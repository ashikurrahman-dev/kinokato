<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurcheseproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purcheseproducts', function (Blueprint $table) {
            $table->id();
            $table->integer('purchese_id');
            $table->integer('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('size_id')->nullable();
            $table->string('size')->nullable();
            $table->float('product_price', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->float('total', 10, 2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
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
        Schema::dropIfExists('purcheseproducts');
    }
}
