<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWelfareStatusLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_status', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
//            下面這邊紀錄福利目的
            $table->unsignedInteger('welfare_id');
            $table->string('welfare_code',10);
            $table->string('welfare_name',10);
            $table->tinyInteger('track_status')->default(0)->nullable();
//            $table->unsignedInteger('welfare_type_id');
//            $table->unsignedInteger('welfare_company_id');
//            $table->unsignedInteger('welfare_detail_id');
            $table->string('budget')->default('0');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('welfare_status');
    }
}
