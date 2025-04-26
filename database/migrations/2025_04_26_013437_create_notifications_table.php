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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('gym_id');
            $table->string('title');
            $table->string('description');
            $table->date('date');
            $table->time('time');
            $table->enum('type', ['member', 'trainer','member','summary','membership_expiry']);
            $table->integer('member_id')->nullable();
            $table->integer('trainer_id')->nullable();
            $table->enum('status', ['read', 'unread']);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
