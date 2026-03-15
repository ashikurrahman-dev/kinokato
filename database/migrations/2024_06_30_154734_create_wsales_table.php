<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wsales', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceID');
            $table->date('date');
            $table->bigInteger('wcustomer_id');
            $table->float('totalAmount', 10, 2)->default(0);
            $table->float('paid', 10, 2)->default(0);
            $table->float('due', 10, 2)->default(0);
            $table->integer('deliveryCharge')->default(0);
            $table->string('status')->default('Active');
            $table->integer('admin_id');
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
        Schema::dropIfExists('wsales');
    }
}
