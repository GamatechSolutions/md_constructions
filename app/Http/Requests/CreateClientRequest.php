<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ClientField;

class CreateClientRequest extends FormRequest
{
    public $dynamic_fields;
	public $dynamic_rules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$this->dynamic_fields = [];
		$this->dynamic_rules = [];

		$this->initDynamicFields();

		return array_merge(
			$this->dynamic_rules,
			[
				'client_type'	=> 'required',
			]
		);
	}

	private function initDynamicFields()
	{
		$client_type = $this->input('client_type');
		$fields = ClientField::getByClientType($client_type)
			->keyBy('name');

		foreach ($fields as $name => $field)
		{
			$use = true;
			$input = $this->input($name);

			if (isset($field->dependencies))
			{
				foreach ($field->dependencies as $key => $value)
				{
					$dependency = $this->input($key);

					$use &= ($dependency == $value);
				}
			}

			if ($use)
			{
				$this->dynamic_fields[$name] = $input;

				if (isset($field->validation))
				{
					$this->dynamic_rules[$name] = $field->validation;
				}
			}
		}
	}
}
