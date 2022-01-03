<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memories', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->unsignedBigInteger('createdBy');
            $table->string('personName');
            $table->date('birthDate');
            $table->date('deathDate');
	    $table->string('brief',300);
            $table->text('lifeStory');
            $table->string('image');
            $table->string('nationality');
            $table->timestamps();
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memories');
    }
}
