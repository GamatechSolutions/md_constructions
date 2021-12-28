<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Models\Product;
use App\Models\Currency;

class ProductController extends Controller
{
	private $actions;

	public function __construct()
	{
		$this->middleware('auth');

		$this->actions = [
			'increase-quantity' => (object)[
				'label'			=> 'Dodaj',
				'permission'	=> 'product.increase-quantity',
				'route'			=> 'product::increase-quantity',
				'class'			=> 'bg-success btn',
				'icon'			=> 'fas fa-plus',
			],
			'decrease-quantity' => (object)[
				'label'			=> 'Oduzmi',
				'permission'	=> 'product.decrease-quantity',
				'route'			=> 'product::decrease-quantity',
				'class'			=> 'bg-danger btn',
				'icon'			=> 'fas fa-minus',
			]
		];
	}

	public function delete(Request $request, $product_id)
	{
		$user = auth()->user();

		if (! $user->can('product.delete'))
		{
			return back();
		}

		$product = Product::findOrFail($product_id);

		$route = route('product::view', [ $product->id ]);

		activity()
			->performedOn($product)
			->causedBy($user)
			->withProperties([ 'action' => 'product-delete' ])
			->log(":causer.name je obrisao/la proizvod <a href='{$route}'>:subject.name (:subject.barcode)</a>");
		
		$product->delete();

		return redirect()->route('product::list-view')->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izbrisali proizvod!'
			]
		]);
	}

	public function createView()
	{
		$user = auth()->user();

		if (! $user->can('product.create'))
		{
			return back();
		}

		$data = [
			'route'			=> route('product::create'),
			'mode'			=> 'create',
			'currencies'	=> Currency::all()
		];

		return view('product.create-edit')->with($data);
	}

	public function editView($product_id)
	{
		$user = auth()->user();

		if (! $user->can('product.edit'))
		{
			return back();
		}

		$product = Product::findOrFail($product_id);
		$data = [
			'route'			=> route('product::edit', [ $product->id ]),
			'product'		=> $product,
			'mode'			=> 'edit',
			'currencies'	=> Currency::all()
		];

		return view('product.create-edit')->with($data);
	}

	public function create(CreateProductRequest $request)
	{
		$input = $request->validated();
		$user = auth()->user();

		$product = Product::create([
			'barcode'		=> $input['barcode'],
			'name'			=> $input['name'],
			'description'	=> $input['description'],
			'image'			=> $input['image'],
			'price'			=> $input['price'],
			'currency_id'	=> $input['currency_id'],
			'quantity'		=> $input['quantity'],
			'uom'			=> $input['uom']
		]);

		$image = $input['image'];

		if (isset($image) && \Storage::exists("temporary/{$image}"))
		{
			\Storage::move("temporary/{$image}", "products/{$image}");
		}

		$route = route('product::view', [ $product->id ]);

		activity()
			->performedOn($product)
			->causedBy($user)
			->withProperties([ 'action' => 'product-create' ])
			->log(":causer.name je dodao/la proizvod <a href='{$route}'>:subject.name (:subject.barcode)</a>");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste dodali proizvod!'
			]
		]);
	}

	public function edit(EditProductRequest $request, $product_id)
	{
		$input = $request->validated();
		$user = auth()->user();

		$product = Product::findOrFail($product_id);

		$input['previous_barcode'] = ($input['barcode'] != $product->barcode) ? $product->barcode : NULL;

		$product->update([
			'barcode'			=> $input['barcode'],
			'previous_barcode'	=> $input['previous_barcode'],
			'name'				=> $input['name'],
			'description'		=> $input['description'],
			'image'				=> $input['image'],
			'price'				=> $input['price'],
			'currency_id'		=> $input['currency_id'],
			'quantity'			=> $input['quantity']
		]);

		$image = $input['image'];
		
		if (isset($image) && \Storage::exists("temporary/{$image}"))
		{
			\Storage::move("temporary/{$image}", "products/{$image}");
		}

		$route = route('product::view', [ $product->id ]);

		activity()
			->performedOn($product)
			->causedBy($user)
			->withProperties([ 'action' => 'product-edit' ])
			->log(":causer.name je izmenio/la proizvod <a href='{$route}'>:subject.name (:subject.barcode)</a>");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izmenili proizvod!'
			]
		]);
	}

	public function listView()
	{
		$data = [
			'actions'	=> $this->actions,
			'products'	=> Product::all()
		];

		return view('product.list')->with($data);
	}

	public function view($product_id)
	{
		$user = auth()->user();

		if (! $user->can('product.view'))
		{
			return back();
		}

		$product = Product::withTrashed()->findOrFail($product_id);
		$data = [
			'actions'	=> $this->actions,
			'product'	=> $product
		];

		return view('product.view')->with($data);
	}

	public function increaseQuantity(Request $request, $product_id)
	{
		$user = auth()->user();

		if (! $user->can('product.increase-quantity'))
		{
			return back();
		}

		$product = Product::findOrFail($product_id);
		$quantity = $request->input('quantity') ?? 0;
		$product->quantity += $quantity;
		$product->save();

		$route = route('product::view', [ $product->id ]);

		activity()
			->performedOn($product)
			->causedBy($user)
			->withProperties([ 'action' => 'product-increase-quantity' ])
			->log(":causer.name je dodao/la {$quantity} <a href='{$route}'>:subject.name (:subject.barcode)</a> na stanje");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno izmenjeno stanje proizvoda!'
			]
		]);
	}

	public function decreaseQuantity(Request $request, $product_id)
	{
		$user = auth()->user();

		if (! $user->can('product.decrease-quantity'))
		{
			return back();
		}

		$product = Product::findOrFail($product_id);
		$quantity = $request->input('quantity') ?? 0;

		if ($quantity > $product->quantity) {
			$quantity = $product->quantity;
		}

		$product->quantity -= $quantity;
		$product->save();

		$route = route('product::view', [ $product->id ]);

		activity()
			->performedOn($product)
			->causedBy($user)
			->withProperties([ 'action' => 'product-decrease-quantity' ])
			->log(":causer.name je skinuo/la {$quantity} <a href='{$route}'>:subject.name (:subject.barcode)</a> sa stanja");

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno izmenjeno stanje proizvoda!'
			]
		]);
	}
}
