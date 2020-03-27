<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWelfareTypeCompanyRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_type_company_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('welfare_company_id');
            $table->unsignedInteger('welfare_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_type_company_relations');
    }
}
