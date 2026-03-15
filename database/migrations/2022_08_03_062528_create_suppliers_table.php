<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplierName');
            $table->string('supplierPhone');
            $table->string('supplierEmail')->nullable();
            $table->string('supplierAddress')->nullable();
            $table->text('supplierProfile')->nullable();
            $table->string('supplierCompanyName')->nullable();
            $table->float('supplierTotalAmount')->default(0);
            $table->float('supplierPaidAmount')->default(0);
            $table->float('supplierDueAmount')->default(0);
            $table->float('supplierPartialAmount')->default(0);
            $table->string('status')->default('Active');
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
        Schema::dropIfExists('suppliers');
    }
}
