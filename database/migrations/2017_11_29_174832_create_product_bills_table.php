<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('sale_transaction_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'sale_transaction_id', 'created_at'], 'product_bills_index');
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
        Schema::dropIfExists('product_bills');
    }
}
