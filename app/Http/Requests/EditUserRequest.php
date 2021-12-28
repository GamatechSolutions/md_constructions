<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class EditUserRequest extends FormRequest
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
			'email'		=> 'required|email|max:50',
			'password'	=> 'confirmed',
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
			'email.max'				=> 'Email ne sme biti duže od :max karaktera.',
			'password.confirmed'	=> 'Lozinke se ne poklapaju.',
			'role.required'			=> 'Ovo polje je neophodno.'
		];
	}
	
	/**
	 * Configure the validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator  $validator
	 * @return void
	 */
	public function withValidator($validator)
	{
		$validator->after(function ($validator) {
			$user = User::where('email', $this->email)->first();

			if (isset($user) && ($user->id != $this->user_id))
			{
				$validator->errors()->add('email', 'Email se već koristi.');
			}
		});
	}
}
