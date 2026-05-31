@extends('layouts.app')
@section('title', 'Edit Anggaran')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('budgets.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Edit Anggaran</h1>
</div>

<div class="px" style="padding-top:8px">
    <div style="background:var(--surface);border-radius:14px;padding:14px;display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <div class="icon-badge" style="background:{{ $budget->category->color }}22">
            <span class="material-icons-round" style="color:{{ $budget->category->color }}">{{ $budget->category->icon }}</span>
        </div>
        <div>
            <p style="font-size:14px;font-weight:600;color:#fff;margin:0">{{ $budget->category->name }}</p>
            <p style="font-size:12px;color:var(--t3);margin:0">{{ \Carbon\Carbon::create()->month($budget->month)->translatedFormat('F') }} {{ $budget->year }}</p>
        </div>
    </div>
    <form method="POST" action="{{ route('budgets.update', $budget) }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf @method('PUT')
        <div>
            <label class="label">Batas Anggaran Baru</label>
            <input type="number" name="amount_limit" value="{{ old('amount_limit',$budget->amount_limit) }}"
                class="input-field" style="font-size:22px;font-weight:700" min="1" step="10000" required>
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('budgets.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>
@endsection
