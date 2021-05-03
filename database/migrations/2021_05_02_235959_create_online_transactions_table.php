<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('giver');
            $table->unsignedBigInteger('needy');
            $table->double('amount');
            $table->double('remaining');
            $table->timestamps();
            $table->foreign('giver')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('online_transactions');
    }
}
