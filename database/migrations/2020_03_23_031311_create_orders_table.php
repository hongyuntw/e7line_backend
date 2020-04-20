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
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('concat_person_id');
            $table->string('note',100)->nullable();
//            狀態待訂
//            0是預設
            $table->tinyInteger('status')->default(0);
//            可能會有多個，所以不用做validate
            $table->string('tax_id')->nullable();
            $table->string('ship_to');

//            這邊代表下訂單的人，不一定是福委
            $table->string('email',50)->nullable();
            $table->string('phone_number',50)->nullable();


            $table->decimal('discount')->default(0);
            $table->decimal('amount');


//            為了啥購買的
            $table->unsignedInteger('welfare_id');

            //         最晚到貨時間
//            收件日期
            $table->timestamp('receive_date')->nullable();
            $table->timestamp('latest_arrival_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
