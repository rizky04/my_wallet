@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- App Bar --}}
<div class="app-bar">
    <div style="display:flex;align-items:center;justify-content:space-between">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#fff;flex-shrink:0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p style="font-size:11px;color:var(--t2);margin:0">
                    @php $h = now()->hour; echo $h < 12 ? 'Selamat Pagi' : ($h < 17 ? 'Selamat Siang' : 'Selamat Malam'); @endphp
                </p>
                <p style="font-size:15px;font-weight:600;color:#fff;margin:0">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <a href="{{ route('transfers.create') }}" style="width:38px;height:38px;background:var(--card);border:0.5px solid var(--border);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
            <span class="material-icons-round" style="font-size:20px">swap_horiz</span>
        </a>
    </div>
</div>

{{-- Balance Card --}}
<div class="px" style="margin-top:4px">
    <div style="background:linear-gradient(135deg,#1E1E1E,#181818);border:0.5px solid var(--border);border-radius:24px;padding:24px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
            <span style="font-size:13px;color:var(--t2)">Saldo Total</span>
            <span class="material-icons-round" style="font-size:18px;color:var(--t3)">visibility</span>
        </div>
        <p style="font-size:30px;font-weight:700;color:#fff;margin:0 0 20px;letter-spacing:-0.5px">
            Rp {{ number_format($totalBalance, 0, ',', '.') }}
        </p>
        <div style="height:0.5px;background:var(--border);margin-bottom:16px"></div>
        <div style="display:flex;gap:0">
            <div style="flex:1;display:flex;align-items:center;gap:10px;padding-right:16px">
                <div class="icon-badge" style="background:rgba(48,209,88,0.12)">
                    <span class="material-icons-round" style="color:var(--success)">arrow_downward</span>
                </div>
                <div>
                    <p style="font-size:10px;color:var(--t2);margin:0">Pemasukan</p>
                    <p style="font-size:12px;font-weight:700;color:var(--success);margin:0">Rp {{ number_format($monthIncome, 0, ',', '.') }}</p>
                </div>
            </div>
            <div style="width:0.5px;background:var(--border)"></div>
            <div style="flex:1;display:flex;align-items:center;gap:10px;padding-left:16px">
                <div class="icon-badge" style="background:rgba(255,69,58,0.12)">
                    <span class="material-icons-round" style="color:var(--error)">arrow_upward</span>
                </div>
                <div>
                    <p style="font-size:10px;color:var(--t2);margin:0">Pengeluaran</p>
                    <p style="font-size:12px;font-weight:700;color:var(--error);margin:0">Rp {{ number_format($monthExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="px" style="margin-top:20px;display:flex;gap:10px">
    <a href="{{ route('transactions.create') }}" style="flex:2;background:var(--primary);border-radius:16px;padding:14px;display:flex;flex-direction:column;align-items:center;gap:4px">
        <span class="material-icons-round" style="color:#fff;font-size:22px">add</span>
        <span style="font-size:12px;font-weight:600;color:#fff">Tambah</span>
    </a>
    <a href="{{ route('transfers.create') }}" class="card" style="flex:1;border-radius:16px;padding:14px;display:flex;flex-direction:column;align-items:center;gap:4px">
        <span class="material-icons-round" style="color:var(--t2);font-size:22px">swap_horiz</span>
        <span style="font-size:12px;font-weight:600;color:var(--t2)">Transfer</span>
    </a>
    <a href="{{ route('reports.index') }}" class="card" style="flex:1;border-radius:16px;padding:14px;display:flex;flex-direction:column;align-items:center;gap:4px">
        <span class="material-icons-round" style="color:var(--t2);font-size:22px">bar_chart</span>
        <span style="font-size:12px;font-weight:600;color:var(--t2)">Laporan</span>
    </a>
</div>

{{-- Bulan Ini --}}
<div class="px" style="margin-top:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <span style="font-size:16px;font-weight:700;color:#fff">{{ now()->isoFormat('MMMM YYYY') }}</span>
        <span style="font-size:11px;color:var(--t2);background:var(--card);border:0.5px solid var(--border);padding:3px 10px;border-radius:8px">Bulan ini</span>
    </div>
    <div style="display:flex;gap:12px">
        <div class="card" style="flex:1;padding:16px">
            <div style="display:flex;align-items:center;gap:5px;margin-bottom:10px">
                <span class="material-icons-round" style="font-size:14px;color:var(--success)">trending_up</span>
                <span style="font-size:11px;color:var(--t2)">Uang Masuk</span>
            </div>
            <p style="font-size:15px;font-weight:700;color:var(--success);margin:0">Rp {{ number_format($monthIncome, 0, ',', '.') }}</p>
        </div>
        <div class="card" style="flex:1;padding:16px">
            <div style="display:flex;align-items:center;gap:5px;margin-bottom:10px">
                <span class="material-icons-round" style="font-size:14px;color:var(--error)">trending_down</span>
                <span style="font-size:11px;color:var(--t2)">Uang Keluar</span>
            </div>
            <p style="font-size:15px;font-weight:700;color:var(--error);margin:0">Rp {{ number_format($monthExpense, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Dompet --}}
@if($wallets->isNotEmpty())
<div class="px" style="margin-top:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <span style="font-size:16px;font-weight:700;color:#fff">Dompet Saya</span>
        <a href="{{ route('wallets.index') }}" style="font-size:12px;color:var(--primary);font-weight:600">Lihat Semua</a>
    </div>
    <div class="section-card">
        @foreach($wallets->take(3) as $i => $wallet)
            @if($i > 0)<div class="divider"></div>@endif
            <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                <div class="icon-badge" style="background:{{ $wallet->color }}22">
                    <span class="material-icons-round" style="color:{{ $wallet->color }}">{{ $wallet->icon }}</span>
                </div>
                <span style="flex:1;font-size:14px;font-weight:500;color:#fff">{{ $wallet->name }}</span>
                <span style="font-size:14px;font-weight:700;color:#fff">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Anggaran --}}
@if($budgets->isNotEmpty())
<div class="px" style="margin-top:24px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <span style="font-size:16px;font-weight:700;color:#fff">Anggaran Bulan Ini</span>
        <a href="{{ route('budgets.index') }}" style="font-size:12px;color:var(--primary);font-weight:600">Kelola</a>
    </div>
    <div class="section-card">
        @foreach($budgets->take(3) as $i => $budget)
            @if($i > 0)<div class="divider"></div>@endif
            @php $bc = $budget->progress >= 100 ? 'var(--error)' : ($budget->progress >= 75 ? 'var(--warning)' : 'var(--success)'); @endphp
            <div style="padding:14px 16px">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="material-icons-round" style="font-size:16px;color:{{ $budget->category->color }}">{{ $budget->category->icon }}</span>
                        <span style="font-size:13px;font-weight:500;color:#fff">{{ $budget->category->name }}</span>
                    </div>
                    <span style="font-size:11px;font-weight:600;color:{{ $bc }}">{{ number_format($budget->progress, 0) }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $budget->progress }}%;background:{{ $bc }}"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:5px">
                    <span style="font-size:11px;color:var(--t3)">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
                    <span style="font-size:11px;color:var(--t3)">/ Rp {{ number_format($budget->amount_limit, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Aktivitas Terbaru --}}
<div class="px" style="margin-top:24px;margin-bottom:8px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <span style="font-size:16px;font-weight:700;color:#fff">Aktivitas Terbaru</span>
        <a href="{{ route('transactions.index') }}" style="font-size:12px;color:var(--primary);font-weight:600">Lihat Semua</a>
    </div>

    @if($recentTransactions->isEmpty())
        <div class="card" style="padding:40px;text-align:center;color:var(--t3)">
            <span class="material-icons-round" style="font-size:40px;display:block;margin-bottom:8px">receipt_long</span>
            <p style="margin:0;font-size:13px">Belum ada transaksi</p>
        </div>
    @else
        <div class="section-card">
            @foreach($recentTransactions as $i => $tx)
                @if($i > 0)<div class="divider"></div>@endif
                @php
                    $inc = $tx->type === 'income';
                    $cc = $tx->category?->color ?? '#8E8E93';
                @endphp
                <div style="padding:14px 16px;display:flex;align-items:center;gap:12px">
                    <div class="icon-badge" style="background:{{ $cc }}22;width:42px;height:42px;border-radius:14px">
                        <span class="material-icons-round" style="color:{{ $cc }}">{{ $tx->category?->icon ?? 'category' }}</span>
                    </div>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:14px;font-weight:600;color:#fff;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $tx->category?->name ?? 'Kategori' }}</p>
                        <p style="font-size:12px;color:var(--t2);margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $tx->note ?: $tx->date->isoFormat('D MMM · HH:mm') }}</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-size:14px;font-weight:700;margin:0;color:{{ $inc ? 'var(--success)' : 'var(--error)' }}">
                            {{ $inc ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </p>
                        <p style="font-size:11px;color:var(--t3);margin:0">{{ $tx->date->isoFormat('D MMM') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
