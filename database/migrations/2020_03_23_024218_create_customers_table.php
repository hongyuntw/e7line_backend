<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('user_id')->default(1);
            $table->string('name',50);

            $table->string('tax_id',8)->nullable();
//            $table->unsignedInteger('capital')->nullable();
            $table->string('capital',25)->nullable();
            $table->unsignedInteger('scales')->nullable();
            $table->string('phone_number',20)->nullable();
            $table->string('fax_number',20)->nullable();
            $table->string('address',50)->nullable();
            $table->string('city',10);
            $table->string('area',10);


            $table->tinyInteger('active_status')->default(0);
            $table->timestamp('active_time',0)->nullable();

            $table->boolean('already_set_sales')->default(false);

            $table->boolean('is_deleted')->default(false);
            $table->timestamp('delete_time',0)->nullable();

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
        Schema::dropIfExists('customers');
    }
}
