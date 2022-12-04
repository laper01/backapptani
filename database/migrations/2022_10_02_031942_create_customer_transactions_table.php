<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id');
            $table->foreignUuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignUuid('farmer_transaction_id');
            $table->foreign('farmer_transaction_id')->references('id')->on('farmer_transactions')->onDelete('cascade');
            $table->date('shiping_date');
            $table->float('weight');
            $table->integer('total_payment');
            $table->string('struck_link')->nullable();
            $table->string('address');
            $table->string("receiver_name");
            $table->integer("shiping_payment");
            $table->integer("price");
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
        Schema::dropIfExists('customer_transactions');
    }
}
