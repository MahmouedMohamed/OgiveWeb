<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodSharingMarkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_sharing_markers', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('type');
            $table->text('description');
            $table->integer('quantity');
            $table->integer('priority');
            $table->boolean('collected')->default(0);
            $table->boolean('existed')->nullable();
            $table->dateTime('collected_at')->nullable();
            $table->string('nationality');
            $table->timestamps();
            $table->foreign('user_id')
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
        Schema::dropIfExists('food_sharing_markers');
    }
}
