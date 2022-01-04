<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\InvoiceField;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ClientField;
use App\Models\Client;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\CreateClientRequest;
use App\Mail\InvoiceMail;

class InvoiceController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('role:Administrator,Računovođa');

		$this->invoice_issuer = (object) [
			'name'				=> 'MD Constructions & Crafts',
			'state'				=> 'Srbija',
			'city'				=> 'Kragujevac',
			'address'			=> 'Lazara Đokića 25, 34000 Kragujevac			',
			'email'				=> 'office@mdconstructions.rs',
			'phone'				=> '064/307-5265',
			'website'			=> 'www.mdconstructions.rs',
			'pib'				=> '112795263',
			'firm_id'			=> '66360202',
			'activity_code'		=> '0000',
			'account_number'	=> '265361031000113502',
			'IBAN'				=> '',
		];

		$this->action_map = (object) [];

		/** INVOICE TYPE ACTIONS */
		$this->action_map->invoice = (object) [
			'active' => (object) [
				'complete' => (object) [
					'route' 		=> 'admin::invoice-complete',
					'label'			=> 'Potvrda uplate',
					'icon_class'	=> 'fas fa-credit-card',
					'color_class'	=> 'btn-success',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Ovim potvrdjujete da Vam je faktura uplaćena, želite li da nastavite?',
				],
				'cancel' => (object) [
					'route' 		=> 'admin::invoice-cancel',
					'label'			=> 'Otkaži',
					'icon_class'	=> 'fas fa-times',
					'color_class'	=> 'btn-danger',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Da li ste sigurni da želite da otkažete ovu fakturu?',
				]
			]
		];

		/** PROFORMA INVOICE TYPE ACTIONS */
		$this->action_map->proforma_invoice = (object) [
			'active' => (object) [
				'create_invoice' => (object) [
					'route' 		=> 'admin::invoice-from-proforma',
					'label'			=> 'Izdaj fakturu',
					'icon_class'	=> 'fas fa-credit-card',
					'color_class'	=> 'btn-success',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Nakon potvrde ove akcije biće kreirana faktura na osnovu profakture.',
				],
				'cancel' => (object) [
					'route' 		=> 'admin::invoice-cancel',
					'label'			=> 'Otkaži',
					'icon_class'	=> 'fas fa-times',
					'color_class'	=> 'btn-danger',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Da li ste sigurni da želite da otkažete ovu fakturu?',
				]
			]
		];

		/** PROFORMA INVOICE TYPE ACTIONS */
		$this->action_map->advance_invoice = (object) [
			'active' => (object) [
				'create_invoice' => (object) [
					'route' 		=> 'admin::invoice-from-advance',
					'label'			=> 'Izdaj fakturu',
					'icon_class'	=> 'fas fa-credit-card',
					'color_class'	=> 'btn-success',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Nakon potvrde ove akcije biće kreirana faktura na osnovu avansnog računa.',
				],
				'cancel' => (object) [
					'route' 		=> 'admin::invoice-cancel',
					'label'			=> 'Otkaži',
					'icon_class'	=> 'fas fa-times',
					'color_class'	=> 'btn-danger',
					'title'			=> 'Potvrdi akciju',
					'message'		=> 'Da li ste sigurni da želite da otkažete ovu fakturu?',
				]
			]
		];

		view()->share('invoice_issuer', $this->invoice_issuer);
	}

	public function invoice()
	{
		$fields = InvoiceField::getByInvoiceType('invoice');
		$products = Product::where('quantity', '>', 0)
			->get();

		foreach ($products as &$product)
		{
			$product->json = json_encode([
				'id'	=> $product->id,
				'name'	=> $product->name,
				'price'	=> $product->price
			]);
		}

		$data = [
			'fields' 		=> json_encode($fields),
			'products'		=> $products,
			'invoice_type'	=> 'invoice'
		];

		return view('invoice.create', $data);
	}

	public function client()
	{
		$fields = ClientField::getByClientType('legal_entity');

		$data = [
			'fields'		=> json_encode($fields),
			'client_type'	=> 'legal_entity'
		];

		return view('client.create', $data);
	}

	public function editClientView(Request $request, $client_id)
	{
		$client = Client::with('fields')
			->findOrFail($client_id);
		$formFields = ClientField::getByClientType($client->type)
			->keyBy('name');
		$fields = $client->getFields();

		foreach ($fields as $key => $value)
		{
			if ($formFields->has($key))
			{
				$formFields->get($key)->default_value = $value;
			}
		}


		$data = [
			'client'		=> $client,
			'fields'		=> json_encode($formFields->values()),
			'client_type'	=> $client->type
		];

		return view('client.edit', $data);
	}

	public function editClient(CreateClientRequest $request, $client_id)
	{
		$client = Client::with('fields')
			->findOrFail($client_id);
		$fields = $client->fields->keyBy('alias');

		$client->type = $request->input('client_type');
		$client->save();

		// TODO: delete old fields?
		foreach ($request->dynamic_fields as $key => $value)
		{
			if ($fields->has($key))
			{
				$fields->get($key)->value = $value;
			}
			else
			{
				$newFields[$key] = $value;
			}
		}

		if (isset($newFields))
		{
			$client->createFields($newFields);
		}

		$client_name = $request->dynamic_fields['firm_name'] ?? $request->dynamic_fields['individual_name'];

		activity()
			->performedOn($client)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'client-edit' ])
			->log(":causer.name je izmenio/la klijenta {$client_name}");

		return \Response::noContent();
	}

	public function createInvoice(CreateInvoiceRequest $request)
	{
		$invoice = Invoice::create([
			'unique_identifier'	=> $request->input('unique_identifier'),
			'type' 				=> $request->input('invoice_type'),
			'note' 				=> $request->input('invoice_note'),
			'price_as_word' 	=> $request->input('invoice_price_as_word')
		]);
		$invoice->createFields($request->dynamic_fields);
		$invoice_items = $request->input('items');

		foreach ($invoice_items as $item)
		{
			$item = InvoiceItem::create([
				'invoice_id'	=> $invoice->id,
				'type'			=> $item['item-type'],
				'product_id'	=> $item['item-product-id'] ?? 0,
				'name'			=> $item['item-name'],
				'uom'			=> $item['item-uom'],
				'quantity'		=> $item['item-quantity'],
				'uom_price'		=> $item['item-uom-price'],
				'discount'		=> $item['item-discount'],
				'tax_rate'		=> $item['item-tax-rate'],
			]);

			$product = Product::find($item->product_id);
			
			if (isset($product))
			{
				$product->quantity -= $item->quantity;
				$product->save();
			}
		}

		$pdf = \PDF::loadView('invoice.pdf', [
			'invoice'	=> $invoice,
			'fields'	=> $invoice->getViewFields()
		]);

		$invoice->pdf_document = "invoice-{$invoice->id}.pdf";
		$invoice->save();

		\Storage::put("invoices/{$invoice->pdf_document}", $pdf->output());

		$route = route('admin::invoice-view', [ $invoice->id ]);
		activity()
			->performedOn($invoice)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'invoice-create' ])
			->log(":causer.name je dodao/la fakturu <a href='{$route}'>:subject.id</a>");

		$request->session()->flash('message', [
			'type'	=> 'success',
			'text'	=> 'Uspešno ste dodali fakturu!'
		]);

		return \Response::json($invoice);
	}

	public function createInvoiceFromProforma(Request $request, $invoice_id)
	{
		$invoice = Invoice::with('items', 'fields')
			->where('type', 'proforma_invoice')
			->findOrFail($invoice_id);
		$new_invoice = $invoice->clone();
		$new_invoice->unique_identifier  = $request->input()['invoice_new_id'] ?? $invoice->unique_identifier  ;

		$pdf = \PDF::loadView('invoice.pdf', [
			'invoice'		=> $new_invoice,
			'fields'		=> $new_invoice->getViewFields(),
			'parent'		=> $invoice,
			'parent_fields'	=> $invoice->getFields()
		]);

		$new_invoice->pdf_document = "invoice-{$new_invoice->id}.pdf";
		$new_invoice->save();

		$invoice->status = 'issued';
		$invoice->save();

		\Storage::put("invoices/{$new_invoice->pdf_document}", $pdf->output());

		return redirect()->route('admin::invoice-view', [ $new_invoice->id ]);
	}

	public function createInvoiceFromAdvance(Request $request, $invoice_id)
	{
		$invoice = Invoice::with('items', 'fields')
			->where('type', 'advance_invoice')
			->findOrFail($invoice_id);

			

		$parent_fields	= $invoice->getFields();
		$parent_fields	= $invoice->getFields();

		$new_invoice = $invoice->clone();
		$new_invoice->unique_identifier  = $request->input()['invoice_new_id'] ?? $invoice->unique_identifier  ;

		$pdf = \PDF::loadView('invoice.pdf', [
			'invoice'		=> $new_invoice,
			'fields'		=> $new_invoice->getViewFields(),
			'parent'		=> $invoice,
			'parent_fields'	=> $parent_fields
		]);

		$new_invoice->pdf_document = "invoice-{$new_invoice->id}.pdf";
		$new_invoice->save();

		// $invoice->status = 'issued';
		// $invoice->save();

		\Storage::put("invoices/{$new_invoice->pdf_document}", $pdf->output());

		return redirect()->route('admin::invoice-view', [ $new_invoice->id ]);
	}

	public function createClient(CreateClientRequest $request)
	{
		$client = Client::create([
			'type' => $request->input('client_type')
		]);

		$client->createFields($request->dynamic_fields);

		$client_name = $request->dynamic_fields['firm_name'] ?? $request->dynamic_fields['individual_name'];

		activity()
			->performedOn($client)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'client-create' ])
			->log(":causer.name je dodao/la klijenta {$client_name}");

		return \Response::json($client);
	}

	public function invoiceList()
	{
		return view('invoice.list');
	}

	public function clientList()
	{
		return view('client.list');
	}

	public function invoiceView(Request $request, $invoice_id)
	{
		$invoice = Invoice::with('items')
			->findOrFail($invoice_id);
		$last_id = Invoice::orderBy('id','DESC')->where('type', 'invoice')->first()->unique_identifier ?? '';	
		return view('invoice.view', [
			'invoice'		=> $invoice,
			'parent'		=> $invoice->parent,
			'last_id'		=> $last_id,
			'fields'		=> $invoice->getViewFields(),
			'parent_fields'	=> isset($invoice->parent) ? $invoice->parent->getFields() : [],
			'actions'		=> $this->action_map->{$invoice->type}->{$invoice->status} ?? []
		]);
	}

	public function invoiceComplete(Request $request, $invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		// $invoice->status = 'completed';
		// $invoice->save();
		$invoice->updateStatus('completed');

		$route = route('admin::invoice-view', [ $invoice->id ]);
		activity()
			->performedOn($invoice)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'invoice-change-status' ])
			->log(":causer.name je promenio/la status fakture <a href='{$route}'>:subject.id</a> na <strong>Plaćeno</strong>");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izmenili status fakture!'
			]
		]);
	}

	public function invoiceCancel(Request $request, $invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		if ($invoice->status == 'canceled')
		{
			return back()->with([
				'message' => [
					'type'	=> 'warning',
					'text'	=> 'Status fakture je već izmenjen!'
				]
			]);
		}

		$invoice->updateStatus('canceled');

		foreach ($invoice->items as $item)
		{
			$product = Product::find($item->product_id);

			if (isset($product))
			{
				$product->quantity += $item->quantity;
				$product->save();
			}
		}

		$route = route('admin::invoice-view', [ $invoice->id ]);
		activity()
			->performedOn($invoice)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'invoice-change-status' ])
			->log(":causer.name je promenio/la status fakture <a href='{$route}'>:subject.id</a> na <strong>Otkazano</strong>");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izmenili status fakture!'
			]
		]);
	}

	public function sendToMail(Request $request, $invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);
		$validator = \Validator::make($request->all(), [
			'invoice_email' => 'nullable|email',
		]);

		if ($validator->fails()) {
			return back()->with([
				'message' => [
					'type'	=> 'error',
					'text'	=> 'Unesite validnu email adresu!'
				]
			]);
		}

		$email = $request->input('invoice_email') ?? $invoice->getFieldsByAlias('contact_email', true);
		$route = route('admin::invoice-view', [ $invoice->id ]);
		activity()
			->performedOn($invoice)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'invoice-email' ])
			->log(":causer.name je poslao/la fakturu <a href='{$route}'>:subject.id</a> na email <strong>{$email}</strong>");
		
		\Mail::to($email)->send(
			new InvoiceMail($invoice)
		);

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste poslali fakturu putem email-a!'
			]
		]);
	}

	public function invoiceDownloadPDF(Request $request, $invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		if (strlen($invoice->pdf_document) && \Storage::exists("invoices/{$invoice->pdf_document}"))
		{
			return \Storage::download("invoices/{$invoice->pdf_document}");
		}

		return abort(404);
	}

	public function deleteInvoice(Request $request, $invoice_id)
	{
		$invoice = Invoice::with('fields', 'items')
			->findOrFail($invoice_id);

		activity()
			->performedOn($invoice)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'invoice-delete' ])
			->log(":causer.name je obrisao/la fakturu :subject.id");

		if ($invoice->status != 'canceled')
		{
			$invoice->updateStatus('canceled');

			foreach ($invoice->items as $item)
			{
				$product = Product::find($item->product_id);
				
				if (isset($product))
				{
					$product->quantity += $item->quantity;
					$product->save();
				}
			}
		}

		$invoice->cleanup();

		$response = [
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izbrisali fakturu!'
			]
		];

		if($request->ajax())
		{
			return \Response::json($response);
		}

		return redirect()
			->route('admin::invoice-list')
			->with($response);
	}

	public function deleteClient(Request $request, $client_id)
	{
		$client = Client::with('fields')
			->findOrFail($client_id);

		$client_name = $client->getFieldsByAlias('firm_name', true) ?? $client->getFieldsByAlias('individual_name', true);

		activity()
			->performedOn($client)
			->causedBy(auth()->user())
			->withProperties([ 'action' => 'client-delete' ])
			->log(":causer.name je obrisao/la klijenta {$client_name}");

		$client->cleanup();

		$response = [
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izbrisali klijenta!'
			]
		];

		if($request->ajax())
		{
			return \Response::json($response);
		}

		return redirect()
			->route('admin::client-list')
			->with($response);
	}

	/** JSON RESPONSES */
	public function getInvoiceTypeFields(Request $request)
	{
		$invoice_type = $request->input('invoice_type') ?? 'invoice';
		$fields = InvoiceField::getByInvoiceType($invoice_type);

		return \Response::json($fields);
	}

	public function getClientTypeFields(Request $request)
	{
		$client_type = $request->input('client_type') ?? 'legal_entity';
		$fields = ClientField::getByClientType($client_type);

		return \Response::json($fields);
	}

	public function getInvoices(Request $request)
	{
		$invoices = Invoice::with('items')
			->get();
		$json = [];

		foreach ($invoices as $invoice)
		{
			$json[] = $invoice->getViewFields();
		}

		return \Response::json($json);
	}

	public function getClients(Request $request)
	{
		$clients = Client::get();
		$json = [];

		foreach ($clients as $client)
		{
			$fields = $client->getFields();

			$fields['id'] = $client->id;
			$fields['client_type'] = $client->type;
			$fields['client_type_label'] = trans("alias.client_type.{$client->type}");

			$json[] = $fields;
		}

		return \Response::json($json);
	}

	public function getProductsJSON()
	{
		$products = Product::where('quantity', '>', 0)
		  ->get();
	
		return \Response::json($products);
	}
}
