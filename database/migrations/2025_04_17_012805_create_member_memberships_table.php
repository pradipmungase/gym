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
        Schema::create('member_memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('member_id')->index();
            $table->integer('gym_id')->index();
            $table->integer('plan_id')->index();
            $table->integer('trainer_id')->nullable()->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('batch');
            $table->enum('discount_type', ['percentage', 'flat'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('plan_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2);
            $table->enum('status', ['active', 'expired', 'cancelled','changed','renew'])->default('active')->index();
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
