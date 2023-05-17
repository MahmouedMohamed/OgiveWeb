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
            $table->uuid('id');
            $table->primary('id');
            $table->string('created_by');
            $table->string('name');
            $table->integer('age');
            $table->string('sex');
            $table->string('type');
            $table->string('breed');
            $table->text('notes')->nullable();
            $table->string('image');
            $table->string('nationality');
            $table->boolean('available_for_adoption')->default(1);
            $table->timestamps();
            $table->foreign('created_by')
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
        Schema::dropIfExists('pets');
    }
}
