<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domain_server', function (Blueprint $table) {
            $table->id();
            $table->string('fld_domain_server_name');
            $table->tinyInteger('fld_status')->default(1); // 1 = Active, 0 = In Active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_server');
    }
};