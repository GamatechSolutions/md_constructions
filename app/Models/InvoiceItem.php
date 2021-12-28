<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
	protected $fillable = [
		'invoice_id',
		'type',
		'product_id',
		'name',
		'uom',
		'quantity',
		'uom_price',
		'discount',
		'tax_rate'
	];

	public function uomPriceWithTax()
	{
		return $this->uom_price * (1. + ($this->tax_rate / 100.));
	}

	public function taxValue()
	{
		$price = $this->uom_price * $this->quantity;
		
		$price *= (1. - ($this->discount / 100.));

		return $price * ($this->tax_rate / 100.);
	}

	public function totalPrice()
	{
		$price = $this->uom_price * $this->quantity;
		$price *= (1. - ($this->discount / 100.));

		return $price * (1. + ($this->tax_rate / 100.));
	}
}
