<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->integer('purchese_id');
            $table->integer('amount')->nullable();
            $table->string('trx_id')->nullable();
            $table->date('date');
            $table->integer('admin_id');
            $table->integer('payment_type_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->text('comments');
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
        Schema::dropIfExists('supplier_payments');
    }
}
