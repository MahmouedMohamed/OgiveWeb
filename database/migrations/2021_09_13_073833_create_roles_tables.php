<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ability_role', function (Blueprint $table) {  //for each ability, it has roles
            $table->primary(['role_id', 'ability_id']);
            $table->unique(['role_id', 'ability_id']);
            $table->string('role_id');
            $table->string('ability_id');
            $table->timestamps();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('ability_id')
                ->references('id')
                ->on('abilities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('role_user', function (Blueprint $table) {  //each user has a role
            $table->primary(['user_id', 'role_id']);
            $table->unique(['user_id', 'role_id']);
            $table->string('user_id');
            $table->string('role_id');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('roles');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('ability_role');
        Schema::dropIfExists('role_user');
    }
}
