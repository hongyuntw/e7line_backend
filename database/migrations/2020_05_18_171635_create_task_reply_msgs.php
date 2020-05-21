<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskReplyMsgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_reply_msgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text')->nullable();
            $table->unsignedInteger('task_assignment_id');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('task_reply_msgs');
    }
}
