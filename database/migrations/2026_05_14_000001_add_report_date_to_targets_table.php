<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->date('report_date')->nullable()->after('id');
        });

        DB::table('targets')->whereNull('report_date')->update([
            'report_date' => DB::raw('DATE(created_at)'),
        ]);

        DB::table('targets')->whereNull('report_date')->update([
            'report_date' => now()->toDateString(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropColumn('report_date');
        });
    }
};
