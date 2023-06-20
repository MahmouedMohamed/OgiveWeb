<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionsAddFulfilledByAutoDonationFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_transactions', function (Blueprint $table) {
            $table->boolean('fulfilled_by_auto_donation')->after('remaining')->default(0);
        });
        Schema::table('offline_transactions', function (Blueprint $table) {
            $table->boolean('fulfilled_by_auto_donation')->after('collected')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
