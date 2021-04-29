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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('needy');
            $table->string('path');
            $table->boolean('before')->default(1);
            $table->timestamps();
            $table->foreign('needy')->references('id')->on('needies')->onDelete('cascade');
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
