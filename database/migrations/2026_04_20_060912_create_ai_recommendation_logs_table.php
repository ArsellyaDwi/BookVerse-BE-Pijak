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
        Schema::create('ai_recommendation_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->text('input');
            $table->text('result')->nullable();
            $table->dateTime('create_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendation_logs');
    }
};
