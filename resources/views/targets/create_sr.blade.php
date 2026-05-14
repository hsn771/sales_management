@extends('layouts.app')

@section('title', 'Add New SR')

@section('content')
    <a href="{{ route('targets.index') }}" class="btn-action" style="background: #fff; color: #64748b; border: 1px solid #e2e8f0; box-shadow: none; margin-bottom: 24px;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to List
    </a>

    <h1 class="page-title">Add New SR</h1>
    <p class="page-sub">Enter the name and route for the new Sales Representative</p>

    <div class="form-card">
        <form action="{{ route('targets.storeSR') }}" method="POST">
            @csrf
            <div class="form-grid" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label class="form-label">SR Name</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. John Doe" required value="{{ old('name') }}">
                </div>
                {{-- Hidden fields with defaults --}}
                <input type="hidden" name="rode" value="New">
                <input type="hidden" name="target" value="0">
                <input type="hidden" name="balance" value="0">
                <input type="hidden" name="commission" value="0">
            </div>

            <div style="margin-top: 32px;">
                <button type="submit" class="btn-action">Create SR</button>
                <a href="{{ route('targets.index') }}" style="margin-left: 16px; color: #64748b; text-decoration: none; font-size: .88rem; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
