@extends('layouts.app')
@section('title', 'Riwayat Transfer')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('wallets.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Riwayat Transfer</h1>
    <a href="{{ route('transfers.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

<div class="px">
    @if($transfers->isEmpty())
        <div style="padding:60px 0;text-align:center;color:var(--t3)">
            <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">swap_horiz</span>
            <p style="font-size:15px;font-weight:600;margin:0">Belum ada transfer</p>
        </div>
    @else
        <div class="section-card">
            @foreach($transfers as $i => $t)
                @if($i > 0)<div class="divider"></div>@endif
                <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                    <div class="icon-badge" style="background:rgba(10,132,255,0.12);width:42px;height:42px;border-radius:14px">
                        <span class="material-icons-round" style="color:var(--blue)">swap_horiz</span>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:13px;font-weight:600;color:#fff;margin:0">{{ $t->fromWallet?->name }} → {{ $t->toWallet?->name }}</p>
                        <p style="font-size:12px;color:var(--t2);margin:0">{{ $t->date->isoFormat('D MMM YYYY · HH:mm') }}</p>
                    </div>
                    <div style="text-align:right">
                        <p style="font-size:14px;font-weight:700;color:#fff;margin:0">Rp {{ number_format($t->amount,0,',','.') }}</p>
                        @if($t->fee > 0)
                            <p style="font-size:11px;color:var(--t3);margin:0">Fee: Rp {{ number_format($t->fee,0,',','.') }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('transfers.destroy', $t) }}" onsubmit="return confirm('Batalkan transfer?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--t3);padding:4px">
                            <span class="material-icons-round" style="font-size:16px">delete</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div style="margin-top:16px">{{ $transfers->links() }}</div>
    @endif
</div>
@endsection
