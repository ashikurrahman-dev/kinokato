<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mainproducts', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('ProductName');
            $table->string('ProductSlug');
            $table->string('position')->nullable();
            $table->string('ProductImage');
            $table->string('RelatedProductIds');
            $table->string('status')->default('Active');
            $table->tinyInteger('top_rated')->default('0');
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
        Schema::dropIfExists('mainproducts');
    }
}
