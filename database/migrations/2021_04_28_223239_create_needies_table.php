<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeediesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('needies', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->double('age')->nullable();
            $table->integer('severity')->default(1);
            $table->string('type');
            $table->text('details');
            $table->double('need')->default(0);
            $table->double('collected')->default(0);
            $table->string('address');
            $table->boolean('satisfied')->default(0);
            $table->boolean('approved')->default(0);
            $table->string('created_by');
            $table->string('url')->nullable();
            //ToDo:Introduce Nationality to Ahed
            // $table->string('nationality');
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
        Schema::dropIfExists('needies');
    }
}
