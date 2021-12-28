<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'barcode',
		'previous_barcode',
		'name',
		'description',
		'image',
		'price',
		'currency_id',
		'quantity',
		'uom'
	];

	public function currency()
	{
		return $this->hasOne(Currency::class, 'id', 'currency_id');
	}

	public function getImageURL()
    {
		if (! isset($this->image))
		{
			return Product::defaultImageURL();
		}

		// return \Storage::url("products/{$this->image}");
		return route('file::image', [ 'filename' => $this->image, 'directory' => 'products' ]);
	}
	
	static public function defaultImageURL()
	{
		return asset('assets/default-product-image1.jpg');
	}
}
