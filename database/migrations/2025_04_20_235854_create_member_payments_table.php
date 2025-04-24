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

            $table->enum('payment_mode', ['cash','phone Ppay','google pay','other'])->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('due_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->enum('payment_type', ['admission','due_payment','renewal','plan_change'])->nullable();

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
