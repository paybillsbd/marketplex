<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use MarketPlex\Product;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('market_product_id');
            $table->boolean('is_public')->default(false);
            $table->string('title', 200)->comment('Stored product title - can be RENAMED by store owner');
            $table->float('mrp', 10, 2)->default(0.0);
            $table->float('discount', 10, 2)->default(0.0);
            
            // $table->json('special_specs')->nullable()->comment('JSON serialization of product specifications defined by store product specifications.');
            
            // NOTE: Temoporary field for specs it will be replaced with $table->json() if postgresql is available to server
            $table->longText('special_specs')->nullable()->comment('JSON serialization of product specifications defined by store product specifications.');
            
            $table->bigInteger('available_quantity')->default(0);
            $table->integer('return_time_limit')->default(0);
            $table->longText('description')->nullable();
            // $table->enum('type', [ 'PHYSICAL', 'DOWNLOADABLE' ])->default('PHYSICAL');
            $table->enum('status', Product::STATUS_FLOWS)->default('ON_APPROVAL');
            $table->timestamps();  
            $table->index(['id', 'user_id', 'store_id', 'market_product_id', 'created_at'], 'products_index');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop('products');
        Schema::enableForeignKeyConstraints();
    }
}
