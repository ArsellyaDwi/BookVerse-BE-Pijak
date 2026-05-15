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
        Schema::table('ai_recommendation_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->bigInteger('user_id')->nullable();
        });

        Schema::dropIfExists('ai_training_logs');
        Schema::dropIfExists('ai_emotion_datasets');
        Schema::dropIfExists('ai_recommendation_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
