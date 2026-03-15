<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncompleteordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incompleteorders', function (Blueprint $table) {
            $table->id();
            $table->string('customerName')->nullable();
            $table->string('customerPhone');
            $table->string('customerAddress')->nullable();
            $table->text('customerNote')->nullable(); // Customer Note
            $table->longText('cartproducts')->nullable(); // Website ID
            $table->integer('subTotal')->nullable();  // Total
            $table->integer('deliveryCharge')->nullable(); // Delivery Charge
            $table->date('orderDate');  // Order Date
            $table->string('status')->default('Processing'); // Steps
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
        Schema::dropIfExists('incompleteorders');
    }
}
