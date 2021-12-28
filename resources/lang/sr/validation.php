<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
	'required' => 'Polje :attribute je neophodno.',
	'numeric' => 'Polje :attribute mora biti broj.',
	'email' => 'Polje :attribute mora biti validna email adresa.',
	'date' => 'Polje :attribute mora biti validan datum.',
	'unique' => 'Polje :attribute mora biti jedinstveno.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
		'items'	=> [
            'required' => 'Faktura mora imati bar jednu stavku.',
		],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
		'unique_identifier'		=> 'Broj fakture',
		'invoice_type'			=> 'Tip fakture',
		'client'				=> 'Klijent',
		'client_type'			=> 'Tip klijenta',
		'firm_name'				=> 'Ime firme',
		'firm_id'				=> 'Matični broj',
		'pib'					=> 'PIB',
		'activity_code'			=> 'Šifra delatnosti',
		'payment_deadline'		=> 'Rok za uplatu (dana)',
		'street'				=> 'Ulica i broj',
		'zip_code'				=> 'Poštanski broj',
		'city'					=> 'Grad',
		'state'					=> 'Država',
		'city'					=> 'Grad',
		'contact_email'			=> 'E-mail',
		'phone'					=> 'Telefon',
		'individual_name'		=> 'Ime i prezime',
		'individual_id'			=> 'JMBG',
		'issue_date'			=> 'Datum izdavanja',
		'issue_place'			=> 'Mesto izdavanja',
		'invoice_price_as_word'	=> 'Ukupan iznos slovima',
	],
];
