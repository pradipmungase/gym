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
        Schema::create('whatsapp_mess_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->text('message');
            $table->string('image')->nullable();
            $table->string('type');
            $table->string('status');
            $table->text('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_mess_tracking');
    }
};
