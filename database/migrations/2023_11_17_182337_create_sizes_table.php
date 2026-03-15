<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->integer('size_id');
            $table->string('size');
            $table->decimal('RegularPrice', 10, 2)->default(0);
            $table->decimal('SalePrice', 10, 2)->default(0);
            $table->decimal('Discount', 10, 2)->default(0);
            $table->decimal('Wholesale', 10, 2)->default(0);
            $table->integer('total_stock')->default(0);
            $table->integer('available_stock')->default(0);
            $table->integer('sold')->default(0);
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
        Schema::dropIfExists('sizes');
    }
}
