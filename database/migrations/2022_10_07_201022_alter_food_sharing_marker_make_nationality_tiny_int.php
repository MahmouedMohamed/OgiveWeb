<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFoodSharingMarkerMakeNationalityTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->dropColumn('nationality');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->tinyInteger('nationality')->after('collected_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->dropColumn('nationality');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->integer('nationality')->after('collected_at');
        });
    }
}
