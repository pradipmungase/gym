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
        Schema::create('member_registration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gym_id')->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile_number');
            $table->string('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('image')->nullable();
            $table->date('joining_date');
            $table->date('end_date');
            $table->string('batch');
            $table->integer('trainer_id')->nullable()->index();
            $table->integer('plan_id')->index();
            $table->decimal('plan_price', 10, 2);
            $table->decimal('final_price_after_discount', 10, 2)->nullable();
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->enum('discount_type', ['percentage', 'flat'])->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('admission_fee', 10, 2)->nullable();
            $table->enum('payment_mode', ['cash','phone pay','google pay','other','system'])->nullable()->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_registration');
    }
};
