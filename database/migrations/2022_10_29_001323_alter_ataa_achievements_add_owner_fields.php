<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAtaaAchievementsAddOwnerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ataa_achievements', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('ataa_achievements', function (Blueprint $table) {
            $table->string('owner_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ataa_achievements', function (Blueprint $table) {
            $table->dropColumn('owner_id');
        });
        Schema::table('ataa_achievements', function (Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
