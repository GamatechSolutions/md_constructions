<?php

use Illuminate\Database\Seeder;
use App\Models\InvoiceField;

class InvoiceFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		/** RAČUN */

		$this->seedInvoiceTypeFields();
		$this->seedProformaInvoiceTypeFields();
		$this->seedAdvanceInvoiceTypeFields();
		//$this->seedDeliveryNoteTypeFields();
	}

	// Form fields for "invoice" type invoice (Račun)
	private function seedInvoiceTypeFields()
	{
		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'client_type',
			'type'			=> 'select',
			'label'			=> 'Tip klijenta',
			'validation'	=> 'required',
			'default_value'	=> 'legal_entity',
			'source'		=> json_encode([
				'type'		=> 'array',
				'values'	=> [
					'legal_entity'	=> 'Pravno lice',
					'individual'	=> 'Fizičko lice'
				]
			])
		]);

		// InvoiceField::create([
		// 	'invoice_type'	=> 'invoice',
		// 	'name'			=> 'test_field',
		// 	'type'			=> 'select',
		// 	'label'			=> 'Test',
		// 	'validation'	=> 'required',
		// 	'default_value'	=> '',
		// 	'source'		=> json_encode([
		// 		'type'		=> 'model',
		// 		'model'		=> 'App\\Models\\Currency',
		// 		'key'		=> 'code',
		// 		'value'		=> [ 'name', 'symbol' ]
		// 	])
		// ]);

/* 		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'client',
			'type'			=> 'text',
			'label'			=> 'Klijent',
			'validation'	=> 'required'
		]);	 */	
			/** PRAVNO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'firm_name',
			'type'			=> 'text',
			'label'			=> 'Naziv firme',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'firm_id',
			'type'			=> 'text',
			'label'			=> 'Matični broj',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'pib',
			'type'			=> 'text',
			'label'			=> 'PIB',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

			/** FIZIČKO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'individual_name',
			'type'			=> 'text',
			'label'			=> 'Ime i prezime',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'individual_id',
			'type'			=> 'text',
			'label'			=> 'JMBG',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
		]);

/* 		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'contact_person',
			'type'			=> 'text',
			'label'			=> 'Kontakt osoba',
		]); */

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'issue_date',
			'type'			=> 'date',
			'label'			=> 'Datum izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'delivery_date',
			'type'			=> 'date',
			'label'			=> 'Datum prometa robe',
		]);
		
		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'issue_place',
			'type'			=> 'text',
			'label'			=> 'Mesto izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'payment_deadline',
			'type'			=> 'text',
			'label'			=> 'Rok za uplatu (dana)',
			'validation'	=> 'required'
		]);

		// delivery note
		InvoiceField::create([
			'invoice_type'	=> 'invoice',
			'name'			=> 'is_delivery_note',
			'type'			=> 'checkbox',
			'label'			=> 'Račun je ujedno i otpremnica',
			'validation'	=> '',
		]);
	}

	// Form fields for "proforma_invoice" type invoice (Predačun)
	private function seedProformaInvoiceTypeFields()
	{
		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'client_type',
			'type'			=> 'select',
			'label'			=> 'Tip klijenta',
			'validation'	=> 'required',
			'default_value'	=> 'legal_entity',
			'source'		=> json_encode([
				'type'		=> 'array',
				'values'	=> [
					'legal_entity'	=> 'Pravno lice',
					'individual'	=> 'Fizičko lice'
				]
			])
		]);
			
			/** PRAVNO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'firm_name',
			'type'			=> 'text',
			'label'			=> 'Naziv firme',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'firm_id',
			'type'			=> 'text',
			'label'			=> 'Matični broj',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'pib',
			'type'			=> 'text',
			'label'			=> 'PIB',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

			/** FIZIČKO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'individual_name',
			'type'			=> 'text',
			'label'			=> 'Ime i prezime',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'individual_id',
			'type'			=> 'text',
			'label'			=> 'JMBG',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
		]);

/* 		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'contact_person',
			'type'			=> 'text',
			'label'			=> 'Kontakt osoba',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]); */

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'issue_date',
			'type'			=> 'date',
			'label'			=> 'Datum izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'issue_place',
			'type'			=> 'text',
			'label'			=> 'Mesto izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'payment_deadline',
			'type'			=> 'text',
			'label'			=> 'Rok za uplatu (dana)',
			'validation'	=> 'required'
		]);

		// delivery note
		InvoiceField::create([
			'invoice_type'	=> 'proforma_invoice',
			'name'			=> 'is_delivery_note',
			'type'			=> 'checkbox',
			'label'			=> 'Račun je ujedno i otpremnica',
			'validation'	=> '',
		]);
	}

	// Form fields for "advance_invoice" type invoice (Avansni račun)
	private function seedAdvanceInvoiceTypeFields()
	{
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'client_type',
			'type'			=> 'select',
			'label'			=> 'Tip klijenta',
			'validation'	=> 'required',
			'default_value'	=> 'legal_entity',
			'source'		=> json_encode([
				'type'		=> 'array',
				'values'	=> [
					'legal_entity'	=> 'Pravno lice',
					'individual'	=> 'Fizičko lice'
				]
			])
		]);

