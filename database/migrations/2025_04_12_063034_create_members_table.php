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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('qr_code_path');
            $table->date('joining_date');
            $table->string('status');
            $table->string('image');
            $table->string('address');
            $table->string('gender');
            $table->integer('age');
            $table->integer('plan_id');
            $table->decimal('discount', 10, 2);
            $table->integer('discount_type');
            $table->decimal('discount_amount', 10, 2);
            $table->integer('gym_id');
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
