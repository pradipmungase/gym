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
            $table->id();
            $table->string('gym_name')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('password');
            $table->string('type')->default('user');
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
