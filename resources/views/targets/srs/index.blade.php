@extends('layouts.app')

@section('title', 'SR List')

@section('content')
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;margin-bottom:8px;">
        <div>
            <h1 class="page-title" style="margin-bottom:0;">SR List</h1>
            <p class="page-sub" style="margin-bottom:0;">All sales representatives used in targets and reports</p>
        </div>
        <a href="{{ route('targets.createSR') }}" class="btn-action">Add SR</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th style="width:120px;text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($srs as $index => $sr)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sr->name }}</td>
                        <td style="text-align:right;">
                            <form action="{{ route('targets.srs.destroy', $sr) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Delete SR {{ $sr->name }}? It will be removed from dropdowns; saved text on past dates is kept.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#94a3b8;padding:32px;">No SRs yet. <a href="{{ route('targets.createSR') }}" style="color:#4f46e5;font-weight:600;">Add one</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
