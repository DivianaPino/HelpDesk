<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('username')->unique()->nullable();
            $table->string('cedula',11)->unique()->nullable();
            $table->string('ficha')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('email_uneg')->unique()->nullable();
            $table->string('password');
            $table->string('telegram_id')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->integer('login_failures')->default(0);
            $table->dateTime('last_failed_login_at')->nullable();
            $table->enum('status_password', ['ACTIVE','RESET','BLOCKED'])->default('ACTIVE');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            // $table->foreignId('current_team_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid()->unique()->nullable();



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
};
