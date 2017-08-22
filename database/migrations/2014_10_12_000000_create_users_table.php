<?php

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
            $table->string('steamid')->unique();
            $table->string('tradeURL');
            $table->string('profileURL');
            $table->string('avatar');
            $table->string('username');
            $table->string('email')->unique();
            $table->integer('user_balance');
            $table->string('refcode');
            $table->string('refget');
            $table->integer('havecsgo');
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
        Schema::drop('users');
    }
}
