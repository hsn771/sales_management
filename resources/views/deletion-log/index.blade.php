@extends('layouts.app')

@section('title', 'Deletion Log')

@section('content')
    <div>
        <h1 class="page-title" style="margin-bottom:0;">Deletion Log</h1>
        <p class="page-sub" style="margin-bottom:0;">History of deleted targets, rodes, SRs, and report rows</p>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>Type</th>
                    <th>What was deleted</th>
                    <th>By</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="white-space:nowrap;">{{ $log->deleted_at?->format('M j, Y g:i A') ?? '—' }}</td>
                        <td>
                            <span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:.7rem;font-weight:700;background:#f1f4ff;color:#4f46e5;">
                                {{ $log->entityTypeLabel() }}
                            </span>
                        </td>
                        <td>{{ $log->summary }}</td>
                        <td>{{ $log->deleted_by ?? '—' }}</td>
                        <td style="max-width:280px;font-size:.78rem;color:#64748b;">
                            @if(!empty($log->details))
                                @foreach($log->details as $key => $value)
                                    @if($value !== null && $value !== '')
                                        <div><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</div>
                                    @endif
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#94a3b8;padding:32px;">No deletions recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div style="margin-top:20px;display:flex;justify-content:center;gap:8px;flex-wrap:wrap;">
            @if($logs->onFirstPage())
                <span style="padding:8px 14px;border-radius:8px;font-size:.82rem;color:#94a3b8;border:1px solid #e2e8f0;">Previous</span>
            @else
                <a href="{{ $logs->previousPageUrl() }}" style="padding:8px 14px;border-radius:8px;font-size:.82rem;color:#4f46e5;border:1px solid #c7d2fe;text-decoration:none;font-weight:600;">Previous</a>
            @endif
            <span style="padding:8px 14px;font-size:.82rem;color:#64748b;">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
            @if($logs->hasMorePages())
                <a href="{{ $logs->nextPageUrl() }}" style="padding:8px 14px;border-radius:8px;font-size:.82rem;color:#4f46e5;border:1px solid #c7d2fe;text-decoration:none;font-weight:600;">Next</a>
            @else
                <span style="padding:8px 14px;border-radius:8px;font-size:.82rem;color:#94a3b8;border:1px solid #e2e8f0;">Next</span>
            @endif
        </div>
    @endif
@endsection
