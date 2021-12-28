<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_fields', function (Blueprint $table) {
            $table->id();
			$table->enum('client_type', ['legal_entity', 'individual'])->default('legal_entity');
			$table->string('name');
			$table->string('type');
			$table->string('label');
			$table->string('placeholder')->nullable();
			$table->string('validation')->nullable();
			$table->string('default_value')->nullable();
			$table->json('source')->nullable();
			$table->json('dependencies')->nullable();
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
        Schema::dropIfExists('client_fields');
    }
}
