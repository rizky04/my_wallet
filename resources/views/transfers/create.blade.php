@extends('layouts.app')
@section('title', 'Transfer')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('wallets.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Transfer Dompet</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('transfers.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf
        <div>
            <label class="label">Dari Dompet</label>
            <select name="from_wallet_id" class="input-field" required>
                <option value="">Pilih dompet asal</option>
                @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ old('from_wallet_id')==$w->id?'selected':'' }}>
                        {{ $w->name }} (Rp {{ number_format($w->balance,0,',','.') }})
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;justify-content:center">
            <div style="width:40px;height:40px;background:var(--primary-dim);border-radius:50%;display:flex;align-items:center;justify-content:center">
                <span class="material-icons-round" style="color:var(--primary)">arrow_downward</span>
            </div>
        </div>
        <div>
            <label class="label">Ke Dompet</label>
            <select name="to_wallet_id" class="input-field" required>
                <option value="">Pilih dompet tujuan</option>
                @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ old('to_wallet_id')==$w->id?'selected':'' }}>{{ $w->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Nominal Transfer</label>
            <input type="number" name="amount" value="{{ old('amount') }}" class="input-field"
                style="font-size:24px;font-weight:700" placeholder="0" min="0" step="1000" required>
        </div>
        <div>
            <label class="label">Biaya Transfer (opsional)</label>
            <input type="number" name="fee" value="{{ old('fee',0) }}" class="input-field" min="0" step="500">
        </div>
        <div>
            <label class="label">Tanggal & Waktu</label>
            <input type="datetime-local" name="date" value="{{ old('date',now()->format('Y-m-d\TH:i')) }}" class="input-field" required>
        </div>
        <div>
            <label class="label">Catatan (opsional)</label>
            <input type="text" name="note" value="{{ old('note') }}" class="input-field" placeholder="Catatan...">
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('wallets.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Transfer</button>
        </div>
    </form>
</div>
@endsection
