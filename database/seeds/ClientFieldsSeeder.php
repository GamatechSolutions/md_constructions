<?php

use Illuminate\Database\Seeder;
use App\Models\ClientField;

class ClientFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->seedLegalEntityTypeFields();
		$this->seedIndividualTypeFields();
	}
	
	// Form fields for "legal_entity" type client (Pravno lice)
	private function seedLegalEntityTypeFields()
	{
		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'firm_name',
			'type'			=> 'text',
			'label'			=> 'Naziv firme',
			'validation'	=> 'required',
		]);

/* 		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'client',
			'type'			=> 'text',
			'label'			=> 'Ime Klijent',
			'validation'	=> ''
		]);	 */

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'firm_id',
			'type'			=> 'text',
			'label'			=> 'Matični broj',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'pib',
			'type'			=> 'text',
			'label'			=> 'PIB',
			'validation'	=> 'required',
		]);

/* 		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'activity_code',
			'type'			=> 'text',
			'label'			=> 'Šifra delatnosti',
			'validation'	=> '',
		]); */

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'payment_deadline',
			'type'			=> 'text',
			'label'			=> 'Rok za uplatu (dana)',
			'default_value' => '30'
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica i broj',
			'validation'	=> 'required',
		]);
		
		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		ClientField::create([
			'client_type'	=> 'legal_entity',
			'name'			=> 'phone',
			'type'			=> 'text',
			'label'			=> 'Telefon',
		]);
		
	
	}

	// Form fields for "individual" type client (Fizičko lice)
	private function seedIndividualTypeFields()
	{
		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'individual_name',
			'type'			=> 'text',
			'label'			=> 'Ime i prezime',
			'validation'	=> 'required',
		]);
		
		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'individual_id',
			'type'			=> 'text',
			'label'			=> 'JMBG',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'payment_deadline',
			'type'			=> 'text',
			'label'			=> 'Rok za uplatu (dana)',
			'default_value' => '30'
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'street',
			'type'			=> 'text',
			'label'			=> 'Ulica i broj',
			'validation'	=> 'required',
		]);
		
		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'zip_code',
			'type'			=> 'text',
			'label'			=> 'Poštanski broj',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'city',
			'type'			=> 'text',
			'label'			=> 'Grad',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'state',
			'type'			=> 'text',
			'label'			=> 'Država',
			'validation'	=> 'required',
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'contact_email',
			'type'			=> 'text',
			'label'			=> 'Kontakt email',
		]);

		ClientField::create([
			'client_type'	=> 'individual',
			'name'			=> 'phone',
			'type'			=> 'text',
			'label'			=> 'Telefon',
		]);
	}
}
