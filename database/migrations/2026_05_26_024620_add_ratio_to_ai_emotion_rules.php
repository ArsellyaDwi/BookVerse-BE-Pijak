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
        Schema::table('ai_emotion_rules', function (Blueprint $table) {
            $table->double('suggested_match_ratio')->default(80);
            $table->text('matched_output_emotions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_emotion_rules', function (Blueprint $table) {
            $table->dropColumn('suggested_match_ratio');
            $table->dropColumn('matched_output_emotions');
        });
    }
};
