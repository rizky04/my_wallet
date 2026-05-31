@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')

{{-- App Bar --}}
<div class="app-bar">
    <div style="display:flex;align-items:center;justify-content:space-between">
        <h1 style="font-size:22px;font-weight:700;color:#fff;margin:0">Transaksi</h1>
        <a href="{{ route('transactions.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
            <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="px" style="margin-bottom:12px">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
        <select name="type" class="input-field" style="flex:1;min-width:0;font-size:13px;padding:10px 12px">
            <option value="">Semua Tipe</option>
            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
        </select>
        <select name="category_id" class="input-field" style="flex:1;min-width:0;font-size:13px;padding:10px 12px">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <div style="display:flex;gap:8px;width:100%">
            <select name="wallet_id" class="input-field" style="flex:1;font-size:13px;padding:10px 12px">
                <option value="">Semua Dompet</option>
                @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ request('wallet_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                @endforeach
            </select>
            <button type="submit" style="background:var(--primary);border:none;border-radius:12px;padding:0 16px;color:#fff;font-weight:600;cursor:pointer">
                <span class="material-icons-round" style="font-size:18px;display:block">search</span>
            </button>
            <a href="{{ route('transactions.index') }}" style="background:var(--card);border:0.5px solid var(--border);border-radius:12px;padding:0 12px;display:flex;align-items:center;color:var(--t2)">
                <span class="material-icons-round" style="font-size:18px">close</span>
            </a>
        </div>
    </form>
</div>

{{-- List --}}
@if($transactions->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">receipt_long</span>
        <p style="font-size:15px;font-weight:600;margin:0 0 4px">Belum ada transaksi</p>
        <p style="font-size:13px;margin:0 0 20px;color:var(--t3)">Mulai catat keuangan kamu</p>
        <a href="{{ route('transactions.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px">+ Tambah Transaksi</a>
    </div>
@else
    <div class="px">
        <div class="section-card">
            @foreach($transactions as $i => $tx)
                @if($i > 0)<div class="divider"></div>@endif
                @php $inc = $tx->type === 'income'; $cc = $tx->category?->color ?? '#8E8E93'; @endphp
                <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                    <div class="icon-badge" style="background:{{ $cc }}22;width:42px;height:42px;border-radius:14px">
                        <span class="material-icons-round" style="color:{{ $cc }}">{{ $tx->category?->icon ?? 'category' }}</span>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:14px;font-weight:600;color:#fff;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $tx->category?->name ?? 'Kategori' }}</p>
                        <p style="font-size:12px;color:var(--t2);margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $tx->wallet?->name }} · {{ $tx->note ?: $tx->date->isoFormat('HH:mm') }}</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-size:14px;font-weight:700;margin:0;color:{{ $inc ? 'var(--success)' : 'var(--error)' }}">
                            {{ $inc ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                        <p style="font-size:11px;color:var(--t3);margin:0">{{ $tx->date->isoFormat('D MMM YYYY') }}</p>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:4px;margin-left:4px">
                        <a href="{{ route('transactions.edit', $tx) }}" style="color:var(--t3)">
                            <span class="material-icons-round" style="font-size:16px">edit</span>
                        </a>
                        <form method="POST" action="{{ route('transactions.destroy', $tx) }}" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;color:var(--t3)">
                                <span class="material-icons-round" style="font-size:16px">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:16px">{{ $transactions->links() }}</div>
    </div>
@endif

@endsection
