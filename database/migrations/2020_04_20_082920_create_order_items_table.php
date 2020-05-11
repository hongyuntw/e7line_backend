<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
//            $table->unsignedInteger('product_id');
//            $table->unsignedInteger('product_detail_id');
            $table->unsignedInteger('product_relation_id');
            $table->string('spec_name')->nullable();

            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->tinyInteger('status')->default(0);
            $table->timestamp('create_date')->nullable();
            $table->timestamp('update_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
