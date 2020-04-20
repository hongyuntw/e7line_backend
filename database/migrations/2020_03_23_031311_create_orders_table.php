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
            $table->timestamp('latest_arrival_date')->nullable();
//            這邊代表下訂單的人，不一定是福委
            $table->string('email');
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
