<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_date_settings', function (Blueprint $table) {
            $table->decimal('apr_percent', 10, 4)->nullable()->after('percent_base');
        });
    }

    public function down(): void
    {
        Schema::table('report_date_settings', function (Blueprint $table) {
            $table->dropColumn('apr_percent');
        });
    }
};
