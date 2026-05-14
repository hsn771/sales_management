<?php

namespace App\Http\Controllers;

use App\Models\ReportDateSetting;
use App\Models\ReportLine;
use App\Models\Rode;
use App\Models\SR;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        if (! Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $selectedDate = $this->resolveReportDate($request->query('date'));

        $reportLines = ReportLine::query()
            ->orderBy('position')
            ->orderBy('id')
            ->with(['rode', 'sr'])
            ->get();

        $dayTargets = Target::query()
            ->whereDate('report_date', $selectedDate)
            ->orderBy('id')
            ->get();

        $targetsByLineId = $dayTargets->whereNotNull('report_line_id')->keyBy('report_line_id');
        $legacyTargets = $dayTargets->whereNull('report_line_id')->values();

        $allRodes = Rode::all();
        $allNames = SR::all();

        $daySettingsRow = ReportDateSetting::query()
            ->whereDate('report_date', $selectedDate)
            ->first();
        $percentBaseForDay = $daySettingsRow && $daySettingsRow->percent_base !== null
            ? (float) $daySettingsRow->percent_base
            : null;
        $aprPercentForDay = $daySettingsRow && $daySettingsRow->apr_percent !== null
            ? (float) $daySettingsRow->apr_percent
            : null;

        return view('daily-report.index', compact(
            'reportLines',
            'targetsByLineId',
            'legacyTargets',
            'dayTargets',
            'allRodes',
            'allNames',
            'selectedDate',
            'percentBaseForDay',
            'aprPercentForDay'
        ));
    }

    private function resolveReportDate(?string $date): string
    {
        if ($date === null || $date === '') {
            return now()->format('Y-m-d');
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable) {
            return now()->format('Y-m-d');
        }
    }
}
