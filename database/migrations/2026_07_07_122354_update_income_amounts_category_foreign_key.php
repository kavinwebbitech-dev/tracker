<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // FK already dropped in a previous failed migration attempt — skip dropForeign

        // Null out any category_id that isn't a valid id in income_categories
        DB::statement('
            UPDATE income_amounts
            SET category_id = NULL
            WHERE category_id IS NOT NULL
            AND category_id NOT IN (SELECT id FROM income_categories)
        ');

        // Add the new FK pointing to income_categories
        Schema::table('income_amounts', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')->on('income_categories')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('income_amounts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('income_amounts', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')->on('services')
                  ->onDelete('set null');
        });
    }
};