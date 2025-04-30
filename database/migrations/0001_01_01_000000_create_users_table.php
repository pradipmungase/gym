<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gym_name');
            $table->string('owner_name');
            $table->string('mobile');
            $table->string('password');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('qr_code');
            $table->string('gym_address')->nullable();
            $table->string('email')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
