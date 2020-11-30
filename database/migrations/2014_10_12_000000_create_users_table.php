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
            // columns
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('email')->unique();
            $table->string('role', 50);
            $table->tinyInteger('status');
            $table->string('password');
            $table->rememberToken();
            $table->ipAddress('last_login_from')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            // indexes
            $table->index('role');
            $table->index('status');
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
