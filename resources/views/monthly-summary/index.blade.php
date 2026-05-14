@extends('layouts.app')

@section('title', 'Monthly Summary')

@section('styles')
    <style>
        .ms-page-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 14px;
        }
        .ms-page-toolbar label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }
        .ms-month-input {
            padding: 6px 10px;
            border: 1px solid #94a3b8;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            background: #fff;
        }

        .ms-btn-print {
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

        .ms-btn-print:hover {
            opacity: 0.92;
        }

        .ms-btn-print svg {
            flex-shrink: 0;
        }

        @media print {
            .sidebar,
            .topbar,
            .no-print {
                display: none !important;
            }

            .main-wrap {
                margin-left: 0 !important;
            }

            body {
                background: #fff !important;
            }

            .content {
                padding: 10px !important;
            }

            .ms-sheet {
                border: 1px solid #000;
                break-inside: avoid;
            }
        }

        .ms-sheet {
            background: #fff;
            border: 1px solid #000;
            max-width: 100%;
            overflow-x: auto;
        }

        .ms-title {
            text-align: center;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 10px 8px 12px;
            border-bottom: 2px solid #000;
            letter-spacing: 0.02em;
        }

        .ms-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 0.78rem;
            color: #000;
        }

        .ms-table th,
        .ms-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            line-height: 1.25;
        }

        .ms-table thead th {
            font-weight: 700;
            text-align: center;
            background: #fff;
            border-bottom: 2px solid #000;
        }

        .ms-table tbody td {
            text-align: center;
        }

        .ms-table .ms-col-date {
            width: 42px;
            text-align: center;
        }

        .ms-table .ms-num {
            text-align: center;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .ms-table tfoot td {
            font-weight: 700;
            text-align: center;
            background: #fff;
        }

        .ms-table tfoot .ms-num {
            font-weight: 700;
        }

        .ms-table tfoot tr.ms-grand td {
            font-weight: 700;
            background: #fff;
        }

        .ms-table tfoot tr.ms-grand .ms-num.ms-grand-val {
            text-align: center;
        }

        @media (max-width: 900px) {
            .ms-page-toolbar {
                flex-direction: column;
                align-items: stretch;
                justify-content: flex-start;
            }

            .ms-page-toolbar label {
                margin-top: 4px;
            }

            .ms-month-input {
                width: 100%;
                max-width: 100%;
            }

            .ms-btn-print {
                width: 100%;
                justify-content: center;
            }

            .ms-sheet {
                border-radius: 10px;
            }

            .ms-title {
                font-size: 0.95rem;
                padding: 10px 8px;
            }

            .ms-table {
                font-size: 0.7rem;
            }

            .ms-table th,
            .ms-table td {
                padding: 3px 4px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ms-page-toolbar no-print">
        <button type="button" class="ms-btn-print" id="ms-print-btn" title="Print this summary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print
        </button>
        <label for="month-picker">Month</label>
        <input type="month" id="month-picker" class="ms-month-input" value="{{ $monthParam }}"
            max="2099-12">
    </div>

    <div class="ms-sheet">
        <div class="ms-title">Monthly Summary({{ $titleMonth }})</div>
        <table class="ms-table">
            <thead>
                <tr>
                    <th class="ms-col-date">Date</th>
                    <th>Total Target</th>
                    <th>Yes/No</th>
                    <th>Target Balance</th>
                    <th>Net Cost</th>
                    <th>Net Profit</th>
                    <th>Net loss</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                    <tr>
                        <td class="ms-col-date">{{ $r['date']->format('j') }}</td>
                        @if($r['total_target'] !== null)
                            <td class="ms-num">{{ number_format($r['total_target'], 0, '.', ',') }}</td>
                            <td>{{-- Yes/No: temporarily blank --}}</td>
                            <td class="ms-num">{{ number_format($r['target_balance'], 0, '.', ',') }}</td>
                            <td class="ms-num">{{ number_format($r['net_cost'], 0, '.', ',') }}</td>
                            <td class="ms-num">{{ $r['net_profit'] !== null ? number_format($r['net_profit'], 0, '.', ',') : '' }}</td>
                            <td class="ms-num">{{ $r['net_loss'] !== null ? number_format($r['net_loss'], 0, '.', ',') : '' }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="ms-totals">
                    <td class="ms-col-date"></td>
                    <td class="ms-num">{{ number_format($totals['total_target'], 0, '.', ',') }}</td>
                    <td></td>
                    <td class="ms-num">{{ number_format($totals['target_balance'], 0, '.', ',') }}</td>
                    <td class="ms-num">{{ number_format($totals['net_cost'], 0, '.', ',') }}</td>
                    <td class="ms-num">{{ number_format($totals['net_profit'], 0, '.', ',') }}</td>
                    <td class="ms-num">{{ number_format($totals['net_loss'], 0, '.', ',') }}</td>
                </tr>
                <tr class="ms-grand">
                    <td class="ms-col-date"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td class="ms-num ms-grand-val">{{ number_format($grandNet, 0, '.', ',') }}/=</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('month-picker').addEventListener('change', function () {
            if (!this.value) return;
            window.location.href = "{{ route('monthly-summary.index') }}?month=" + encodeURIComponent(this.value);
        });

        document.getElementById('ms-print-btn').addEventListener('click', function () {
            window.print();
        });
    </script>
@endsection
