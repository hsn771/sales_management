@extends('layouts.app')

@section('title', 'Daily Report')

@section('styles')
    <style>
        /* White canvas on this page (layout uses #f0f2f8 on body and .content) */
        body {
            background: #fff !important;
        }

        .main-wrap .content {
            background: #fff !important;
        }

        .dr-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 14px;
        }

        .dr-toolbar label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }

        .dr-btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 2px 10px rgba(79, 70, 229, 0.25);
        }

        .dr-btn-print:hover {
            opacity: 0.92;
        }

        .report-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid #d1d5db;
        }

        /* White strip at top of card: date sits in “blank” area, top-right */
        .report-date-top-space {
            background: #fff;
            min-height: 40px;
            padding: 12px 16px 2px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: none;
            margin-bottom: 4px;
        }

        .report-date-top-right {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: none;
            letter-spacing: 0.02em;
        }

        .report-date-top-space .report-date-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .report-date-top-space .report-date-display {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
        }

        .report-date-top-space .report-date-cal-svg {
            flex-shrink: 0;
            color: #64748b;
        }

        .report-header {
            background: #374151;
            color: #fff;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .report-header-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        .report-header-controls .report-date-input {
            margin-top: 0;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .header-date {
            font-size: 0.8rem;
            font-weight: 500;
            opacity: 0.8;
            letter-spacing: 0.05em;
        }

        .report-date-input {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #fff;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.8rem;
            margin-top: 10px;
            font-weight: 600;
            letter-spacing: 0.04em;
            color-scheme: dark;
        }

        .report-date-input:focus {
            outline: 2px solid rgba(129, 140, 248, 0.8);
            border-color: transparent;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px;
            font-size: 0.85rem;
            color: #374151;
            font-weight: 700;
        }

        .report-table td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            font-size: 0.88rem;
            color: #111827;
            text-align: center;
        }

        .dr-read-cell {
            font-weight: 500;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            background: #fff;
            border-top: 2px solid #374151;
            border-left: 1px solid #d1d5db;
            border-right: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
        }

        .summary-box {
            background: #f9fafb;
            padding: 7px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #374151;
            border-right: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            box-sizing: border-box;
        }

        .summary-box:nth-child(3n) {
            border-right: none;
            border-left: 1px solid #d1d5db;
        }

        .summary-box:nth-last-child(-n + 3) {
            border-bottom: none;
        }

        .summary-box--target {
            align-items: flex-start;
        }

        .summary-box--empty {
            background: #e5e7eb;
            min-height: 0;
        }

        .summary-box svg {
            width: 15px;
            height: 15px;
            color: #6b7280;
            flex-shrink: 0;
        }

        .summary-label {
            font-weight: 600;
            color: #4b5563;
        }

        .summary-value {
            font-weight: 700;
            color: #111827;
            margin-left: auto;
        }

        .summary-percent-wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
            margin-left: auto;
        }

        .summary-row-target-sum {
            font-size: 0.65rem;
            color: #64748b;
            font-weight: 500;
            text-align: right;
            line-height: 1.1;
            max-width: 200px;
        }

        .summary-row-target-sum strong {
            color: #111827;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .dr-summary-num {
            min-width: 72px;
            text-align: right;
        }

        .dr-print-shell {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .report-signatures-row {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-end;
            flex-wrap: nowrap;
            gap: 18px;
            width: 100%;
            box-sizing: border-box;
            padding: 24px 16px 8px;
            margin-top: auto;
            margin-bottom: 72px;
            background: #fff;
        }

        .report-signature-slot {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 0 0 auto;
        }

        .report-signature-line {
            display: block;
            width: 110px;
            border-bottom: 1.5px solid #111827;
            height: 0;
        }

        .report-signature-label {
            display: block;
            width: 110px;
            margin-top: 5px;
            text-align: center;
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            letter-spacing: 0.03em;
        }

        .dr-print-top-gap {
            display: none;
        }

        .dr-page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 900px) {
            .dr-page-head {
                flex-direction: column;
                align-items: stretch;
            }

            .dr-toolbar {
                width: 100%;
                justify-content: flex-start;
            }

            .report-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-top: 12px;
                border-radius: 10px;
            }

            .report-table {
                min-width: 640px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .summary-box:nth-child(3n) {
                border-right: none;
            }

            .summary-box {
                border-right: none !important;
                flex-wrap: wrap;
            }

            .report-date-top-space {
                padding: 10px 12px 2px;
            }
        }

        @media print {
            --print-margin-top: 3cm;
            --print-margin-right: 6mm;
            --print-margin-bottom: 0mm;
            --print-margin-left: 6mm;

            @page {
                size: A4 portrait;
                margin: 0;
            }

            .sidebar,
            .topbar,
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                min-height: 100vh !important;
                height: auto !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .main-wrap {
                margin: 0 !important;
                margin-left: 0 !important;
                padding: 0 !important;
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                min-height: 100vh !important;
                height: auto !important;
                overflow: visible !important;
            }

            .dr-print-top-gap {
                display: block !important;
                width: 100%;
                height: 3.2cm !important;
                min-height: 3.2cm !important;
                flex: 0 0 auto !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .content {
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                align-items: center !important;
                padding: 0 var(--print-margin-right) var(--print-margin-bottom) var(--print-margin-left) !important;
                margin: 0 !important;
                min-height: 100vh !important;
                overflow: visible !important;
                background: #fff !important;
                box-sizing: border-box !important;
            }

            .content::after {
                display: none !important;
            }

            .dr-print-shell {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                box-sizing: border-box;
                display: flex !important;
                flex-direction: column !important;
                flex: 1 1 auto !important;
                min-height: calc(100vh - 3.2cm) !important;
                padding: 0;
            }

            .report-container {
                box-shadow: none !important;
                filter: none !important;
                margin: 0 !important;
                border: none !important;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                border-radius: 0;
            }

            .report-date-top-space {
                min-height: 0 !important;
                padding: 0 12px 0.5mm !important;
                margin-bottom: 1mm !important;
                background: #fff !important;
                border-bottom: none !important;
            }

            .report-date-top-space .report-date-label {
                font-size: 8.5pt !important;
                color: #475569 !important;
            }

            .report-date-top-space .report-date-display {
                font-size: 11pt !important;
                font-weight: 700;
                color: #000 !important;
            }

            .report-date-top-space .report-date-cal-svg {
                width: 15px !important;
                height: 15px !important;
                color: #374151 !important;
            }

            .report-table th {
                font-size: 10pt !important;
                padding: 8px 10px !important;
            }

            .report-table td {
                font-size: 10.5pt !important;
                padding: 7px 10px !important;
            }

            /* Summary: 3×3 sheet like reference — white cells, thin grid, grey corner */
            .summary-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 0 !important;
                background: #fff !important;
                border-top: 1px solid #d1d5db !important;
                border-left: 1px solid #d1d5db !important;
                border-right: 1px solid #d1d5db !important;
                border-bottom: 1px solid #d1d5db !important;
            }

            .summary-box {
                background: #fff !important;
                border-right: 1px solid #d1d5db !important;
                border-bottom: 1px solid #d1d5db !important;
                padding: 4px 8px !important;
                font-size: 8.5pt !important;
                color: #374151 !important;
                gap: 5px !important;
                align-items: center !important;
                min-height: 0 !important;
                box-sizing: border-box;
            }

            .summary-box--target {
                align-items: flex-start !important;
            }

            .summary-box:nth-child(3n) {
                border-right: none !important;
                border-left: 1px solid #d1d5db !important;
            }

            .summary-box:nth-last-child(-n + 3) {
                border-bottom: none !important;
            }

            .summary-box--empty {
                background: #e8e8e8 !important;
                border-right: none !important;
                border-bottom: none !important;
            }

            .summary-box svg {
                width: 14px !important;
                height: 14px !important;
                color: #6b7280 !important;
                flex-shrink: 0;
                margin-top: 0 !important;
                stroke-width: 1.75 !important;
            }

            .summary-label {
                font-weight: 600 !important;
                color: #4b5563 !important;
                font-size: 8pt !important;
            }

            .summary-value,
            .summary-box .dr-summary-num {
                font-weight: 700 !important;
                color: #000 !important;
                font-size: 9pt !important;
            }

            .summary-percent-wrap {
                margin-left: auto !important;
                align-items: flex-end !important;
                gap: 1px !important;
            }

            .summary-row-target-sum {
                font-size: 7pt !important;
                color: #6b7280 !important;
                line-height: 1.1 !important;
            }

            .summary-row-target-sum strong {
                color: #000 !important;
                font-size: 7pt !important;
            }

            .header-title {
                font-size: 13pt !important;
            }

            .header-title svg {
                width: 22px !important;
                height: 22px !important;
            }

            .report-header {
                padding: 10px 14px !important;
            }

            .report-signatures-row {
                margin-top: auto !important;
                margin-bottom: 30mm !important;
                justify-content: center !important;
                padding: 8mm 0 0 0 !important;
                gap: 5mm !important;
                background: #fff !important;
            }

            .report-signature-line {
                width: 38mm !important;
                border-bottom: 1px solid #000 !important;
            }

            .report-signature-label {
                width: 38mm !important;
                margin-top: 1.5mm !important;
                font-size: 8.5pt !important;
                color: #000 !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="no-print dr-page-head">
        <h1 class="page-title">Daily Report Management</h1>
        <div class="dr-toolbar">
            <button type="button" class="dr-btn-print" onclick="window.print()">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </div>

    <div class="dr-print-top-gap" aria-hidden="true"></div>

    <div class="dr-print-shell">
    <div class="report-container">
        <div class="report-date-top-space">
            <div class="report-date-top-right">
                <span class="report-date-label"></span>
                <span class="report-date-display">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</span>
                <svg class="report-date-cal-svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="report-header">
            <div class="header-title">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Daily Report
            </div>
            <div class="report-header-controls no-print">
                <label for="report-date-input" class="header-date" style="cursor: pointer;">Select date</label>
                <input type="date" id="report-date-input" class="report-date-input" name="report_date"
                    value="{{ $selectedDate }}" max="2099-12-31" aria-label="Report date">
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 100px;">Rode</th>
                    <th>SR (Name)</th>
                    <th style="width: 76px;">Target %</th>
                    <th>Target</th>
                    <th>Balance</th>
                    <th>Over</th>
                    <th>Comm.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalTarget = 0;
                    $totalBalance = 0;
                    $totalOver = 0;
                    $totalComm = 0;
                    $bestName = 'N/A';
                    $bestAch = 0;
                    foreach ($dayTargets as $t) {
                        $totalTarget += $t->target;
                        $totalBalance += $t->balance;
                        $totalOver += $t->over;
                        $totalComm += $t->commission;
                        if ($t->ach > $bestAch) {
                            $bestAch = $t->ach;
                            $bestName = $t->name;
                        }
                    }
                    $achDenominator = ($percentBaseForDay !== null && (float) $percentBaseForDay > 0)
                        ? (float) $percentBaseForDay
                        : $totalTarget;
                @endphp
                @foreach($reportLines as $line)
                    @php
                        $target = $targetsByLineId->get($line->id);
                    @endphp
                    <tr>
                        @php
                            $rodeNm = $target
                                ? ($target->displayRode($line->rode?->name) ?: '—')
                                : ($line->rode?->name ?? '—');
                            $srNm = $target
                                ? ($target->displaySr($line->sr?->name) ?: '—')
                                : ($line->sr?->name ?? '—');
                        @endphp
                        <td class="dr-read-cell">{{ $rodeNm }}</td>
                        <td class="dr-read-cell">{{ $srNm }}</td>
                        <td class="dr-read-cell">{{ $target && (float) $target->target_percent > 0 ? (float) $target->target_percent : '—' }}</td>
                        <td class="dr-read-cell">{{ $target ? number_format($target->target, 0, '.', '') : '—' }}</td>
                        <td class="dr-read-cell">{{ $target ? number_format($target->balance, 0, '.', '') : '—' }}</td>
                        <td class="dr-read-cell">{{ $target ? number_format($target->over, 0, '.', '') : '—' }}</td>
                        <td class="dr-read-cell">{{ $target ? number_format($target->commission, 0, '.', '') : '—' }}</td>
                    </tr>
                @endforeach
                @foreach($legacyTargets as $target)
                    @php
                        $rodeNm = $target->displayRode(
                            optional($allRodes->firstWhere('id', $target->rode_id))->name
                        ) ?: '—';
                        $srNm = $target->displaySr(
                            optional($allNames->firstWhere('id', $target->sr_id))->name
                        ) ?: '—';
                    @endphp
                    <tr>
                        <td class="dr-read-cell">{{ $rodeNm }}</td>
                        <td class="dr-read-cell">{{ $srNm }}</td>
                        <td class="dr-read-cell">{{ (float) $target->target_percent > 0 ? (float) $target->target_percent : '—' }}</td>
                        <td class="dr-read-cell">{{ number_format($target->target, 0, '.', '') }}</td>
                        <td class="dr-read-cell">{{ number_format($target->balance, 0, '.', '') }}</td>
                        <td class="dr-read-cell">{{ number_format($target->over, 0, '.', '') }}</td>
                        <td class="dr-read-cell">{{ number_format($target->commission, 0, '.', '') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-grid">
            <div class="summary-box summary-box--target">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v8m-4-4h8" />
                </svg>
                <span class="summary-label">Total Target:</span>
                <div class="summary-percent-wrap">
                    <span class="summary-value dr-summary-num">{{ $percentBaseForDay !== null ? number_format($percentBaseForDay, 0, '.', '') : '—' }}</span>
                    <span class="summary-row-target-sum">Sum of row Targets:
                        <strong>{{ $dayTargets->count() > 0 ? number_format($totalTarget, 0, '.', '') : '0' }}</strong></span>
                </div>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="summary-label">Total Balance:</span>
                <span class="summary-value dr-summary-num">{{ $dayTargets->count() > 0 ? number_format($totalBalance, 0, '.', '') : '—' }}</span>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="summary-label">Ach %:</span>
                <span class="summary-value dr-summary-num">@if($dayTargets->count() > 0){{ $achDenominator > 0 ? number_format(($totalBalance / $achDenominator) * 100, 1) : '0.0' }}%@else — @endif</span>
            </div>

            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="summary-label">Total Commission:</span>
                <span class="summary-value dr-summary-num">{{ $dayTargets->count() > 0 ? number_format($totalComm, 0, '.', '') : '—' }}</span>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <span class="summary-label">Total Over:</span>
                <span class="summary-value dr-summary-num">{{ $dayTargets->count() > 0 ? number_format($totalOver, 0, '.', '') : '—' }}</span>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="summary-label">Best:</span>
                <span class="summary-value dr-summary-num">{{ $dayTargets->count() > 0 ? $bestName : '—' }}</span>
            </div>

            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="summary-label">Daily Cost:</span>
                <span class="summary-value dr-summary-num">{{ $dayTargets->count() > 0 ? number_format($dayTargets->first()->daily_cost ?? 0, 0, '.', '') : '—' }}</span>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="summary-label">APR %:</span>
                <span class="summary-value dr-summary-num">{{ $aprPercentForDay !== null ? number_format($aprPercentForDay, 0, '.', '') : '—' }}</span>
            </div>
            <div class="summary-box summary-box--empty" aria-hidden="true"></div>
        </div>

    </div>

        <div class="report-signatures-row">
            @foreach (['Manager', 'RSM', 'RSO', 'Signature'] as $signatureLabel)
                <div class="report-signature-slot">
                    <span class="report-signature-line" aria-hidden="true"></span>
                    <span class="report-signature-label">{{ $signatureLabel }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportDateInput = document.getElementById('report-date-input');
            if (reportDateInput) {
                reportDateInput.addEventListener('change', function () {
                    window.location.href = "{{ route('daily-report.index') }}?date=" + encodeURIComponent(this.value);
                });
            }
        });
    </script>
@endsection
