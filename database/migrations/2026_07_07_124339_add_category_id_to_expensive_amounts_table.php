<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expensive_amounts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });

        // Migrate existing text category values into expense_categories,
        // then point category_id at the matching row
        $rows = DB::table('expensive_amounts')->whereNotNull('category')->where('category', '!=', '')->get(['id', 'category']);

        foreach ($rows as $row) {
            $categoryName = trim($row->category);

            $category = DB::table('expense_categories')->where('name', $categoryName)->first();

            if (!$category) {
                $categoryId = DB::table('expense_categories')->insertGetId([
                    'name'       => $categoryName,
                    'status'     => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $category->id;
            }

            DB::table('expensive_amounts')->where('id', $row->id)->update(['category_id' => $categoryId]);
        }

        Schema::table('expensive_amounts', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')->on('expense_categories')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('expensive_amounts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};