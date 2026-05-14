<?php

use App\Models\ReportLine;
use App\Models\Target;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->foreignId('report_line_id')->nullable()->after('id')->constrained('report_lines')->cascadeOnDelete();
        });

        $pairs = DB::table('targets')
            ->select('rode_id', 'sr_id')
            ->whereNotNull('rode_id')
            ->whereNotNull('sr_id')
            ->distinct()
            ->get();

        foreach ($pairs as $pair) {
            $lineId = DB::table('report_lines')->insertGetId([
                'rode_id' => $pair->rode_id,
                'sr_id' => $pair->sr_id,
                'position' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('targets')
                ->where('rode_id', $pair->rode_id)
                ->where('sr_id', $pair->sr_id)
                ->whereNull('report_line_id')
                ->update(['report_line_id' => $lineId]);
        }

        while (Target::query()->whereNull('report_line_id')->exists()) {
            $t = Target::query()->whereNull('report_line_id')->orderBy('id')->first();
            $line = ReportLine::query()->create([
                'rode_id' => $t->rode_id,
                'sr_id' => $t->sr_id,
                'position' => (int) (ReportLine::query()->max('position') ?? 0) + 1,
            ]);
            $t->update(['report_line_id' => $line->id]);
        }

        $p = 0;
        foreach (ReportLine::query()->orderBy('position')->orderBy('id')->get() as $line) {
            $line->update(['position' => $p++]);
        }
    }

    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropForeign(['report_line_id']);
            $table->dropColumn('report_line_id');
        });
    }
};
