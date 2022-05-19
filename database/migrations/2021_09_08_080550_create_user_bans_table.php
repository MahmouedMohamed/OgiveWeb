<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bans', function (Blueprint $table) {
            $table->unsignedBigInteger('banned_user');
            $table->uuid('id');
            $table->primary('id');
            $table->string('tag');
            $table->boolean('active')->default(1);
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable(); //null -> Infinity
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('banned_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bans');
    }
}
