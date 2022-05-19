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
            $table->uuid('id');
            $table->primary('id');
            $table->string('created_by');
            $table->string('person_name');
            $table->date('birth_date');
            $table->date('death_date');
            $table->string('brief', 300);
            $table->text('life_story');
            $table->string('image');
            $table->string('nationality');
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
        Schema::dropIfExists('memories');
    }
}
