<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('courier_id')->unsigned();
            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
            $table->bigInteger('zone_id')->unsigned();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->string('areaName');
            $table->string('areaId');
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
        Schema::dropIfExists('areas');
    }
}
