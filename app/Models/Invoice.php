<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Gamatech\DynamicParameters\Traits\HasDynamicFields;
use App\Models\InvoiceItem;
use Carbon\Carbon;

class Invoice extends Model
{
	use HasDynamicFields;

	protected $with = [ 'items', 'parent' ];

	protected $fillable = [
		'unique_identifier',
		'type',
		'note',
		'price_as_word',
		'pdf_document'
	];

	public function items()
	{
		return $this->hasMany(InvoiceItem::class);
	}

	public function parent()
	{
		return $this->hasOne(Invoice::class, 'id', 'parent_id');
	}

	public function getViewFields()
	{
		$fields = (object) $this->getFields();

		foreach ($fields as $key => &$value)
		{
			if ($key == 'payment_deadline')
			{
				$date = $fields->issue_date ?? $this->created_at;
				$value = Carbon::create($date)
					->addDays($value)
					->format('d.m.Y.');
			}
			elseif ($key == 'issue_date')
			{
				$value = Carbon::create($value)
					->format('d.m.Y.');
			}
		}

		$fields->base_price = 0;
		$fields->discount_value = 0;
		$fields->tax_value = 0;
		$fields->price = 0;

		foreach ($this->items as $item)
		{
			$base_price = $item->uom_price * $item->quantity;
			$discount_value = $base_price * ($item->discount / 100.);
			$tax_value = ($base_price - $discount_value) * ($item->tax_rate / 100.);
			$price = ($base_price - $discount_value) + $tax_value;

			$fields->base_price += $base_price;
			$fields->discount_value += $discount_value;
			$fields->tax_value += $tax_value;
			$fields->price += $price;
		}

		$fields->id					= $this->id;
		$fields->type				= trans("alias.invoice_type.{$this->type}");
		$fields->status				= trans("alias.invoice_status.{$this->status}");
		// $fields->unique_number	= ($this->parent_id > 0 ? $this->parent_id : $this->id) . '-' . $this->created_at->format('Y');
		$fields->unique_number		= $this->unique_identifier;

		return $fields;
	}

	public function cleanup()
	{
		if (\Storage::exists("invoices/{$this->pdf_document}"))
		{
			@\Storage::delete("invoices/{$this->pdf_document}");
		}

		foreach ($this->items as $item)
		{
			$item->delete();
		}

		foreach ($this->fields as $field)
		{
			$field->cleanup();
		}

		return $this->delete();
	}

	public function clone()
	{
		$invoice = $this->replicate();
		$invoice->parent_id = $this->id;
		$invoice->created_at = $this->created_at;
		$invoice->type = 'invoice';
		$invoice->save();

		foreach ($this->items as $item)
		{
			$new_item = $item->replicate();
			$new_item->invoice_id = $invoice->id;
			$new_item->save();
		}

		foreach ($this->fields as $field)
		{
			$new_field = $field->replicate();
			$new_source = $field->source->replicate();

			$new_source->save();
			$new_field->entity_id = $invoice->id;
			$new_field->source_id = $new_source->id;
			$new_field->save();
		}

		return $invoice;
	}

	public function updateStatus($status, $deep = true)
	{
		$this->status = $status;
		$this->save();

		$parent = Invoice::find($this->parent_id);

		if ($deep && isset($parent))
		{
			$parent->updateStatus($status, $deep);
		}
	}
}
