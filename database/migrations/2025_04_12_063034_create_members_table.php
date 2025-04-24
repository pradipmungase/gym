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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('gym_id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile');
            $table->string('qr_code_path');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->integer('age')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('image')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
