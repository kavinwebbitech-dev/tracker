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
        Schema::create('task_details', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');
            $table->string('staff_id');
            $table->string('date_type')->nullable();
            $table->string('start_date');
            $table->string('end_data');
            $table->string('user_comments')->nullable();
            $table->string('admin_comments')->nullable();
            $table->string('status');
            $table->string('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_details');
    }
};
