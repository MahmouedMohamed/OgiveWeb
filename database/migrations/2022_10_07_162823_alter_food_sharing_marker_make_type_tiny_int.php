<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFoodSharingMarkerMakeTypeTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->tinyInteger('type')->after('longitude');
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
            $table->dropColumn('type');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->string('type')->after('longitude');
        });
    }
}
