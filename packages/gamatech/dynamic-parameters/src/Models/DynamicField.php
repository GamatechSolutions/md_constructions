<?php

namespace Gamatech\DynamicParameters\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DynamicField extends Model
{
	protected $fillable = [
		'entity_id', 'entity_type', 'alias', 'source_id', 'source_type'
	];

	public static function create($entity, $alias, $value)
	{
		if (!is_subclass_of($entity, Model::class))
		{
			throw new Exception('DynamicField Exception.');
		}

		if (is_subclass_of($value, Model::class))
		{
			$source = DynamicMorphValue::create([
				'value_id'		=> $value->id,
				'value_type'	=> get_class($value)
			]);
		}
		elseif (is_scalar($value) || is_null($value))
		{
			$source = DynamicScalarValue::create([
				'value' => $value
			]);
		}
		else
		{
			throw new Exception('DynamicField Exception.');
		}

		return static::query()->create([
			'entity_id'		=> $entity->id,
			'entity_type'	=> get_class($entity),
			'alias'			=> $alias,
			'source_id'		=> $source->id,
			'source_type'	=> get_class($source),
		]);
	}

	protected function source()
    {
        return $this->morphTo();
	}

	public function getValueAttribute($value)
	{
		return $this->source->value;
	}

	public function setValueAttribute($value)
	{
		$this->source->value = $value;

		$this->source->save();
	}

	public function cleanup()
	{
		$this->source->delete();

		return $this->delete();
	}
}
