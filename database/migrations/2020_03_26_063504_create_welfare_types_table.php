<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWelfareTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20);
            $table->unsignedInteger('code');
            $table->unsignedInteger('welfare_status_id');
            $table->unsignedInteger('welfare_type_company_relation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_types');
    }
}
