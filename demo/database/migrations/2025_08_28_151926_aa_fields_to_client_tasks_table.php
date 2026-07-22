<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_tasks', function (Blueprint $table) {
            $table->string('cost_type')->nullable()->after('user_type');
            $table->integer('paid_amount')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_tasks', function (Blueprint $table) {
            $table->dropColumn(['cost_type','paid_amount']);
        });
    }
};
