@extends('layouts.app')
@section('title', 'Hutang & Piutang')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('settings.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Hutang & Piutang</h1>
    <a href="{{ route('debts.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

<div class="px" style="display:flex;gap:10px;margin-bottom:20px">
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Total Hutang</p>
        <p style="font-size:16px;font-weight:700;color:var(--error);margin:0">Rp {{ number_format($totalDebt,0,',','.') }}</p>
    </div>
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Total Piutang</p>
        <p style="font-size:16px;font-weight:700;color:var(--success);margin:0">Rp {{ number_format($totalLoan,0,',','.') }}</p>
    </div>
</div>

@if($debts->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">handshake</span>
        <p style="font-size:15px;font-weight:600;margin:0">Belum ada catatan</p>
        <a href="{{ route('debts.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px;display:inline-block;margin-top:12px">+ Tambah</a>
    </div>
@else
    <div class="px" style="display:flex;flex-direction:column;gap:10px">
        @foreach($debts as $debt)
            @php
                $isDebt = $debt->type === 'debt';
                $c = $isDebt ? 'var(--error)' : 'var(--success)';
                $bg = $isDebt ? 'rgba(255,69,58,0.12)' : 'rgba(48,209,88,0.12)';
                $overdue = $debt->due_date && $debt->due_date->isPast() && $debt->status==='active';
                $paid = $debt->status === 'paid';
            @endphp
            <div class="card" style="padding:16px;{{ $paid?'opacity:0.6':'' }}">
                <div style="display:flex;align-items:flex-start;gap:12px">
                    <div class="icon-badge" style="background:{{ $bg }};width:44px;height:44px;border-radius:14px">
                        <span class="material-icons-round" style="color:{{ $c }}">{{ $isDebt?'arrow_upward':'arrow_downward' }}</span>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:2px">
                            <span style="font-size:14px;font-weight:600;color:#fff">{{ $debt->person_name }}</span>
                            <span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:20px;background:{{ $bg }};color:{{ $c }}">
                                {{ $isDebt?'Hutang':'Piutang' }}
                            </span>
                            @if($paid)
                                <span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:20px;background:rgba(48,209,88,0.12);color:var(--success)">Lunas</span>
                            @elseif($overdue)
                                <span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:20px;background:rgba(255,69,58,0.15);color:var(--error)">Jatuh Tempo!</span>
                            @endif
                        </div>
                        @if($debt->note)
                            <p style="font-size:12px;color:var(--t2);margin:0">{{ $debt->note }}</p>
                        @endif
                        @if($debt->due_date)
                            <p style="font-size:11px;color:{{ $overdue?'var(--error)':'var(--t3)' }};margin:2px 0 0">Jatuh tempo: {{ $debt->due_date->isoFormat('D MMM YYYY') }}</p>
                        @endif
                        @if(!$paid && $debt->paid_amount > 0)
                            <div class="progress-bar" style="margin-top:8px">
                                <div class="progress-fill" style="width:{{ $debt->progress_percent }}%;background:{{ $c }}"></div>
                            </div>
                            <p style="font-size:11px;color:var(--t3);margin:4px 0 0">Terbayar Rp {{ number_format($debt->paid_amount,0,',','.') }}</p>
                        @endif
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-size:15px;font-weight:700;color:{{ $c }};margin:0">Rp {{ number_format($debt->remaining_amount,0,',','.') }}</p>
                        <p style="font-size:11px;color:var(--t3);margin:2px 0 0">dari Rp {{ number_format($debt->amount,0,',','.') }}</p>
                        <div style="display:flex;gap:4px;justify-content:flex-end;margin-top:8px">
                            <a href="{{ route('debts.edit', $debt) }}" style="width:28px;height:28px;background:var(--surface);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                                <span class="material-icons-round" style="font-size:14px">edit</span>
                            </a>
                            <form method="POST" action="{{ route('debts.destroy', $debt) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:28px;height:28px;background:var(--surface);border:none;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                                    <span class="material-icons-round" style="font-size:14px">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
