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
        Schema::create('request_feature', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gym_id')->index();
            $table->string('feature_name');
            $table->text('description')->nullable();
            $table->enum('status', ['requested', 'approved', 'rejected'])->default('requested')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_feature');
    }
};
