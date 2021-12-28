<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		$user = auth()->user();

		return $user->can('product.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'barcode'		=> 'required|unique:products|max:50',
			'name'			=> 'required|max:255',
			'description'	=> 'max:5000',
			'price'			=> 'required|numeric|min:0',
			'image'			=> '',
			'currency_id'	=> 'required|integer|exists:currencies,id',
			'quantity'		=> 'numeric|min:0',
			'uom'			=> ''
		];
	}
	
	/**
	 * Get the validation messages that apply to the request.
	 *
	 * @return array
	 */
	public function messages()
    {
        return [
			'barcode.required'		=> 'Ovo polje je neophodno.',
			'barcode.unique'		=> 'Ident-No. već postoji.',
			'barcode.max'			=> 'Ident-No. ne sme biti duži od :max karaktera.',
			'name.required'			=> 'Ovo polje je neophodno.',
			'name.max'				=> 'Ime ne sme biti duže od :max karaktera.',
			'description.max'		=> 'Opis ne sme biti duži od :max karaktera.',
			'price.required'		=> 'Ovo polje je neophodno.',
			'price.numeric'			=> 'Cena mora imati numerički format.',
			'price.min'				=> 'Cena mora biti pozitivna.',
			'quantity.integer'		=> 'Količina mora imati numerički format.',
			'quantity.min'			=> 'Količina mora biti pozitivna.',
		];
    }
}
