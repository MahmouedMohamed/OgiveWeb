<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('giver')->nullable();
            $table->unsignedBigInteger('needy');
            $table->double('amount');
            $table->text('preferredSection');
            $table->text('address');
            $table->string('phoneNumber')->nullable();
            $table->dateTime('startCollectDate');
            $table->dateTime('endCollectDate');
            $table->dateTime('selectedDate')->nullable();
            $table->boolean('collected')->default(0);
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
        Schema::dropIfExists('offline_transactions');
    }
}
