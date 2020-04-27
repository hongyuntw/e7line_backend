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
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('other_customer_name')->nullable();
            $table->unsignedInteger('business_concat_person_id')->nullable();
            $table->string('other_concat_person_name')->nullable();
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


            $table->decimal('amount')->default(0);

//            付款資訊
//            共有三種付款方式
            $table->tinyInteger('payment_method')->default(0);
            $table->string('payment_date')->nullable();
//            匯款才會有
            $table->string('swift_code')->nullable();
            $table->string('payment_account_name')->nullable();
            $table->string('payment_account_num')->nullable();


//            e7line下單帳號,姓名
            $table->string('e7line_account');
            $table->string('e7line_name');


//            為了啥購買的
            $table->unsignedInteger('welfare_id');

//            收件日期,最晚到貨時間
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
