<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained('books')->onDelete('set null');
            $table->text('quote');
            $table->string('mood', 50)->nullable();
            $table->integer('page_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('author_name')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            
            $table->index('mood');
            $table->index('user_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_quotes');
    }
};