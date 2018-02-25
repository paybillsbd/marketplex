<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('sale_transaction_id')->unsigned();
            $table->string('purpose', 100);
            $table->float('amount', 10, 2)->default(0.0);
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'sale_transaction_id', 'created_at'], 'shipping_bills_index');
            $table->foreign('sale_transaction_id')
                    ->references('id')->on('sale_transactions')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_bills');
    }
}
