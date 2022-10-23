<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_transactions', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('fruit_commodity_id');
            $table->foreign('fruit_commodity_id')->references('id')->on('fruit_commodities')->onDelete('cascade');
            $table->float('weight');
            $table->integer("payment");
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
        Schema::dropIfExists('farmer_transactions');
    }
}
