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
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company')->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('residence_address')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();
            $table->string('instagram')->nullable();
            $table->string('avatar_path')->default('uploads/avatar-m.png');
            $table->string('online_status', 10)->default('success');
            $table->tinyInteger('admin')->default(0);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
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
