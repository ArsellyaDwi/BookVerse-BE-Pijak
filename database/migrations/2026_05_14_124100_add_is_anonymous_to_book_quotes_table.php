<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('book_quotes', function (Blueprint $table) {
            $table->boolean('is_anonymous')->default(false)->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('book_quotes', function (Blueprint $table) {
            $table->dropColumn('is_anonymous');
        });
    }
};