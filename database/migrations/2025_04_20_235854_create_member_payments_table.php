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
        Schema::create('member_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('membership_id');
            $table->integer('gym_id');

            $table->enum('payment_mode', ['cash','phone pay','google pay','other','system'])->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->decimal('after_discount_amount', 10, 2)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->decimal('original_plan_amount', 10, 2)->nullable();
            $table->enum('payment_type', ['admission','Due Payment','renewal','Plan Change','system'])->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_payments');
    }
};
