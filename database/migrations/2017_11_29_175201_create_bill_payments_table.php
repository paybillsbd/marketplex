<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('sale_transaction_id')->unsigned();

            $table->enum('method', [
                'by_cash_hand2hand',
                'by_cash_cheque_deposit',
                'by_cash_electronic_trans',
                'by_cheque_hand2hand' ])->default('by_cash_hand2hand');

            $table->float('amount', 10, 2)->default(0.0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'sale_transaction_id', 'created_at'], 'bill_payments_index');
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
        Schema::dropIfExists('bill_payments');
    }
}
