<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWcustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wcustomers', function (Blueprint $table) {
            $table->id();
            $table->string('wcustomerName');
            $table->string('wcustomerPhone');
            $table->string('wcustomerEmail')->nullable();
            $table->string('wcustomerAddress')->nullable();
            $table->text('wcustomerProfile')->nullable();
            $table->string('wcustomerCompanyName')->nullable();
            $table->float('wcustomerTotalAmount')->default(0);
            $table->float('wcustomerPaidAmount')->default(0);
            $table->float('wcustomerDueAmount')->default(0);
            $table->float('wcustomerPartialAmount')->default(0);
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
        Schema::dropIfExists('wcustomers');
    }
}