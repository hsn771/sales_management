<?php

namespace App\Http\Controllers;

use App\Models\ReportDateSetting;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MonthlySummaryController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        $monthInput = $request->query('month');
        try {
            $monthStart = $monthInput
                ? Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Throwable) {
            $monthStart = now()->startOfMonth();
        }

        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthParam = $monthStart->format('Y-m');

        $aggregates = Target::query()
            ->whereBetween('report_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->selectRaw('report_date, SUM(target) as total_target, SUM(balance) as total_balance, MAX(daily_cost) as net_cost')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->report_date)->format('Y-m-d'));

        $percentBases = ReportDateSetting::query()
            ->whereBetween('report_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get()
            ->keyBy(fn ($row) => $row->report_date->format('Y-m-d'));

        $rows = [];
        for ($day = $monthStart->copy(); $day->lte($monthEnd); $day->addDay()) {
            $key = $day->format('Y-m-d');
            $agg = $aggregates->get($key);
            $daySetting = $percentBases->get($key);
            $savedPercentBase = $daySetting && $daySetting->percent_base !== null
                ? (float) $daySetting->percent_base
                : null;

            if ($agg) {
                $sumRowTargets = (float) $agg->total_target;
                $totalTarget = $savedPercentBase !== null ? $savedPercentBase : $sumRowTargets;
                $totalBalance = (float) $agg->total_balance;
                $netCost = (float) $agg->net_cost;
                $metTarget = $totalBalance >= $totalTarget;
                $yesNo = $metTarget ? 'Yes' : 'No';
                $netProfit = $totalTarget >= $totalBalance ? $totalTarget - $totalBalance : null;
                $netLoss = $totalTarget < $totalBalance ? $totalBalance - $totalTarget : null;
            } elseif ($savedPercentBase !== null) {
                $totalTarget = $savedPercentBase;
                $totalBalance = $netCost = null;
                $yesNo = null;
                $netProfit = null;
                $netLoss = null;
            } else {
                $totalTarget = $totalBalance = $netCost = null;
                $yesNo = null;
                $netProfit = null;
                $netLoss = null;
            }

            $rows[] = [
                'date' => $day->copy(),
                'total_target' => $totalTarget,
                'yes_no' => $yesNo,
                'target_balance' => $totalBalance,
                'net_cost' => $netCost,
                'net_profit' => $netProfit,
                'net_loss' => $netLoss,
            ];
        }

        $sumTarget = 0.0;
        $sumBalance = 0.0;
        $sumCost = 0.0;
        $sumProfit = 0.0;
        $sumLoss = 0.0;
        foreach ($rows as $r) {
            if ($r['total_target'] !== null) {
                $sumTarget += $r['total_target'];
            }
            if ($r['target_balance'] !== null) {
                $sumBalance += $r['target_balance'];
            }
            if ($r['net_cost'] !== null) {
                $sumCost += $r['net_cost'];
            }
            if ($r['net_profit'] !== null) {
                $sumProfit += $r['net_profit'];
            }
            if ($r['net_loss'] !== null) {
                $sumLoss += $r['net_loss'];
            }
        }
        $grandNet = $sumProfit - $sumLoss;

        $titleMonth = strtoupper($monthStart->format('M')).'-'.$monthStart->format('y');

        return view('monthly-summary.index', [
            'rows' => $rows,
            'monthParam' => $monthParam,
            'monthLabel' => $monthStart->format('F Y'),
            'titleMonth' => $titleMonth,
            'totals' => [
                'total_target' => $sumTarget,
                'target_balance' => $sumBalance,
                'net_cost' => $sumCost,
                'net_profit' => $sumProfit,
                'net_loss' => $sumLoss,
            ],
            'grandNet' => $grandNet,
        ]);
    }
}
