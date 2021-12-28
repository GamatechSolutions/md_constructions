<?php

namespace Gamatech\DynamicParameters\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class DynamicMorphValue extends Model
{
	protected $with = [ 'value' ];

	protected $fillable = [
		'value_id', 'value_type'
	];

	public function value()
	{
		return $this->morphTo();
	}

	public function setValueAttribute($value)
	{
		if (!is_subclass_of($value, Model::class))
		{
			throw new Exception('DynamicMorphValue Exception.');
		}

		$this->attributes['value_id'] = $value->id;
		$this->attributes['value_type'] = get_class($value);
	}
}
