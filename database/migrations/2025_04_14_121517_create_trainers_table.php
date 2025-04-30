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
        Schema::create('trainers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gym_id')->index();
            $table->string('name')->index();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('gender');
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->date('joining_date');
            $table->date('birth_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->integer('monthly_salary');
            $table->integer('daily_salary')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