/* 		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'client',
			'type'			=> 'text',
			'label'			=> 'Klijent',
			'validation'	=> 'required'
		]); */
			
			/** PRAVNO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'firm_name',
			'type'			=> 'text',
			'label'			=> 'Naziv firme',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'firm_id',
			'type'			=> 'text',
			'label'			=> 'Matični broj',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'pib',
			'type'			=> 'text',
			'label'			=> 'PIB',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

			/** FIZIČKO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'individual_name',
			'type'			=> 'text',
			'label'			=> 'Ime i prezime',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'individual_id',
			'type'			=> 'text',
			'label'			=> 'JMBG',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
		]);

	/* 	InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'contact_person',
			'type'			=> 'text',
			'label'			=> 'Kontakt osoba',
		]); */

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'issue_date',
			'type'			=> 'date',
			'label'			=> 'Datum izdavanja',
			'validation'	=> 'required'
		]);
		
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'delivery_date',
			'type'			=> 'date',
			'label'			=> 'Datum izdavanja',
		]);
		
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'issue_place',
			'type'			=> 'text',
			'label'			=> 'Mesto izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'payment_deadline',
			'type'			=> 'text',
			'label'			=> 'Rok za uplatu (dana)',
			'validation'	=> 'required'
		]);

		// payment
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'advance_price',
			'type'			=> 'text',
			'label'			=> 'Za avansnu uplatu',
			'validation'	=> 'required',
			'default_value'	=> '0'
		]);

		// delivery note
		InvoiceField::create([
			'invoice_type'	=> 'advance_invoice',
			'name'			=> 'is_delivery_note',
			'type'			=> 'checkbox',
			'label'			=> 'Račun je ujedno i otpremnica',
			'validation'	=> '',
		]);
	}

	//Form fields for "delivery_note" type invoice (Otpremnica)
	private function seedDeliveryNoteTypeFields()
	{
		
		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'client_type',
			'type'			=> 'select',
			'label'			=> 'Tip klijenta',
			'validation'	=> 'required',
			'default_value'	=> 'legal_entity',
			'source'		=> json_encode([
				'type'		=> 'array',
				'values'	=> [
					'legal_entity'	=> 'Pravno lice',
					'individual'	=> 'Fizičko lice'
				]
			])
		]);
			
			/** PRAVNO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'firm_name',
			'type'			=> 'text',
			'label'			=> 'Naziv firme',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'firm_id',
			'type'			=> 'text',
			'label'			=> 'Matični broj',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'pib',
			'type'			=> 'text',
			'label'			=> 'PIB',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'legal_entity'
			])
		]);

			/** FIZIČKO LICE */
		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'individual_name',
			'type'			=> 'text',
			'label'			=> 'Ime i prezime',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'individual_id',
			'type'			=> 'text',
			'label'			=> 'JMBG',
			'validation'	=> 'required',
			'dependencies'	=> json_encode([
				'client_type' => 'individual'
			])
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
		]);

/* 		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'contact_person',
			'type'			=> 'text',
			'label'			=> 'Kontakt osoba',
		]); */

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'issue_date',
			'type'			=> 'text',
			'label'			=> 'Datum izdavanja',
			'validation'	=> 'required'
		]);

		InvoiceField::create([
			'invoice_type'	=> 'delivery_note',
			'name'			=> 'issue_place',
			'type'			=> 'text',
			'label'			=> 'Mesto izdavanja',
			'validation'	=> 'required'
		]);
	}
}
