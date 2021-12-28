<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *	
	* @return void
	*/
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('barcode', 50);
			$table->string('previous_barcode', 50)->nullable();
			$table->string('name', 255);
			$table->text('description')->nullable();
			$table->string('image', 50)->nullable();
			$table->double('price');
			$table->foreignId('currency_id');
			$table->double('quantity')->default(0);
			$table->string('uom', 50)->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('products');
	}
}
