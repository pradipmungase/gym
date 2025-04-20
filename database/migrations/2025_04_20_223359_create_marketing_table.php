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
            Schema::create('marketing', function (Blueprint $table) {
                $table->id();
                $table->string('whatsappNumber')->nullable();
                $table->string('name')->nullable();
                $table->string('pincode')->nullable();
                $table->string('address')->nullable();
                $table->string('owner_name')->nullable();
                $table->string('email')->nullable();
                $table->string('mobile')->nullable();
                $table->dateTime('datetime1')->nullable();
                $table->dateTime('datetime2')->nullable();
                $table->dateTime('datetime3')->nullable();
                $table->dateTime('datetime4')->nullable();
                $table->dateTime('datetime5')->nullable();
                $table->dateTime('datetime6')->nullable();
                $table->dateTime('datetime7')->nullable();
                $table->dateTime('datetime8')->nullable();
                $table->dateTime('datetime9')->nullable();
                $table->dateTime('datetime10')->nullable();
                $table->dateTime('is_send1')->nullable();
                $table->dateTime('is_send2')->nullable();
                $table->dateTime('is_send3')->nullable();
                $table->dateTime('is_send4')->nullable();
                $table->dateTime('is_send5')->nullable();
                $table->dateTime('is_send6')->nullable();
                $table->dateTime('is_send7')->nullable();
                $table->dateTime('is_send8')->nullable();
                $table->dateTime('is_send9')->nullable();
                $table->dateTime('is_send10')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing');
    }
};
