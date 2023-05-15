<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBansAddOwnerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bans', function (Blueprint $table) {
            $table->dropForeign('user_bans_banned_user_foreign');
            $table->dropColumn('banned_user');
        });
        Schema::table('user_bans', function (Blueprint $table) {
            $table->string('banned_id')->after('id');
            $table->tinyInteger('banned_type')->after('banned_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bans', function (Blueprint $table) {
            $table->dropColumn('banned_id');
            $table->dropColumn('banned_type');
        });
        Schema::table('user_bans', function (Blueprint $table) {
            $table->string('banned_user')->after('id');
            $table->foreign('banned_user')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
