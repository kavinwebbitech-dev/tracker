<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('income_amounts', function (Blueprint $table) {
            $table->unsignedBigInteger('my_bill_id')->nullable()->after('id');
        });

        Schema::table('expensive_amounts', function (Blueprint $table) {
            $table->unsignedBigInteger('my_bill_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('income_amounts', function (Blueprint $table) {
            $table->dropColumn('my_bill_id');
        });

        Schema::table('expensive_amounts', function (Blueprint $table) {
            $table->dropColumn('my_bill_id');
        });
    }
};