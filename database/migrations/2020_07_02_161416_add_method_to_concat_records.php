<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMethodToConcatRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concat_records', function (Blueprint $table) {
            //
            $table->string('method')->nullable()->default('無');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concat_records', function (Blueprint $table) {
            //
            $table->dropColumn('method');
        });
    }
}
