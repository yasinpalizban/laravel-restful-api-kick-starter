<?php

namespace Modules\Auth\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  CreateUserTable extends Migration
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
            $table->string('username')->unique();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('reset_token');
            $table->dateTime('reset_at');
            $table->dateTime('reset_expires');
            $table->string('active_token');
            $table->boolean('active');
            $table->date('active_expires');
            $table->boolean('status');
            $table->string('status_message');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image');
            $table->set('gender', ['male', 'female']);
            $table->date('birthday');
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->string('title');
            $table->text('bio');

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

;
