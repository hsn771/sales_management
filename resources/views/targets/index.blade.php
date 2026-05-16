@extends('layouts.app')

@section('title', 'Targets')

@section('styles')
    <style>
        .report-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid #d1d5db;
        }

        .report-header {
            background: #374151;
            color: #fff;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.1rem;
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
            padding: 5px 8px;
            font-size: 0.88rem;
            color: #111827;
            text-align: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: #d1d5db;
            border-top: 2px solid #374151;
        }

        .summary-box {
            background: #f9fafb;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #374151;
        }

        .summary-box svg {
            width: 18px;
            height: 18px;
            color: #6b7280;
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

        .btn-create-top {
            margin-bottom: 15px;
        }

        .sortable-ghost {
            opacity: 0.4;
            background-color: #e2e8f0 !important;
        }

        .inline-input {
            background: transparent;
            border: 1px solid transparent;
            padding: 6px;
            border-radius: 4px;
            width: 100%;
            font-weight: 500;
            color: #111827;
            text-align: center;
            transition: border-color 0.2s, background 0.2s;
        }

        .inline-input:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .inline-input:focus {
            background: #fff;
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .editable-cost {
            background: #fff;
            border: 1px solid #d1d5db;
            padding: 4px 8px;
            border-radius: 4px;
            width: 100px;
            font-weight: 700;
            color: #111827;
            text-align: right;
            margin-left: auto;
        }

        .editable-cost:focus {
            outline: 2px solid #4f46e5;
            border-color: transparent;
        }

        .btn-add-row {
            background: #fff;
            border: 1px solid #4f46e5;
            color: #4f46e5;
            padding: 4px 12px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            font-size: 0.85rem;
            border-radius: 6px;
            margin: 15px 10px;
            transition: background 0.2s;
            width: fit-content;
        }

        .btn-add-row:hover {
            background: #f1f4ff;
        }

        .summary-percent-wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            margin-left: auto;
        }

        .summary-row-target-sum {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
            text-align: right;
            line-height: 1.2;
            max-width: 200px;
        }

        .summary-row-target-sum strong {
            color: #111827;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .targets-page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 900px) {
            .targets-page-head {
                flex-direction: column;
                align-items: stretch;
            }

            .report-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-top: 12px;
                border-radius: 10px;
            }

            .report-table {
                min-width: 760px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .summary-box {
                flex-wrap: wrap;
            }

            .editable-cost {
                max-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="targets-page-head">
        <h1 class="page-title">Targets</h1>
        {{-- <a href="{{ route('targets.create') }}" class="btn-action btn-create-top">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New Record
        </a> --}}
    </div>

    <div class="report-container">
        <div class="report-header">
            <div class="header-title">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Daily Targets
            </div>
            <label for="report-date-input" class="header-date" style="cursor: pointer;">Select date</label>
            <input type="date" id="report-date-input" class="report-date-input" name="report_date"
                value="{{ $selectedDate }}" max="2099-12-31" aria-label="Report date">
            <div class="header-date" style="margin-top: 4px; opacity: 0.75;">
                {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 40px;"></th>
                    <th style="width: 100px;">Rode</th>
                    <th>SR (Name)</th>
                    <th style="width: 76px;">Target %</th>
                    <th>Target</th>
                    <th>Balance</th>
                    <th>Over</th>
                    <th>Comm.</th>
                    <th style="width: 80px;">Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-tbody">
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
                        $rowRodeId = $target?->rode_id ?? $line->rode_id;
                        $rowSrId = $target?->sr_id ?? $line->sr_id;
                    @endphp
                    <tr data-line-id="{{ $line->id }}" @if($target) data-id="{{ $target->id }}" @endif>
                        <td class="drag-handle" style="cursor: grab; color: #9ca3af;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M4 8h16M4 16h16" />
                            </svg>
                        </td>
                        <td>
                            <select class="inline-input line-meta-field" data-line-id="{{ $line->id }}" data-field="rode_id">
                                <option value="">Select Rode</option>
                                @foreach($allRodes as $rode)
                                    <option value="{{ $rode->id }}" {{ (int) $rowRodeId === (int) $rode->id ? 'selected' : '' }}>
                                        {{ $rode->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="inline-input line-meta-field" data-line-id="{{ $line->id }}" data-field="sr_id">
                                <option value="">Select SR</option>
                                @foreach($allNames as $sr)
                                    <option value="{{ $sr->id }}" {{ (int) $rowSrId === (int) $sr->id ? 'selected' : '' }}>{{ $sr->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="inline-input daily-field" inputmode="decimal"
                                data-line-id="{{ $line->id }}"
                                @if($target) data-id="{{ $target->id }}" @endif
                                data-field="target_percent"
                                title="% of the Total Target number in the summary box below (e.g. 5 with 40000 there → 2000 in Target)"
                                step="any"
                                value="{{ $target && (float) $target->target_percent > 0 ? (float) $target->target_percent : '' }}"
                                placeholder="">
                        </td>
                        <td>
                            <input type="number" class="inline-input daily-field"
                                data-line-id="{{ $line->id }}"
                                @if($target) data-id="{{ $target->id }}" @endif
                                data-field="target"
                                value="{{ $target ? number_format($target->target, 0, '.', '') : '' }}" step="0.01">
                        </td>
                        <td>
                            <input type="number" class="inline-input daily-field"
                                data-line-id="{{ $line->id }}"
                                @if($target) data-id="{{ $target->id }}" @endif
                                data-field="balance"
                                value="{{ $target ? number_format($target->balance, 0, '.', '') : '' }}" step="0.01">
                        </td>
                        <td id="{{ $target ? 'over-'.$target->id : 'over-line-'.$line->id }}">{{ $target ? number_format($target->over, 0, '.', '') : '' }}</td>
                        <td>
                            <input type="number" class="inline-input daily-field"
                                data-line-id="{{ $line->id }}"
                                @if($target) data-id="{{ $target->id }}" @endif
                                data-field="commission"
                                value="{{ $target ? number_format($target->commission, 0, '.', '') : '' }}"
                                step="0.01">
                        </td>
                        <td>
                            <form action="{{ route('targets.reportLines.destroy', $line) }}" method="POST" style="display: inline;"
                                onsubmit="return confirm('Clear this row for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }} only? Past dates keep their saved Rode/SR in Daily Report.')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <button type="submit"
                                    style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: 0.75rem; cursor: pointer;">Del</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @foreach($legacyTargets as $target)
                    <tr data-id="{{ $target->id }}">
                        <td class="drag-handle" style="cursor: grab; color: #9ca3af;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M4 8h16M4 16h16" />
                            </svg>
                        </td>
                        <td>
                            <select class="inline-input update-field" data-id="{{ $target->id }}" data-field="rode_id">
                                <option value="">Select Rode</option>
                                @foreach($allRodes as $rode)
                                    <option value="{{ $rode->id }}" {{ $target->rode_id == $rode->id ? 'selected' : '' }}>
                                        {{ $rode->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="inline-input update-field" data-id="{{ $target->id }}" data-field="sr_id">
                                <option value="">Select SR</option>
                                @foreach($allNames as $sr)
                                    <option value="{{ $sr->id }}" {{ $target->sr_id == $sr->id ? 'selected' : '' }}>{{ $sr->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="inline-input update-field" data-id="{{ $target->id }}"
                                data-field="target_percent" inputmode="decimal" step="any"
                                title="% of the Total Target in the summary box below"
                                value="{{ (float) $target->target_percent > 0 ? (float) $target->target_percent : '' }}"
                                placeholder="">
                        </td>
                        <td>
                            <input type="number" class="inline-input update-field" data-id="{{ $target->id }}"
                                data-field="target" value="{{ number_format($target->target, 0, '.', '') }}" step="0.01">
                        </td>
                        <td>
                            <input type="number" class="inline-input update-field" data-id="{{ $target->id }}"
                                data-field="balance" value="{{ number_format($target->balance, 0, '.', '') }}" step="0.01">
                        </td>
                        <td id="over-{{ $target->id }}">{{ number_format($target->over, 0, '.', '') }}</td>
                        <td>
                            <input type="number" class="inline-input update-field" data-id="{{ $target->id }}"
                                data-field="commission" value="{{ number_format($target->commission, 0, '.', '') }}"
                                step="0.01">
                        </td>
                        <td>
                            <form action="{{ route('targets.destroy', $target) }}" method="POST" style="display: inline;"
                                onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <button type="submit"
                                    style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: 0.75rem; cursor: pointer;">Del</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button id="add-row-btn" class="btn-add-row">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Row
        </button>

        <div class="summary-grid">
            <div class="summary-box" style="align-items: flex-start;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 8v8m-4-4h8" />
                </svg>
                <span class="summary-label">Total Target:</span>
                <div class="summary-percent-wrap">
                    <input type="number" id="summary-target" class="editable-cost"
                        title="Saved for this date. Used with Target % in each row (e.g. 25000 and 5% → 1250 in that row's Target). Not replaced by the sum of row targets."
                        value="{{ $percentBaseForDay !== null ? number_format($percentBaseForDay, 0, '.', '') : '' }}"
                        placeholder="">
                    <span class="summary-row-target-sum">Sum of row Targets:
                        <strong id="summary-target-table-total">{{ $dayTargets->count() > 0 ? number_format($totalTarget, 0, '.', '') : '0' }}</strong></span>
                </div>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="summary-label">Total Balance:</span>
                <input type="number" id="summary-balance" class="editable-cost"
                    value="{{ $dayTargets->count() > 0 ? number_format($totalBalance, 0, '.', '') : '' }}">
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="summary-label">Ach %:</span>
                <span class="summary-value"
                    id="summary-ach">@if($dayTargets->count() > 0){{ $achDenominator > 0 ? number_format(($totalBalance / $achDenominator) * 100, 1) : '0.0' }}%@endif</span>
            </div>

            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="summary-label">Total Commission:</span>
                <input type="number" id="summary-commission" class="editable-cost" value="{{ $dayTargets->count() > 0 ? number_format($totalComm, 0, '.', '') : '' }}" readonly>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                <span class="summary-label">Total Over:</span>
                <span class="summary-value" id="summary-over">{{ $dayTargets->count() > 0 ? number_format($totalOver, 0, '.', '') : '' }}</span>
            </div>
            <div class="summary-box">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="summary-label">Best:</span>
                <span class="summary-value" id="summary-best">{{ $dayTargets->count() > 0 ? $bestName : '' }}</span>
            </div>

            <div class="summary-box" style="grid-column: span 1; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="summary-label">Daily Cost:</span>
                <input type="number" id="daily-cost-input" class="editable-cost"
                    value="{{ $dayTargets->count() > 0 ? number_format($dayTargets->first()->daily_cost ?? 0, 0, '.', '') : '' }}" placeholder="">
            </div>
            <div class="summary-box" style="grid-column: span 1; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="summary-label">APR %:</span>
                <input type="number" id="apr-input" class="editable-cost" placeholder=""
                    value="{{ $aprPercentForDay !== null ? number_format($aprPercentForDay, 0, '.', '') : '' }}"
                    step="any"
                    title="Saved for this date. Used to compute commission from each row balance.">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportDateInput = document.getElementById('report-date-input');
            if (reportDateInput) {
                reportDateInput.addEventListener('change', function () {
                    window.location.href = "{{ route('targets.index') }}?date=" + encodeURIComponent(this.value);
                });
            }

            // Sortable logic
            const el = document.getElementById('sortable-tbody');
            if (el) {
                Sortable.create(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: function (evt) {
                        console.log('Row moved');
                    },
                });
            }

            function sumRowTargetsFromDom() {
                let sum = 0;
                document.querySelectorAll('#sortable-tbody tr[data-line-id] .daily-field[data-field="target"]').forEach(inp => {
                    sum += parseFloat(String(inp.value).replace(/,/g, '')) || 0;
                });
                document.querySelectorAll('#sortable-tbody tr[data-id]:not([data-line-id]) .update-field[data-field="target"]').forEach(inp => {
                    sum += parseFloat(String(inp.value).replace(/,/g, '')) || 0;
                });
                return sum;
            }

            function refreshTableTargetTotalFromInputs() {
                const sum = sumRowTargetsFromDom();
                const totalEl = document.getElementById('summary-target-table-total');
                if (totalEl) {
                    totalEl.textContent = String(Math.round(sum));
                }
            }

            function computeSummaryAchDenominatorFromDom() {
                const fromSummary = parseSummaryTotalTarget();
                return fromSummary > 0 ? fromSummary : sumRowTargetsFromDom();
            }

            function refreshSummaryAchFromDom() {
                const ach = document.getElementById('summary-ach');
                const balEl = document.getElementById('summary-balance');
                if (!ach || !balEl) {
                    return;
                }
                const bal = parseFloat(String(balEl.value).replace(/,/g, '')) || 0;
                const d = computeSummaryAchDenominatorFromDom();
                ach.innerText = (d > 0 ? (bal / d) * 100 : 0).toFixed(1) + '%';
            }

            function parseSummaryTotalTarget() {
                const summaryEl = document.getElementById('summary-target');
                if (!summaryEl || String(summaryEl.value).trim() === '') {
                    return 0;
                }
                return parseFloat(String(summaryEl.value).replace(/,/g, '')) || 0;
            }

            function applyRowTotalsToSummaryUi(data) {
                if (!data || !data.success) {
                    return;
                }
                const bal = document.getElementById('summary-balance');
                const over = document.getElementById('summary-over');
                const ach = document.getElementById('summary-ach');
                if (bal) {
                    bal.value = data.totalBalance.toFixed(0);
                }
                if (over) {
                    over.innerText = data.totalOver.toFixed(0);
                }
                if (ach) {
                    ach.innerText = data.totalAch.toFixed(1) + '%';
                }
                refreshTableTargetTotalFromInputs();
            }

            async function persistPercentBase() {
                const summaryEl = document.getElementById('summary-target');
                const raw = summaryEl ? String(summaryEl.value).trim() : '';
                const body = { report_date: '{{ $selectedDate }}' };
                if (raw === '') {
                    body.percent_base = null;
                } else {
                    const n = parseFloat(raw.replace(/,/g, ''));
                    body.percent_base = Number.isNaN(n) ? null : n;
                }
                await fetch("{{ route('targets.updatePercentBase') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(body)
                });
            }

            async function syncPercentRowsFromSummaryBox() {
                const total = parseSummaryTotalTarget();
                const csrf = '{{ csrf_token() }}';
                const date = '{{ $selectedDate }}';
                let lastData = null;

                for (const row of document.querySelectorAll('tr[data-line-id]')) {
                    const pctIn = row.querySelector('.daily-field[data-field="target_percent"]');
                    if (!pctIn) {
                        continue;
                    }
                    const raw = String(pctIn.value).trim();
                    if (raw === '') {
                        continue;
                    }
                    const pct = parseFloat(raw);
                    if (Number.isNaN(pct) || pct <= 0) {
                        continue;
                    }

                    const computed = Math.round((pct / 100) * total);
                    const tgtIn = row.querySelector('.daily-field[data-field="target"]');
                    if (tgtIn) {
                        tgtIn.value = String(computed);
                    }

                    const lineId = row.dataset.lineId;
                    const tid = row.dataset.id;

                    if (tid) {
                        const res = await fetch(`{{ url('targets') }}/${tid}/inline-update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({ target_percent: pct, target: computed })
                        });
                        lastData = await res.json();
                        if (lastData.success) {
                            const overCell = document.getElementById('over-' + tid);
                            if (overCell) {
                                overCell.innerText = parseFloat(lastData.over).toFixed(0);
                            }
                        }
                    } else {
                        const res = await fetch("{{ route('targets.dailyUpsert') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                report_line_id: parseInt(lineId, 10),
                                report_date: date,
                                target_percent: pct,
                                target: computed
                            })
                        });
                        lastData = await res.json();
                        if (lastData.success) {
                            row.dataset.id = String(lastData.targetId);
                            row.querySelectorAll('.daily-field').forEach(inp => {
                                inp.setAttribute('data-id', String(lastData.targetId));
                            });
                            const overEl = document.getElementById('over-line-' + lineId);
                            if (overEl) {
                                overEl.id = 'over-' + lastData.targetId;
                                overEl.innerText = parseFloat(lastData.over).toFixed(0);
                            }
                        }
                    }
                }

                for (const row of document.querySelectorAll('tr[data-id]:not([data-line-id])')) {
                    const pctIn = row.querySelector('.update-field[data-field="target_percent"]');
                    if (!pctIn) {
                        continue;
                    }
                    const raw = String(pctIn.value).trim();
                    if (raw === '') {
                        continue;
                    }
                    const pct = parseFloat(raw);
                    if (Number.isNaN(pct) || pct <= 0) {
                        continue;
                    }
                    const computed = Math.round((pct / 100) * total);
                    const tgtIn = row.querySelector('.update-field[data-field="target"]');
                    if (tgtIn) {
                        tgtIn.value = String(computed);
                    }
                    const id = row.dataset.id;
                    const res = await fetch(`{{ url('targets') }}/${id}/inline-update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ target_percent: pct, target: computed })
                    });
                    lastData = await res.json();
                    if (lastData.success) {
                        const overCell = document.getElementById('over-' + id);
                        if (overCell) {
                            overCell.innerText = parseFloat(lastData.over).toFixed(0);
                        }
                    }
                }

                if (lastData && lastData.success) {
                    applyRowTotalsToSummaryUi(lastData);
                }
            }

            const summaryTargetEl = document.getElementById('summary-target');
            let percentBaseSaveTimer = null;
            if (summaryTargetEl) {
                summaryTargetEl.addEventListener('input', function () {
                    clearTimeout(percentBaseSaveTimer);
                    percentBaseSaveTimer = setTimeout(function () {
                        persistPercentBase()
                            .then(function () {
                                refreshSummaryAchFromDom();
                            })
                            .catch(err => console.error('Error:', err));
                    }, 600);
                });
                summaryTargetEl.addEventListener('change', async function () {
                    clearTimeout(percentBaseSaveTimer);
                    try {
                        await persistPercentBase();
                        await syncPercentRowsFromSummaryBox();
                        refreshSummaryAchFromDom();
                    } catch (err) {
                        console.error('Error:', err);
                    }
                });
            }
            const tbody = document.getElementById('sortable-tbody');
            if (tbody) {
                tbody.addEventListener('input', function (e) {
                    const t = e.target;
                    if (t && (t.matches('.daily-field[data-field="target"]') || t.matches('.update-field[data-field="target"]'))) {
                        refreshTableTargetTotalFromInputs();
                    }
                });
                tbody.addEventListener('change', function (e) {
                    const el = e.target;

                    if (el.classList.contains('line-meta-field')) {
                        const row = el.closest('tr');
                        const lineId = row.dataset.lineId;
                        const rodeSel = row.querySelector('.line-meta-field[data-field="rode_id"]');
                        const srSel = row.querySelector('.line-meta-field[data-field="sr_id"]');
                        fetch(`{{ url('targets/report-lines') }}/${lineId}/meta`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                rode_id: rodeSel.value === '' ? null : parseInt(rodeSel.value, 10),
                                sr_id: srSel.value === '' ? null : parseInt(srSel.value, 10),
                                report_date: '{{ $selectedDate }}'
                            })
                        }).catch(err => console.error('Error:', err));
                        return;
                    }

                    if (el.classList.contains('daily-field')) {
                        const row = el.closest('tr');
                        const lineId = row.dataset.lineId;
                        const field = el.dataset.field;
                        const raw = String(el.value).trim();

                        const payload = {
                            report_line_id: parseInt(lineId, 10),
                            report_date: '{{ $selectedDate }}'
                        };

                        if (field === 'target_percent') {
                            const pct = raw === '' ? 0 : parseFloat(raw);
                            const total = parseSummaryTotalTarget();
                            const computed = Math.round(((Number.isNaN(pct) ? 0 : pct) / 100) * total);
                            const targetInput = row.querySelector('.daily-field[data-field="target"]');
                            if (targetInput) {
                                targetInput.value = raw === '' ? '' : String(computed);
                            }
                            payload.target_percent = raw === '' ? 0 : (Number.isNaN(pct) ? 0 : pct);
                            if (raw !== '') {
                                payload.target = computed;
                            }
                        } else if (field === 'target') {
                            const pctInput = row.querySelector('.daily-field[data-field="target_percent"]');
                            if (pctInput) {
                                pctInput.value = '';
                            }
                            payload.target_percent = 0;
                            payload.target = raw === '' ? 0 : (parseFloat(raw.replace(/,/g, '')) || 0);
                        } else {
                            payload[field] = raw === '' ? 0 : (parseFloat(raw.replace(/,/g, '')) || 0);
                        }

                        const tid = row.dataset.id;
                        if (tid) {
                            fetch(`{{ url('targets') }}/${tid}/inline-update`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(payload)
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const overCell = document.getElementById(`over-${tid}`);
                                        if (overCell) {
                                            overCell.innerText = parseFloat(data.over).toFixed(0);
                                        }
                                        applyRowTotalsToSummaryUi(data);
                                    }
                                })
                                .catch(err => console.error('Error:', err));
                        } else {
                            fetch("{{ route('targets.dailyUpsert') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(payload)
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        row.dataset.id = String(data.targetId);
                                        row.querySelectorAll('.daily-field').forEach(inp => {
                                            inp.setAttribute('data-id', String(data.targetId));
                                        });
                                        const overEl = document.getElementById('over-line-' + lineId);
                                        if (overEl) {
                                            overEl.id = 'over-' + data.targetId;
                                            overEl.innerText = parseFloat(data.over).toFixed(0);
                                        }
                                        applyRowTotalsToSummaryUi(data);
                                    }
                                })
                                .catch(err => console.error('Error:', err));
                        }
                        return;
                    }

                    if (el.classList.contains('update-field') && el.dataset.id) {
                        const id = el.dataset.id;
                        const field = el.dataset.field;
                        const row = el.closest('tr');
                        let body = {};

                        if (field === 'target_percent') {
                            const raw = String(el.value).trim();
                            const pct = raw === '' ? 0 : parseFloat(raw);
                            const total = parseSummaryTotalTarget();
                            const computed = Math.round(((Number.isNaN(pct) ? 0 : pct) / 100) * total);
                            const targetInput = row.querySelector('.update-field[data-field="target"]');
                            if (targetInput) {
                                targetInput.value = raw === '' ? '' : String(computed);
                            }
                            body.target_percent = raw === '' ? 0 : (Number.isNaN(pct) ? 0 : pct);
                            if (raw !== '') {
                                body.target = computed;
                            }
                        } else if (field === 'target') {
                            const pctInput = row.querySelector('.update-field[data-field="target_percent"]');
                            if (pctInput) {
                                pctInput.value = '';
                            }
                            body.target_percent = 0;
                            body.target = parseFloat(String(el.value).replace(/,/g, '')) || 0;
                        } else {
                            body[field] = el.value;
                        }

                        fetch(`{{ url('targets') }}/${id}/inline-update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(body)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const achCell = document.getElementById(`ach-${id}`);
                                    if (achCell) {
                                        achCell.innerText = parseFloat(data.ach).toFixed(1) + '%';
                                    }
                                    const overCell = document.getElementById(`over-${id}`);
                                    if (overCell) {
                                        overCell.innerText = parseFloat(data.over).toFixed(0);
                                    }
                                    applyRowTotalsToSummaryUi(data);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            }

            // Add Row logic
            const addRowBtn = document.getElementById('add-row-btn');
            if (addRowBtn) {
                addRowBtn.addEventListener('click', function () {
                    fetch('{{ route("targets.reportLines.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            report_date: '{{ $selectedDate }}'
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            }

            // Daily Cost update logic
            const dailyCostInput = document.getElementById('daily-cost-input');
            if (dailyCostInput) {
                dailyCostInput.addEventListener('change', function () {
                    const newValue = this.value;
                    fetch('{{ route("targets.updateDailyCost") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ daily_cost: newValue, report_date: '{{ $selectedDate }}' })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Daily cost updated');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            }

            async function persistAprPercent() {
                const aprEl = document.getElementById('apr-input');
                const raw = aprEl ? String(aprEl.value).trim() : '';
                const body = { report_date: '{{ $selectedDate }}' };
                if (raw === '') {
                    body.apr_percent = null;
                } else {
                    const n = parseFloat(raw.replace(/,/g, ''));
                    body.apr_percent = Number.isNaN(n) ? null : n;
                }
                await fetch("{{ route('targets.updateAprPercent') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(body)
                });
            }

            function applyAprPercentToRows(aprInputEl) {
                const apr = parseFloat(String(aprInputEl.value).replace(/,/g, '')) || 0;
                let totalCommission = 0;

                document.querySelectorAll('tr[data-id]').forEach(row => {
                    const id = row.getAttribute('data-id');
                    const balanceInput = row.querySelector('[data-field="balance"]');
                    const commInput = row.querySelector('[data-field="commission"]');

                    if (balanceInput && commInput) {
                        const balance = parseFloat(balanceInput.value) || 0;
                        const commission = (balance * apr) / 100;
                        commInput.value = commission.toFixed(0);
                        totalCommission += commission;

                        fetch(`{{ url('targets') }}/${id}/inline-update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                commission: commission
                            })
                        });
                    }
                });

                const summaryComm = document.getElementById('summary-commission');
                if (summaryComm) {
                    summaryComm.value = totalCommission.toFixed(0);
                }
            }

            // Handle APR Calculation
            const aprInput = document.getElementById('apr-input');
            let aprSaveTimer = null;
            if (aprInput) {
                aprInput.addEventListener('input', function () {
                    applyAprPercentToRows(this);
                    clearTimeout(aprSaveTimer);
                    aprSaveTimer = setTimeout(function () {
                        persistAprPercent().catch(err => console.error('Error:', err));
                    }, 600);
                });
                aprInput.addEventListener('change', function () {
                    clearTimeout(aprSaveTimer);
                    persistAprPercent().catch(err => console.error('Error:', err));
                });
            }

            refreshTableTargetTotalFromInputs();
        });
    </script>
@endsection