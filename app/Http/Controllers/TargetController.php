<?php

namespace App\Http\Controllers;

use App\Models\ReportDateSetting;
use App\Models\ReportLine;
use App\Models\Rode;
use App\Models\SR;
use App\Models\Target;
use App\Support\DeletionLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Session::get('logged_in')) {
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

        return view('targets.index', compact(
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        return view('targets.create');
    }

    public function createRode()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        return view('targets.create_rode');
    }

    public function createSR()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        return view('targets.create_sr');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'rode' => 'required|string',
            'name' => 'required|string',
            'target' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'report_date' => 'nullable|date',
        ]);

        $reportDate = $this->resolveReportDate($validated['report_date'] ?? null);

        // Auto-calculate Over
        $validated['over'] = $validated['balance'] > $validated['target'] 
            ? $validated['balance'] - $validated['target'] 
            : 0;
            
        // Auto-calculate ACH
        $validated['ach'] = $validated['target'] > 0 
            ? ($validated['balance'] / $validated['target']) * 100 
            : 0;

        $validated['report_date'] = $reportDate;

        // Use daily cost from an existing row for this report date when available
        $first = Target::query()->whereDate('report_date', $reportDate)->orderBy('id')->first();
        $validated['daily_cost'] = $first ? $first->daily_cost : 0;

        Target::create($validated);

        return redirect()->route('targets.index', ['date' => $reportDate])->with('success', 'Target created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Target $target)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        return view('targets.edit', compact('target'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Target $target)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'rode' => 'required|string',
            'name' => 'required|string',
            'target' => 'required|numeric',
            'balance' => 'required|numeric',
            'commission' => 'required|numeric',
        ]);

        // Auto-calculate Over
        $validated['over'] = $validated['balance'] > $validated['target'] 
            ? $validated['balance'] - $validated['target'] 
            : 0;

        // Auto-calculate ACH
        $validated['ach'] = $validated['target'] > 0 
            ? ($validated['balance'] / $validated['target']) * 100 
            : 0;

        $target->update($validated);

        return redirect()->route('targets.index')->with('success', 'Target updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Target $target)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        $date = $this->resolveReportDate($request->input('date') ?? $target->report_date?->format('Y-m-d'));

        DeletionLogger::log(
            'target',
            $target->id,
            sprintf(
                'Target: %s / %s (%s)',
                $target->displayRode(),
                $target->displaySr(),
                $target->report_date?->format('Y-m-d') ?? $date
            ),
            [
                'report_date' => $target->report_date?->format('Y-m-d'),
                'rode' => $target->displayRode(),
                'sr' => $target->displaySr(),
                'target' => $target->target,
                'balance' => $target->balance,
            ]
        );

        $target->delete();

        return redirect()->route('targets.index', ['date' => $date])->with('success', 'Target deleted successfully.');
    }
    /**
     * Update daily cost for all targets.
     */
    public function updateDailyCost(Request $request)
    {
        if (!Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'daily_cost' => 'required|numeric',
            'report_date' => 'nullable|date',
        ]);

        $reportDate = $this->resolveReportDate($validated['report_date'] ?? null);

        Target::query()
            ->whereDate('report_date', $reportDate)
            ->update(['daily_cost' => $validated['daily_cost']]);

        return response()->json(['success' => true]);
    }

    /**
     * Persist the "Total for %" summary number for a report date (separate from row target sums).
     */
    public function updatePercentBase(Request $request)
    {
        if (! Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'report_date' => 'required|date',
            'percent_base' => 'nullable|numeric',
        ]);

        $reportDate = $this->resolveReportDate($validated['report_date']);
        $base = $validated['percent_base'] ?? null;

        if ($base === null || $base === '') {
            $setting = ReportDateSetting::query()->whereDate('report_date', $reportDate)->first();
            if ($setting) {
                $setting->percent_base = null;
                $setting->save();
                if ($setting->percent_base === null && $setting->apr_percent === null) {
                    $setting->delete();
                }
            }
        } else {
            ReportDateSetting::query()->updateOrCreate(
                ['report_date' => $reportDate],
                ['percent_base' => $base]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Persist APR % for the report date (summary box).
     */
    public function updateAprPercent(Request $request)
    {
        if (! Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'report_date' => 'required|date',
            'apr_percent' => 'nullable|numeric',
        ]);

        $reportDate = $this->resolveReportDate($validated['report_date']);
        $apr = $validated['apr_percent'] ?? null;

        if ($apr === null || $apr === '') {
            $setting = ReportDateSetting::query()->whereDate('report_date', $reportDate)->first();
            if ($setting) {
                $setting->apr_percent = null;
                $setting->save();
                if ($setting->percent_base === null && $setting->apr_percent === null) {
                    $setting->delete();
                }
            }
        } else {
            ReportDateSetting::query()->updateOrCreate(
                ['report_date' => $reportDate],
                ['apr_percent' => $apr]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Update target or balance inline from index page.
     */
    public function inlineUpdate(Request $request, Target $target)
    {
        if (!Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'rode' => 'nullable|string',
            'name' => 'nullable|string',
            'rode_id' => 'nullable|integer|exists:rodes,id',
            'sr_id' => 'nullable|integer|exists:s_r_s,id',
            'target' => 'nullable|numeric',
            'target_percent' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
        ]);

        $rodeId = array_key_exists('rode_id', $validated) ? $validated['rode_id'] : $target->rode_id;
        $srId = array_key_exists('sr_id', $validated) ? $validated['sr_id'] : $target->sr_id;
        $reportDate = $target->report_date?->format('Y-m-d') ?? $this->resolveReportDate(null);

        if ($rodeId && $srId && $this->isDuplicateRodeSrPair(
            (int) $rodeId,
            (int) $srId,
            $reportDate,
            $target->report_line_id,
            $target->id
        )) {
            return response()->json([
                'error' => 'This Rode and SR are already assigned to another row.',
            ], 422);
        }

        $target->update($validated);

        if (array_key_exists('rode_id', $validated)) {
            $target->rode_id = $validated['rode_id'];
            $target->rode = $target->rode_id
                ? (Rode::query()->find($target->rode_id)?->name ?? '')
                : '';
        }
        if (array_key_exists('sr_id', $validated)) {
            $target->sr_id = $validated['sr_id'];
            $target->name = $target->sr_id
                ? (SR::query()->find($target->sr_id)?->name ?? '')
                : '';
        }
        if (array_key_exists('rode_id', $validated) || array_key_exists('sr_id', $validated)) {
            $target->save();
        }

        // Recalculate Over and ACH
        $target->over = $target->balance > $target->target ? $target->balance - $target->target : 0;
        $target->ach = $target->target > 0 ? ($target->balance / $target->target) * 100 : 0;
        $target->save();

        $reportDate = $target->report_date?->format('Y-m-d') ?? $this->resolveReportDate(null);
        $sameDay = Target::query()->whereDate('report_date', $reportDate)->get();
        $totalTarget = $sameDay->sum('target');
        $totalBalance = $sameDay->sum('balance');
        $totalOver = $sameDay->sum('over');
        $totalAch = $this->computeSummaryTotalAch($reportDate, $sameDay);

        return response()->json([
            'success' => true,
            'over' => $target->over,
            'ach' => $target->ach,
            'totalTarget' => $totalTarget,
            'totalBalance' => $totalBalance,
            'totalOver' => $totalOver,
            'totalAch' => $totalAch,
        ]);
    }

    public function storeReportLine(Request $request)
    {
        if (!Session::get('logged_in')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return redirect()->route('login');
        }

        $max = (int) (ReportLine::query()->max('position') ?? 0);
        ReportLine::query()->create([
            'position' => $max + 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('targets.index', [
            'date' => $this->resolveReportDate($request->input('report_date')),
        ])->with('success', 'Row added.');
    }

    public function destroyReportLine(Request $request, ReportLine $reportLine)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $date = $this->resolveReportDate($request->input('date'));

        $reportLine->load(['rode', 'sr']);
        $targetsQuery = Target::query()
            ->where('report_line_id', $reportLine->id)
            ->whereDate('report_date', $date);
        $targetCount = (clone $targetsQuery)->count();

        DeletionLogger::log(
            'report_line',
            $reportLine->id,
            sprintf(
                'Report row cleared for %s: %s / %s (%d target(s))',
                $date,
                $reportLine->rode?->name ?? '—',
                $reportLine->sr?->name ?? '—',
                $targetCount
            ),
            [
                'report_date' => $date,
                'rode' => $reportLine->rode?->name,
                'sr' => $reportLine->sr?->name,
                'targets_removed' => $targetCount,
            ]
        );

        $targetsQuery->delete();

        $hasTargetsOnOtherDates = Target::query()
            ->where('report_line_id', $reportLine->id)
            ->exists();

        if (! $hasTargetsOnOtherDates) {
            DeletionLogger::log(
                'report_line',
                $reportLine->id,
                sprintf(
                    'Report row removed: %s / %s',
                    $reportLine->rode?->name ?? '—',
                    $reportLine->sr?->name ?? '—'
                ),
                [
                    'rode' => $reportLine->rode?->name,
                    'sr' => $reportLine->sr?->name,
                ]
            );

            $reportLine->delete();

            return redirect()->route('targets.index', ['date' => $date])->with('success', 'Row removed.');
        }

        return redirect()->route('targets.index', ['date' => $date])->with('success', 'Row cleared for this date only. Past dates are unchanged.');
    }

    public function updateReportLineMeta(Request $request, ReportLine $reportLine)
    {
        if (!Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'rode_id' => 'nullable|integer|exists:rodes,id',
            'sr_id' => 'nullable|integer|exists:s_r_s,id',
            'report_date' => 'nullable|date',
        ]);

        $rodeId = $validated['rode_id'] ?? null;
        $srId = $validated['sr_id'] ?? null;
        $reportDate = $this->resolveReportDate($validated['report_date'] ?? $request->input('report_date'));

        if ($rodeId && $srId && $this->isDuplicateRodeSrPair((int) $rodeId, (int) $srId, $reportDate, $reportLine->id, null)) {
            return response()->json([
                'error' => 'This Rode and SR are already assigned to another row.',
            ], 422);
        }

        $reportLine->fill([
            'rode_id' => $rodeId,
            'sr_id' => $srId,
        ]);
        $reportLine->save();
        $reportLine->load(['rode', 'sr']);

        $rodeName = $reportLine->rode?->name ?? '';
        $srName = $reportLine->sr?->name ?? '';

        Target::query()
            ->where('report_line_id', $reportLine->id)
            ->whereDate('report_date', $reportDate)
            ->update([
                'rode_id' => $reportLine->rode_id,
                'sr_id' => $reportLine->sr_id,
                'rode' => $rodeName,
                'name' => $srName,
            ]);

        return response()->json(['success' => true]);
    }

    public function upsertDailyTarget(Request $request)
    {
        if (!Session::get('logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'report_line_id' => 'required|exists:report_lines,id',
            'report_date' => 'required|date',
            'target' => 'nullable|numeric',
            'target_percent' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
        ]);

        $reportDate = $this->resolveReportDate($validated['report_date']);
        $line = ReportLine::query()->with(['rode', 'sr'])->findOrFail($validated['report_line_id']);

        $target = Target::query()->firstOrNew([
            'report_line_id' => $line->id,
            'report_date' => $reportDate,
        ]);

        if (! $target->exists) {
            $first = Target::query()->whereDate('report_date', $reportDate)->orderBy('id')->first();
            $target->fill([
                'rode_id' => $line->rode_id,
                'sr_id' => $line->sr_id,
                'rode' => $line->rode?->name ?? '',
                'name' => $line->sr?->name ?? '',
                'target' => 0,
                'target_percent' => 0,
                'balance' => 0,
                'commission' => 0,
                'over' => 0,
                'ach' => 0,
                'daily_cost' => $first ? $first->daily_cost : 0,
            ]);
        }

        if ($request->exists('target_percent')) {
            $target->target_percent = (float) ($validated['target_percent'] ?? 0);
        }
        if ($request->exists('target')) {
            $target->target = (float) ($validated['target'] ?? 0);
        }
        if ($request->exists('balance')) {
            $target->balance = (float) ($validated['balance'] ?? 0);
        }
        if ($request->exists('commission')) {
            $target->commission = (float) ($validated['commission'] ?? 0);
        }

        $target->over = $target->balance > $target->target ? $target->balance - $target->target : 0;
        $target->ach = $target->target > 0 ? ($target->balance / $target->target) * 100 : 0;
        $target->save();

        $sameDay = Target::query()->whereDate('report_date', $reportDate)->get();
        $totalTarget = $sameDay->sum('target');
        $totalBalance = $sameDay->sum('balance');
        $totalOver = $sameDay->sum('over');
        $totalAch = $this->computeSummaryTotalAch($reportDate, $sameDay);

        return response()->json([
            'success' => true,
            'targetId' => $target->id,
            'over' => $target->over,
            'ach' => $target->ach,
            'totalTarget' => $totalTarget,
            'totalBalance' => $totalBalance,
            'totalOver' => $totalOver,
            'totalAch' => $totalAch,
        ]);
    }

    public function indexRodes()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $rodes = Rode::query()->orderBy('name')->get();

        return view('targets.rodes.index', compact('rodes'));
    }

    public function indexSRs()
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $srs = SR::query()->orderBy('name')->get();

        return view('targets.srs.index', compact('srs'));
    }

    public function destroyRode(Rode $rode)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        DeletionLogger::log(
            'rode',
            $rode->id,
            'Rode: '.$rode->name,
            ['name' => $rode->name]
        );

        $rode->delete();

        return redirect()->route('targets.rodes.index')->with('success', 'Rode deleted.');
    }

    public function destroySR(SR $sr)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        DeletionLogger::log(
            'sr',
            $sr->id,
            'SR: '.$sr->name,
            ['name' => $sr->name]
        );

        $sr->delete();

        return redirect()->route('targets.srs.index')->with('success', 'SR deleted.');
    }

    public function storeRode(Request $request)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        $validated = $request->validate(['rode' => 'required|string']);
        Rode::create(['name' => $validated['rode']]);

        return redirect()->route('targets.rodes.index')->with('success', 'Rode added successfully');
    }

    public function storeSR(Request $request)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }
        $validated = $request->validate(['name' => 'required|string']);
        SR::create(['name' => $validated['name']]);

        return redirect()->route('targets.srs.index')->with('success', 'SR added successfully');
    }

    /**
     * Summary Ach %: total balance / (saved Total Target when set and positive, else sum of row targets) × 100.
     */
    private function computeSummaryTotalAch(string $reportDate, $sameDay): float
    {
        $totalBalance = (float) $sameDay->sum('balance');
        $sumTargets = (float) $sameDay->sum('target');
        $setting = ReportDateSetting::query()->whereDate('report_date', $reportDate)->first();
        $denominator = ($setting && $setting->percent_base !== null && (float) $setting->percent_base > 0)
            ? (float) $setting->percent_base
            : $sumTargets;

        return $denominator > 0.0 ? ($totalBalance / $denominator) * 100 : 0.0;
    }

    private function isDuplicateRodeSrPair(
        int $rodeId,
        int $srId,
        string $reportDate,
        ?int $excludeLineId,
        ?int $excludeTargetId
    ): bool {
        $lineQuery = ReportLine::query()
            ->where('rode_id', $rodeId)
            ->where('sr_id', $srId);
        if ($excludeLineId !== null) {
            $lineQuery->where('id', '!=', $excludeLineId);
        }
        if ($lineQuery->exists()) {
            return true;
        }

        $targetQuery = Target::query()
            ->whereDate('report_date', $reportDate)
            ->where('rode_id', $rodeId)
            ->where('sr_id', $srId);

        if ($excludeTargetId !== null) {
            $targetQuery->where('id', '!=', $excludeTargetId);
        }
        if ($excludeLineId !== null) {
            $targetQuery->where(function ($q) use ($excludeLineId) {
                $q->whereNull('report_line_id')
                    ->orWhere('report_line_id', '!=', $excludeLineId);
            });
        }

        return $targetQuery->exists();
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
