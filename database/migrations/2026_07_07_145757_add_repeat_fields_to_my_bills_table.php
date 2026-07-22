<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('my_bills', function (Blueprint $table) {
            $table->integer('repeat_count')->nullable();
            $table->enum('repeat_type', ['day', 'week', 'month', 'year'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('my_bills', function (Blueprint $table) {
            $table->dropColumn(['repeat_count', 'repeat_type']);
        });
    }
};