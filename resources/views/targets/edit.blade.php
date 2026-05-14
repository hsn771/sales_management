@extends('layouts.app')

@section('title', 'Edit Target')

@section('content')
    <a href="{{ route('targets.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-size: .88rem; margin-bottom: 20px;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to List
    </a>

    <h1 class="page-title">Edit Target: {{ $target->name }}</h1>
    <p class="page-sub">Update the details for this sales target</p>

    <div class="form-card">
        <form action="{{ route('targets.update', $target) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Rode</label>
                    <input type="text" name="rode" class="form-input" required value="{{ old('rode', $target->rode) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required value="{{ old('name', $target->name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Target</label>
                    <input type="number" step="0.01" name="target" class="form-input" required value="{{ old('target', $target->target) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Balance</label>
                    <input type="number" step="0.01" name="balance" class="form-input" required value="{{ old('balance', $target->balance) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Commission</label>
                    <input type="number" step="0.01" name="commission" class="form-input" required value="{{ old('commission', $target->commission) }}">
                </div>
            </div>

            <div style="margin-top: 32px;">
                <button type="submit" class="btn-action">Update Target</button>
                <a href="{{ route('targets.index') }}" style="margin-left: 16px; color: #64748b; text-decoration: none; font-size: .88rem; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
