<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAtaaAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ataa_achievements', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->uuid('id');
            $table->primary('id');
            $table->integer('markers_collected');
            $table->integer('markers_posted');
            $table->boolean('freezed')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ataa_achievements');
    }
}
