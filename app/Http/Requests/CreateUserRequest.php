<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return auth()->user()->hasRole('Administrator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'name'		=> 'required|max:50',
			'email'		=> 'required|email|unique:users|max:50',
			'password'	=> 'required|confirmed',
			'role'		=> 'required|integer|exists:roles,id'
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
			'name.required'			=> 'Ovo polje je neophodno.',
			'name.max'				=> 'Ime ne sme biti duže od :max karaktera.',
			'email.required'		=> 'Ovo polje je neophodno.',
			'email.email'			=> 'Email nije validnog formata.',
			'email.unique'			=> 'Email se već koristi.',
			'email.max'				=> 'Email ne sme biti duže od :max karaktera.',
			'password.required'		=> 'Ovo polje je neophodno.',
			'password.confirmed'	=> 'Lozinke se ne poklapaju.',
			'role.required'			=> 'Ovo polje je neophodno.'
		];
    }
}
