<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2);
            $table->integer('books_per_multiplier')->default(4);
            $table->boolean('is_active')->default(true);
            $table->integer('estimated_days_min')->nullable();
            $table->integer('estimated_days_max')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('delivery_method_id')->nullable()->after('delivery_address_id')->constrained()->nullOnDelete();
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['delivery_method_id']);
            $table->dropColumn(['delivery_method_id', 'shipping_cost']);
        });
        Schema::dropIfExists('delivery_methods');
    }
};
