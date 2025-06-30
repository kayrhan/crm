<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('username')->nullable();
            $table->longText('password');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('org_id', '0');
            $table->string('first_name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('gsm')->nullable();
            $table->string('phone_no')->nullable();
            $table->smallInteger('get_email', '0');
            $table->integer('in_use', '0');
            $table->string('ip', '0');
            $table->timestamp('last_login', '0');
            $table->integer('add_by', '0');
            $table->string('add_ip', '0');
            $table->integer('update_by', '0');
            $table->string('update_ip', '0');
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
