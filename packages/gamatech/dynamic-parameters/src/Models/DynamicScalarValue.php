<?php

namespace Gamatech\DynamicParameters\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class DynamicScalarValue extends Model
{
	private $id = 'id';
    protected $fillable = [
		'id',
		'type',
		'value'
	];

	public function getValueAttribute($value)
	{
		switch ($this->type) {
			case 'boolean':
				return boolval($value);

			case 'integer':
				return intval($value);

			case 'double':
				return floatval($value);

			case 'string':
				return strval($value);
		}

		return NULL;
	}

	public function setValueAttribute($value)
	{
		
		if (!(is_scalar($value) || is_null($value)))
		{
			throw new Exception('DynamicScalarValue Exception.');
		}

		$this->attributes['type'] = gettype($value);
		$this->attributes['value'] = $value;
	}
}
