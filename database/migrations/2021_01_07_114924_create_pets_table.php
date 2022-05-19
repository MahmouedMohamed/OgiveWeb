<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->unsignedBigInteger('createdBy');
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->integer('age');
            $table->string('sex');
            $table->string('type');
            $table->text('notes')->nullable();
            $table->string('image');
            $table->string('nationality');
            $table->boolean('availableForAdoption')->default(1);
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
        Schema::dropIfExists('pets');
    }
}
