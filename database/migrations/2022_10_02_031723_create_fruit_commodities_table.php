<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFruitCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fruit_commodities', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('farmer_id');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->foreignUuid('colector_id');
            $table->foreign('colector_id')->references('id')->on('collectors')->onDelete('cascade');
            $table->foreignUuid('fruit_id');
            $table->foreign('fruit_id')->references('id')->on('fruits')->onDelete('cascade');
            $table->date('blossoms_tree_date');
            $table->date('harvesting_date')->nullable();
            $table->string('fruit_grade');
            $table->boolean('verified');
            $table->date('verfied_date');
            $table->float('weight');
            $table->integer("price_kg");
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
        Schema::dropIfExists('fruit_commodities');
    }
}
