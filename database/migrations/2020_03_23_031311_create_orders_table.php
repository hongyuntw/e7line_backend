<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
//            客戶名字
            $table->unsignedInteger('customer_id');
            $table->string('tax_id');
//            業務名字
            $table->unsignedInteger('user_id');
//            訂單編號
            $table->string('code');
//            這筆訂單總共價錢，折價等
            $table->decimal('price');
            $table->decimal('discount');
            $table->decimal('total_price');
//            採購目的
            $table->unsignedInteger('welfare_id');
            $table->unsignedInteger('is_deleted')->default(0);
            $table->timestamp('create_date',0)->nullable();
            $table->timestamp('update_date',0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
