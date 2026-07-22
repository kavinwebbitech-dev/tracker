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
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_id')->after('remember_token')->nullable();
            $table->string('user_type')->after('admin_id');
            $table->string('profile_picture')->after('user_type')->nullable();
            $table->string('points')->after('profile_picture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_id');
            $table->dropColumn('user_type');
            $table->dropColumn('profile_picture');
            $table->dropColumn('points');
        });
    }
};
