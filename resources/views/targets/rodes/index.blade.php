@extends('layouts.app')

@section('title', 'Rode List')

@section('content')
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;margin-bottom:8px;">
        <div>
            <h1 class="page-title" style="margin-bottom:0;">Rode List</h1>
            <p class="page-sub" style="margin-bottom:0;">All route codes used in targets and reports</p>
        </div>
        <a href="{{ route('targets.createRode') }}" class="btn-action">Add Rode</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rode</th>
                    <th style="width:120px;text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rodes as $index => $rode)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rode->name }}</td>
                        <td style="text-align:right;">
                            <form action="{{ route('targets.rodes.destroy', $rode) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Delete rode {{ $rode->name }}? It will be removed from dropdowns; saved text on past dates is kept.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#94a3b8;padding:32px;">No rodes yet. <a href="{{ route('targets.createRode') }}" style="color:#4f46e5;font-weight:600;">Add one</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
