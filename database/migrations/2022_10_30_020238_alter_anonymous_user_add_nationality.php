<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAnonymousUserAddNationality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anonymous_users', function (Blueprint $table) {
            $table->tinyInteger('nationality')->after('device_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anonymous_users', function (Blueprint $table) {
            $table->dropColumn('nationality');
        });
    }
}
