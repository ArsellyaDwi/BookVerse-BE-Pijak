<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('genres', function (Blueprint $table) {
            if (!Schema::hasColumn('genres', 'extroversion')) {
                $table->float('extroversion')->default(50);
            }
            if (!Schema::hasColumn('genres', 'neuroticism')) {
                $table->float('neuroticism')->default(50);
            }
            if (!Schema::hasColumn('genres', 'agreeableness')) {
                $table->float('agreeableness')->default(50);
            }
            if (!Schema::hasColumn('genres', 'conscientiousness')) {
                $table->float('conscientiousness')->default(50);
            }
            if (!Schema::hasColumn('genres', 'openness')) {
                $table->float('openness')->default(50);
            }
        });
    }

    public function down(): void
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropColumn([
                'extroversion',
                'neuroticism',
                'agreeableness',
                'conscientiousness',
                'openness'
            ]);
        });
    }
};