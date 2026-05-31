@extends('layouts.app')
@section('title', 'Anggaran')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('settings.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Anggaran Bulanan</h1>
    <a href="{{ route('budgets.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

<div class="px" style="margin-bottom:16px">
    <form method="GET" style="display:flex;gap:8px">
        <select name="month" class="input-field" style="flex:1;font-size:13px;padding:10px 12px">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
        <select name="year" class="input-field" style="width:90px;font-size:13px;padding:10px 12px">
            @foreach(range(now()->year-1,now()->year+1) as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" style="background:var(--primary);border:none;border-radius:12px;padding:0 14px;color:#fff;font-weight:600;font-size:13px;cursor:pointer">OK</button>
    </form>
</div>

@if($budgets->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">pie_chart</span>
        <p style="font-size:15px;font-weight:600;margin:0 0 4px">Belum ada anggaran</p>
        <a href="{{ route('budgets.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px;display:inline-block;margin-top:12px">+ Tambah Anggaran</a>
    </div>
@else
    <div class="px" style="display:flex;flex-direction:column;gap:10px">
        @foreach($budgets as $budget)
            @php $bc = $budget->progress>=100?'var(--error)':($budget->progress>=75?'var(--warning)':'var(--success)'); @endphp
            <div class="card" style="padding:16px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="icon-badge" style="background:{{ $budget->category->color }}22">
                            <span class="material-icons-round" style="color:{{ $budget->category->color }}">{{ $budget->category->icon }}</span>
                        </div>
                        <div>
                            <p style="font-size:14px;font-weight:600;color:#fff;margin:0">{{ $budget->category->name }}</p>
                            <p style="font-size:11px;color:var(--t3);margin:0">{{ number_format($budget->progress,0) }}% terpakai</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('budgets.edit', $budget) }}" style="width:30px;height:30px;background:var(--surface);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                            <span class="material-icons-round" style="font-size:15px">edit</span>
                        </a>
                        <form method="POST" action="{{ route('budgets.destroy', $budget) }}" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:30px;height:30px;background:var(--surface);border:none;border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                                <span class="material-icons-round" style="font-size:15px">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="progress-bar" style="margin-bottom:8px">
                    <div class="progress-fill" style="width:{{ $budget->progress }}%;background:{{ $bc }}"></div>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="font-size:12px;font-weight:600;color:{{ $bc }}">Rp {{ number_format($budget->spent,0,',','.') }}</span>
                    <span style="font-size:12px;color:var(--t2)">/ Rp {{ number_format($budget->amount_limit,0,',','.') }}</span>
                </div>
                @if($budget->progress >= 100)
                    <p style="font-size:11px;color:var(--error);margin:6px 0 0;font-weight:600">⚠ Anggaran melebihi batas!</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@endsection
