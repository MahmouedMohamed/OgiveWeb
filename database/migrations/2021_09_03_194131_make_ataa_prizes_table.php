<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeAtaaPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ataa_prizes', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->string('arabic_name');
            $table->string('image')->nullable(); //if money would be nullable
            $table->integer('required_markers_collected')->default(0);
            $table->integer('required_markers_posted')->default(0);
            $table->date('from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('to')->nullable(); //Can be null cause it can be forever
            $table->integer('level');
            $table->boolean('active')->default(1);
            //nullable -> Can Be auto created
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('ataa_prizes');
    }
}
