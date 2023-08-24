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
            $table->uuid('id');
            $table->primary('id');
            $table->string('giver_id')->nullable();
            $table->string('needy_id');
            $table->double('amount');
            $table->text('preferred_section');
            $table->text('address');
            $table->string('phone_number')->nullable();
            $table->dateTime('start_collect_date');
            $table->dateTime('end_collect_date');
            $table->dateTime('selected_date')->nullable();
            $table->boolean('collected')->default(0);
            $table->timestamps();
            $table->foreign('giver_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('offline_transactions');
    }
}
