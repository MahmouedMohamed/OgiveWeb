<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeCatcherTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_catcher_tracking', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('tracker_id');
            $table->string('tracked_id');
            $table->double('point_x');
            $table->double('point_y');
            $table->double('range_in_meter');
            $table->timestamps();
            $table->foreign('tracker_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('tracked_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('time_catcher_tracking');
    }
}
