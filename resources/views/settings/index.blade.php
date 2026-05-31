@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')

<div class="app-bar">
    <h1 style="font-size:22px;font-weight:700;color:#fff;margin:0">Pengaturan</h1>
</div>

<div class="px">

    {{-- Profile Card --}}
    <div class="card" style="padding:16px;display:flex;align-items:center;gap:14px;margin-bottom:28px">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:22px;color:#fff;flex-shrink:0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div style="flex:1">
            <p style="font-size:17px;font-weight:600;color:#fff;margin:0">{{ auth()->user()->name }}</p>
            <p style="font-size:12px;color:var(--t2);margin:2px 0 0">{{ auth()->user()->email }}</p>
        </div>
    </div>

    {{-- Fitur Keuangan --}}
    <p style="font-size:11px;font-weight:600;color:var(--t3);letter-spacing:0.8px;text-transform:uppercase;margin:0 0 10px">Fitur Keuangan</p>
    <div class="section-card" style="margin-bottom:24px">
        <a href="{{ route('budgets.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(10,132,255,0.15)">
                <span class="material-icons-round" style="color:var(--blue)">pie_chart</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Anggaran Bulanan</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Kelola batas pengeluaran</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </a>
        <div class="divider"></div>
        <a href="{{ route('debts.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(255,69,58,0.12)">
                <span class="material-icons-round" style="color:var(--error)">account_balance</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Hutang & Piutang</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Catat pinjaman dan tagihan</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </a>
        <div class="divider"></div>
        <a href="{{ route('goals.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(48,209,88,0.12)">
                <span class="material-icons-round" style="color:var(--success)">savings</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Target Tabungan</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Rencanakan tujuan keuangan</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </a>
        <div class="divider"></div>
        <a href="{{ route('transfers.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(191,90,242,0.12)">
                <span class="material-icons-round" style="color:var(--purple)">swap_horiz</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Riwayat Transfer</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Lihat semua transfer dompet</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </a>
        <div class="divider"></div>
        <a href="{{ route('recurring.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(255,159,10,0.12)">
                <span class="material-icons-round" style="color:var(--orange)">autorenew</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Tagihan Berulang</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">WiFi, listrik, kontrakan, dll.</p>
            </div>
            @php
                $pendingCount = auth()->user()->recurringTransactions()
                    ->where('is_active', true)->get()
                    ->filter(fn($r) => $r->isDueThisMonth())->count();
            @endphp
            @if($pendingCount > 0)
                <span style="background:var(--orange);color:#000;font-size:11px;font-weight:700;min-width:20px;height:20px;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 5px">{{ $pendingCount }}</span>
            @else
                <span class="material-icons-round chevron">chevron_right</span>
            @endif
        </a>
    </div>

    {{-- Data & Pengaturan --}}
    <p style="font-size:11px;font-weight:600;color:var(--t3);letter-spacing:0.8px;text-transform:uppercase;margin:0 0 10px">Data & Pengaturan</p>
    <div class="section-card" style="margin-bottom:24px">
        <a href="{{ route('categories.index') }}" style="padding:14px 16px;display:flex;align-items:center;gap:12px">
            <div class="icon-badge" style="background:rgba(255,159,10,0.15)">
                <span class="material-icons-round" style="color:var(--orange)">category</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Kategori</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Kelola kategori transaksi</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </a>
        <div class="divider"></div>
        <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;opacity:0.5">
            <div class="icon-badge" style="background:rgba(255,59,48,0.12)">
                <span class="material-icons-round" style="color:var(--primary)">backup</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Backup & Restore</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Segera hadir</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </div>
        <div class="divider"></div>
        <div style="padding:14px 16px;display:flex;align-items:center;gap:12px;opacity:0.5">
            <div class="icon-badge" style="background:rgba(48,209,88,0.12)">
                <span class="material-icons-round" style="color:var(--success)">download</span>
            </div>
            <div style="flex:1">
                <p style="font-size:14px;font-weight:500;color:#fff;margin:0">Ekspor Laporan</p>
                <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Excel / PDF (segera hadir)</p>
            </div>
            <span class="material-icons-round chevron">chevron_right</span>
        </div>
    </div>

    {{-- Akun --}}
    <p style="font-size:11px;font-weight:600;color:var(--t3);letter-spacing:0.8px;text-transform:uppercase;margin:0 0 10px">Akun</p>
    <div class="section-card" style="margin-bottom:28px">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" onclick="return confirm('Keluar dari akun?')"
                style="width:100%;background:none;border:none;padding:14px 16px;display:flex;align-items:center;gap:12px;cursor:pointer;text-align:left">
                <div class="icon-badge" style="background:rgba(255,69,58,0.12)">
                    <span class="material-icons-round" style="color:var(--error)">logout</span>
                </div>
                <div style="flex:1">
                    <p style="font-size:14px;font-weight:500;color:var(--error);margin:0">Keluar</p>
                    <p style="font-size:12px;color:var(--t2);margin:2px 0 0">Kembali ke halaman login</p>
                </div>
                <span class="material-icons-round chevron">chevron_right</span>
            </button>
        </form>
    </div>

    {{-- About --}}
    <div style="text-align:center;padding-bottom:8px">
        <p style="font-size:13px;color:var(--t2);font-weight:600;margin:0">Keuanganku</p>
        <p style="font-size:12px;color:var(--t3);margin:4px 0 0">Versi 1.0.0</p>
    </div>

</div>
@endsection
