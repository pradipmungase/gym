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
            $table->bigIncrements('id');
            $table->integer('gym_id')->index();
            $table->string('title');
            $table->string('description');
            $table->date('date');
            $table->time('time');
            $table->string('type')->index();
            $table->integer('member_id')->nullable()->index();
            $table->integer('trainer_id')->nullable()->index();
            $table->enum('status', ['read', 'unread'])->index();
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
