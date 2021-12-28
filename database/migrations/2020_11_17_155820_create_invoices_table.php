<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('parent_id')->default(0);
			$table->enum('type', ['invoice', 'proforma_invoice', 'advance_invoice', 'delivery_note'])->default('invoice');
			$table->enum('status', ['active', 'completed', 'canceled', 'issued'])->default('active');
			$table->text('note')->nullable();
			$table->string('price_as_word')->nullable();
			$table->string('pdf_document')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
