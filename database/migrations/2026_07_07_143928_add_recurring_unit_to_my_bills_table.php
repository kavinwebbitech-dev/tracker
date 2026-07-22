<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('my_bills', function (Blueprint $table) {
            $table->string('recurring_unit')->nullable()->after('recurring_type'); // none, day, week, month
        });
    }

    public function down()
    {
        Schema::table('my_bills', function (Blueprint $table) {
            $table->dropColumn('recurring_unit');
        });
    }
};