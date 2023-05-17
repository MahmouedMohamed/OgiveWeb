<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersMakeGenderTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('gender')->after('email');
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
            $table->dropColumn('gender');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->default('female')->after('email');
        });
    }
}
