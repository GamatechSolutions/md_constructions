<?php

namespace Gamatech\DynamicParameters\Traits;

use Gamatech\DynamicParameters\Models\DynamicField;

trait HasDynamicFields
{
	public function fields()
	{
		return $this->hasMany(DynamicField::class, 'entity_id')
			->where('entity_type', static::class);
	}

	public function createFields(array $data)
	{
		foreach ($data as $field_key => $field_value)
		{
			if (is_array($field_value))
			{
				foreach($field_value as $value)
				{
					if (isset($value))
					{
						DynamicField::create($this, $field_key, $value);
					}
				}

				continue;
			}

			if (isset($field_value))
			{
				DynamicField::create($this, $field_key, $field_value);
			}
		}
	}

	public function groupFieldsByAlias()
	{
		$data = (object) [];

		foreach ($this->fields as $field)
		{
			$alias = $field->alias;

			if (!isset($data->$alias))
			{
				$data->$alias = [];
			}

			$data->$alias[] = $field->value;
		}

		return $data;
	}

	public function getFieldsByAlias($alias, $isSingle = false)
	{
		$fields = sizeof($this->fields) ? [] : null;

		foreach ($this->fields->where('alias', '=', $alias) as $field)
		
		while (sizeof($collection) > 0)
		{
			$field = $collection->pop();

			if ($isSingle)
			{
				return $field->value;
			}

			$fields[] = $field->value;
		}

		return $fields;
	}

	public function getFields()
	{
		$fields = sizeof($this->fields) ? [] : null;

		foreach ($this->fields as $field)
		{
			$alias = $field->alias;

			if (isset($fields[$alias]))
			{
				if (! is_array($fields[$alias]))
				{
					$fields[$alias] = [ $fields[$alias] ];
				}
				
				$fields[$alias][] = $field->value;
			}
			else
			{
				$fields[$alias] = $field->value;
			}
		}

		return $fields;
	}
}