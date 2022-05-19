<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->uuid('id');
            $table->primary('id');
            $table->text('access_token')->unique();
            $table->text('scopes')->nullable();  //To Support Roles //2x Checks feature
            $table->string('appType');
            $table->string('accessType');
            $table->boolean('active')->default(1);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
}
