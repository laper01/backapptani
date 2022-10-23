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
            $table->foreignUuid('collector_id');
            $table->foreign('collector_id')->references('id')->on('collectors')->onDelete('cascade');
            $table->foreignUuid('fruit_id');
            $table->foreign('fruit_id')->references('id')->on('fruits')->onDelete('cascade');
            $table->date('blossoms_tree_date')->nullable();
            $table->date('harvesting_date')->nullable();
            $table->string('fruit_grade')->nullable();
            $table->boolean('verified')->nullable();
            $table->date('verfied_date')->nullable();
            $table->float('weight')->nullable();
            $table->integer("price_kg")->nullable();
            $table->float('weight_selled')->nullable();
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
