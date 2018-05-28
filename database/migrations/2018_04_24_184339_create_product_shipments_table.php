<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->string('title');
            $table->string('supplier')->default('Únknown');
            $table->float('price', 10, 2)->default(0.0);
            $table->float('store_unit', 10, 2)->default(0.0)->comment('A unit amount of product for supply or shipment');
            $table->bigInteger('stored_unit_total')->default(0);
            $table->enum('store_unit_type', [ 'QUANTITY', 'WEIGHT' ])->default('WEIGHT');
            $table->enum('status', [ 'STORE_ADDED', 'STORE_REMOVED', 'SALES_ADDED', 'SALES_REMOVED' ])->default('STORE_ADDED');
            $table->enum('direction', [ 'CHECKED_IN', 'CHECKED_OUT' ])->default('CHECKED_IN');
            $table->string('tag')->default('unknown');
            $table->timestamps();
            $table->index(['id', 'product_id', 'created_at'], 'product_shipments_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_shipments');
    }
}
