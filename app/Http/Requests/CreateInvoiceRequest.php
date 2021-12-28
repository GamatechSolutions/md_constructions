<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\InvoiceField;

class CreateInvoiceRequest extends FormRequest
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
				'unique_identifier'		=> 'required|unique:invoices',
				'invoice_type'			=> 'required',
				'invoice_price_as_word'	=> 'required',
				'items'					=> 'required|array',
				'items.*.item-type'		=> 'required',
				'items.*.item-name'		=> 'required',
				'items.*.item-uom'		=> 'required',
			]
		);
	}

	public function messages()
	{
		return [];
	}

	private function initDynamicFields()
	{
		$invoice_type = $this->input('invoice_type');
		$fields = InvoiceField::getByInvoiceType($invoice_type)
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
