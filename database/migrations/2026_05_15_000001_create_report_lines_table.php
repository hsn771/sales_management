<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rode_id')->nullable()->constrained('rodes')->nullOnDelete();
            $table->foreignId('sr_id')->nullable()->constrained('s_r_s')->nullOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_lines');
    }
};
