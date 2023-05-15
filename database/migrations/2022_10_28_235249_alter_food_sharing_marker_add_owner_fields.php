<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFoodSharingMarkerAddOwnerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->dropForeign('food_sharing_markers_user_id_foreign');
            $table->dropColumn('user_id');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->string('owner_id')->after('id');
            $table->tinyInteger('owner_type')->after('owner_id');
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
            $table->dropColumn('owner_id');
            $table->dropColumn('owner_type');
        });
        Schema::table('food_sharing_markers', function (Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
