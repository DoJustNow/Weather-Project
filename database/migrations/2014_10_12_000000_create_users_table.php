<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            //SOCIALITE
            $table->string('github_id')->unique()->index()->nullable();
            $table->string('mailru_id')->unique()->index()->nullable();
            $table->string('google_id')->unique()->index()->nullable();
            $table->string('vkontakte_id')->unique()->index()->nullable();
            //Токен верификации
            $table->string('verify_token')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable();//
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('unconfirmed_email')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
