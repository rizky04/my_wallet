@extends('layouts.app')
@section('title', 'Dompet')

@section('content')

<div class="app-bar">
    <div style="display:flex;align-items:center;justify-content:space-between">
        <h1 style="font-size:22px;font-weight:700;color:#fff;margin:0">Dompet Saya</h1>
        <a href="{{ route('wallets.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
            <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
        </a>
    </div>
</div>

{{-- Total --}}
<div class="px" style="margin-bottom:20px">
    <div class="card" style="padding:20px;text-align:center">
        <p style="font-size:12px;color:var(--t2);margin:0 0 4px">Total Semua Dompet</p>
        <p style="font-size:28px;font-weight:700;color:#fff;margin:0">Rp {{ number_format($totalBalance,0,',','.') }}</p>
    </div>
</div>

{{-- Transfer Button --}}
@if($wallets->count() >= 2)
<div class="px" style="margin-bottom:16px">
    <a href="{{ route('transfers.create') }}" style="background:var(--card);border:0.5px solid var(--border);border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:12px">
        <div class="icon-badge" style="background:rgba(10,132,255,0.15)">
            <span class="material-icons-round" style="color:var(--blue)">swap_horiz</span>
        </div>
        <div style="flex:1">
            <p style="font-size:14px;font-weight:600;color:#fff;margin:0">Transfer Antar Dompet</p>
            <p style="font-size:12px;color:var(--t2);margin:0">Pindah saldo tanpa biaya</p>
        </div>
        <span class="material-icons-round chevron">chevron_right</span>
    </a>
</div>
@endif

{{-- Wallets --}}
@if($wallets->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">account_balance_wallet</span>
        <p style="font-size:15px;font-weight:600;margin:0 0 4px">Belum ada dompet</p>
        <a href="{{ route('wallets.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px;display:inline-block;margin-top:12px">+ Tambah Dompet</a>
    </div>
@else
    <div class="px">
        <div class="section-card">
            @foreach($wallets as $i => $wallet)
                @if($i > 0)<div class="divider"></div>@endif
                <div style="padding:16px;display:flex;align-items:center;gap:12px">
                    <div class="icon-badge" style="background:{{ $wallet->color }}22;width:44px;height:44px;border-radius:14px">
                        <span class="material-icons-round" style="color:{{ $wallet->color }};font-size:20px">{{ $wallet->icon }}</span>
                    </div>
                    <div style="flex:1">
                        <p style="font-size:14px;font-weight:600;color:#fff;margin:0">{{ $wallet->name }}</p>
                        <p style="font-size:18px;font-weight:700;color:#fff;margin:2px 0 0">Rp {{ number_format($wallet->balance,0,',','.') }}</p>
                    </div>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('wallets.edit', $wallet) }}" style="width:32px;height:32px;background:var(--surface);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                            <span class="material-icons-round" style="font-size:16px">edit</span>
                        </a>
                        <form method="POST" action="{{ route('wallets.destroy', $wallet) }}" onsubmit="return confirm('Hapus {{ $wallet->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:32px;height:32px;background:var(--surface);border:none;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                                <span class="material-icons-round" style="font-size:16px">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@endsection
