<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('task_staff', function (Blueprint $table) {
            $table->decimal('assigned_designer_hours', 8, 2)->default(0)->after('assigned_testing_hours');
        });

        Schema::table('project_list', function (Blueprint $table) {
            $table->decimal('designer_hours', 8, 2)->default(0)->after('testing_hours');
        });
    }

    public function down()
    {
        Schema::table('task_staff', function (Blueprint $table) {
            $table->dropColumn('assigned_designer_hours');
        });

        Schema::table('project_list', function (Blueprint $table) {
            $table->dropColumn('designer_hours');
        });
    }
};