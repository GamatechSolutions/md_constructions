<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Gamatech\DynamicParameters\Traits\HasDynamicFields;

class Client extends Model
{
    use HasDynamicFields;

	protected $fillable = [
		'type'
	];

	public function cleanup()
	{
		foreach ($this->fields as $field)
		{
			$field->cleanup();
		}

		return $this->delete();
	}
}
