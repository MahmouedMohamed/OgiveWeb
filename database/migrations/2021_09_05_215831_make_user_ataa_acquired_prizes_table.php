<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserAtaaAcquiredPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ataa_acquired_prizes', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->string('prize_id');
            $table->unique(['user_id', 'prize_id']);
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('prize_id')
                ->references('id')
                ->on('ataa_prizes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ataa_acquired_prizes');
    }
}
