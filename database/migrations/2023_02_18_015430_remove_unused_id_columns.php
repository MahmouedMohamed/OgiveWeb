<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_ataa_acquired_badges', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('user_ataa_acquired_prizes', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_ataa_acquired_badges', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
        });
        Schema::table('user_ataa_acquired_prizes', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
        });
    }
}
