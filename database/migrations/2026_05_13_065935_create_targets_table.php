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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->string('rode');
            $table->string('name');
            $table->decimal('target', 12, 2)->default(0);
            $table->decimal('target_percent', 8, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('over', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->decimal('daily_cost', 12, 2)->default(0);
            $table->decimal('ach', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
