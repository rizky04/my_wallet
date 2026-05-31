@extends('layouts.app')
@section('title', 'Target Tabungan')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('settings.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Target Tabungan</h1>
    <a href="{{ route('goals.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

<div class="px" style="display:flex;gap:10px;margin-bottom:20px">
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Total Target</p>
        <p style="font-size:15px;font-weight:700;color:#fff;margin:0">Rp {{ number_format($totalTarget,0,',','.') }}</p>
    </div>
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Terkumpul</p>
        <p style="font-size:15px;font-weight:700;color:var(--primary);margin:0">Rp {{ number_format($totalSaved,0,',','.') }}</p>
    </div>
</div>

@if($goals->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">savings</span>
        <p style="font-size:15px;font-weight:600;margin:0">Belum ada target</p>
        <a href="{{ route('goals.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px;display:inline-block;margin-top:12px">+ Buat Target</a>
    </div>
@else
    <div class="px" style="display:flex;flex-direction:column;gap:10px">
        @foreach($goals as $goal)
            @php $done = $goal->progress_percent >= 100; $barC = $done ? 'var(--success)' : $goal->color; @endphp
            <div class="card" style="padding:16px">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                    <div class="icon-badge" style="background:{{ $goal->color }}22;width:48px;height:48px;border-radius:16px">
                        <span class="material-icons-round" style="color:{{ $goal->color }};font-size:22px">{{ $goal->icon }}</span>
                    </div>
                    <div style="flex:1">
                        <p style="font-size:14px;font-weight:600;color:#fff;margin:0">{{ $goal->goal_name }}</p>
                        @if($goal->deadline)
                            <p style="font-size:12px;color:var(--t3);margin:2px 0 0">Target: {{ $goal->deadline->isoFormat('D MMM YYYY') }}</p>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div style="display:flex;gap:4px">
                            <a href="{{ route('goals.edit', $goal) }}" style="width:28px;height:28px;background:var(--surface);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                                <span class="material-icons-round" style="font-size:14px">edit</span>
                            </a>
                            <form method="POST" action="{{ route('goals.destroy', $goal) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:28px;height:28px;background:var(--surface);border:none;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                                    <span class="material-icons-round" style="font-size:14px">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:13px;font-weight:700;color:#fff">Rp {{ number_format($goal->current_amount,0,',','.') }}</span>
                    <span style="font-size:12px;color:var(--t2)">dari Rp {{ number_format($goal->target_amount,0,',','.') }}</span>
                </div>
                <div class="progress-bar" style="margin-bottom:6px">
                    <div class="progress-fill" style="width:{{ $goal->progress_percent }}%;background:{{ $barC }}"></div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:12px;font-weight:600;color:{{ $barC }}">
                        {{ number_format($goal->progress_percent,0) }}%
                        @if($done) · Tercapai! 🎉 @endif
                    </span>
                    @if(!$done)
                        <span style="font-size:11px;color:var(--t3)">Kurang Rp {{ number_format($goal->remaining_amount,0,',','.') }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
