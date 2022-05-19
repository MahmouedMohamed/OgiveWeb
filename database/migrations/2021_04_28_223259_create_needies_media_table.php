<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeediesMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('needies_media', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('needy_id');
            $table->string('path');
            $table->boolean('before')->default(1);
            $table->timestamps();
            $table->foreign('needy_id')
                ->references('id')
                ->on('needies')
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
        Schema::dropIfExists('needies_media');
    }
}
