<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeAtaaPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ataa_prizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image')->nullable(); //if money would be nullable
            $table->integer('required_markers_collected')->default(0);
            $table->integer('required_markers_posted')->default(0);
            $table->dateTime('from')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('to')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('level');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ataa_prizes');
    }
}
