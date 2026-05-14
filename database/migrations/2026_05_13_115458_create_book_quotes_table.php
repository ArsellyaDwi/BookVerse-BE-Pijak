<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->text('quote');
            $table->string('mood', 50)->nullable();
            $table->integer('page_number')->nullable();
            $table->timestamps();
            
            $table->index('mood');
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_quotes');
    }
};