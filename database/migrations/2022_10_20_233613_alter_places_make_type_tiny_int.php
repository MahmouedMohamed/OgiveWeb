<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPlacesMakeTypeTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->tinyInteger('type')->after('specialty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->integer('type')->after('specialty');
        });
    }
}
