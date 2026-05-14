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
        Schema::table('targets', function (Blueprint $table) {
            $table->foreignId('rode_id')->nullable()->constrained('rodes')->onDelete('set null');
            $table->foreignId('sr_id')->nullable()->constrained('s_r_s')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropForeign(['rode_id']);
            $table->dropForeign(['sr_id']);
            $table->dropColumn(['rode_id', 'sr_id']);
        });
    }
};
