<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSenaoOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senao_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seq_id');
            $table->string('no');
            $table->string('order_id')->nullable();
            $table->timestamp('pay_date')->nullable();
            $table->string('senao_isbn');
            $table->string('supplier_isbn')->nullable();
            $table->string('product_name');
            $table->string('color')->nullable();
            $table->string('attribute_name')->nullable();
            $table->string('attribute_value')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');
            $table->string('receiver');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->nullable();
            $table->string('shipment_code')->nullable();
            $table->string('shipment_company')->nullable();
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('senao_orders');
    }
}
