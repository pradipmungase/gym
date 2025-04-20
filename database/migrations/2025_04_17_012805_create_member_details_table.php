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
        Schema::create('member_details', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('gym_id');
            $table->integer('plan_id');
            $table->integer('trainer_id');
            $table->date('joining_date');
            $table->date('expiry_date');
            $table->string('batch');
            $table->string('paymentMode')->nullable();
            $table->decimal('admission_fee', 10, 2);
            $table->string('discount_type');
            $table->integer('discount_inpute')->nullable();
            $table->decimal('plan_price', 10, 2);
            $table->decimal('after_discount_price', 10, 2);
            $table->decimal('due_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_details');
    }
};
