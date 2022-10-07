<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFoodSharingMarkerMakePriorityTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->tinyInteger('priority')->after('quantity');
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
            $table->dropColumn('priority');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->integer('priority')->after('quantity');
        });
    }
}
