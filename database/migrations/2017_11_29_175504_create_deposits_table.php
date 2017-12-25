<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('sale_transaction_id')->unsigned();
            $table->string('bank_title')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_branch')->nullable();
            $table->enum('method', [ 'bank', 'vault' ])->default('bank');
            $table->float('amount', 10, 2)->default(0.0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'sale_transaction_id', 'created_at'], 'deposits_index');
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
        Schema::dropIfExists('deposits');
    }
}
