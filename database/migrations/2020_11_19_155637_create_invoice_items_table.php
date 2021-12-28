<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('invoice_id');
			$table->enum('type', [ 'service', 'product' ])->default('service');
			$table->unsignedBigInteger('product_id')->default(0);
			$table->string('name');
			$table->string('uom');
			$table->double('quantity')->default(1.);
			$table->double('uom_price');
			$table->double('discount')->default(0.);
			$table->double('tax_rate')->default(0.);
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
        Schema::dropIfExists('invoice_items');
    }
}
