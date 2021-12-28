<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceField extends Model
{
    protected $fillable = [
		'invoice_type',
		'name',
		'type',
		'label',
		'placeholder',
		'default_value',
		'source',
		'dependencies'
	];

	public static function getByInvoiceType($invoice_type)
	{
		$fields = InvoiceField::where('invoice_type', $invoice_type)
			->get();

		foreach ($fields as &$field)
		{
			if (isset($field->source))
			{
				$source = json_decode($field->source);

				switch ($source->type)
				{
					case 'array':
						$field->source = $source->values;
					break;

					case 'model':
						$field->source = [];

						if (class_exists($source->model))
						{
							$class = new \ReflectionClass($source->model);

							if ($class->isSubclassOf(\Illuminate\Database\Eloquent\Model::class))
							{
								$records = $class->newInstanceWithoutConstructor()->get();
							}
						}

						foreach ($records as $record)
						{
							$values = array_map(static function ($key) use ($record) {
								return $record->{$key};
							}, $source->value);

							$source->values[$record->{$source->key}] = implode('-', $values);
						}

						$field->source = $source->values;
					break;
				}
			}

			if (isset($field->dependencies))
			{
				$field->dependencies = json_decode($field->dependencies);
			}
		}

		return $fields;
	}
}
