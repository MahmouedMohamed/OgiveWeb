<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersMakeNationalityTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nationality');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('nationality')->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nationality');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('nationality')->after('address');
        });
    }
}
