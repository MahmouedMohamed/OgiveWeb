<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNeediesMakeTypeTinyInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('needies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('needies', function (Blueprint $table) {
            $table->tinyInteger('type')->after('severity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('needies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('needies', function (Blueprint $table) {
            $table->string('type')->after('severity');
        });
    }
}
