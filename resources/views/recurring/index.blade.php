@extends('layouts.app')
@section('title', 'Tagihan Berulang')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('settings.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Tagihan Berulang</h1>
    <a href="{{ route('recurring.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

{{-- Info Banner --}}
<div class="px" style="margin-bottom:16px">
    <div style="background:rgba(10,132,255,0.1);border:0.5px solid rgba(10,132,255,0.3);border-radius:14px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px">
        <span class="material-icons-round" style="color:var(--blue);font-size:18px;flex-shrink:0;margin-top:1px">info</span>
        <p style="font-size:12px;color:var(--t2);margin:0;line-height:1.5">
            Tagihan yang aktif akan otomatis masuk ke <strong style="color:#fff">pengeluaran</strong> sesuai tanggal yang ditentukan setiap bulan. Sistem berjalan tiap hari pukul 07.00.
        </p>
    </div>
</div>

@if($recurrings->isEmpty())
    <div style="padding:60px 20px;text-align:center;color:var(--t3)">
        <span class="material-icons-round" style="font-size:48px;display:block;margin-bottom:12px">autorenew</span>
        <p style="font-size:15px;font-weight:600;margin:0 0 4px;color:var(--t2)">Belum ada tagihan berulang</p>
        <p style="font-size:13px;margin:0 0 20px">Tambahkan WiFi, listrik, kontrakan, dll.</p>
        <a href="{{ route('recurring.create') }}" style="background:var(--primary);color:#fff;padding:12px 24px;border-radius:14px;font-weight:600;font-size:14px">+ Tambah Tagihan</a>
    </div>
@else
    <div class="px" style="display:flex;flex-direction:column;gap:10px">
        @foreach($recurrings as $r)
            @php
                $isDue = $r->is_active && $r->isDueThisMonth();
                $catColor = $r->category?->color ?? '#8E8E93';
            @endphp
            <div class="card" style="padding:16px;{{ !$r->is_active ? 'opacity:0.55' : '' }}">
                <div style="display:flex;align-items:center;gap:12px">
                    {{-- Icon --}}
                    <div style="width:44px;height:44px;border-radius:14px;background:{{ $catColor }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <span class="material-icons-round" style="color:{{ $catColor }};font-size:20px">{{ $r->category?->icon ?? 'autorenew' }}</span>
                    </div>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px">
                            <span style="font-size:14px;font-weight:600;color:#fff">{{ $r->name }}</span>
                            @if($isDue)
                                <span style="font-size:10px;font-weight:600;padding:1px 6px;border-radius:20px;background:rgba(255,159,10,0.15);color:var(--orange)">Menunggu</span>
                            @elseif($r->is_active && $r->last_run_at?->isSameMonth(now()))
                                <span style="font-size:10px;font-weight:600;padding:1px 6px;border-radius:20px;background:rgba(48,209,88,0.12);color:var(--success)">Sudah diproses</span>
                            @endif
                        </div>
                        <p style="font-size:12px;color:var(--t2);margin:0">
                            Tgl {{ $r->day_of_month }} tiap bulan · {{ $r->wallet?->name }}
                        </p>
                        @if($r->last_run_at)
                            <p style="font-size:11px;color:var(--t3);margin:1px 0 0">
                                Terakhir: {{ $r->last_run_at->isoFormat('D MMM YYYY') }}
                            </p>
                        @endif
                    </div>

                    {{-- Amount --}}
                    <div style="text-align:right;flex-shrink:0">
                        <p style="font-size:15px;font-weight:700;color:var(--error);margin:0">
                            Rp {{ number_format($r->amount, 0, ',', '.') }}
                        </p>
                        <p style="font-size:10px;color:var(--t3);margin:1px 0 0">per bulan</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:8px;margin-top:12px;padding-top:12px;border-top:0.5px solid var(--divider)">
                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('recurring.toggle', $r) }}" style="flex:1">
                        @csrf
                        <button type="submit" style="width:100%;background:{{ $r->is_active ? 'rgba(255,69,58,0.1)' : 'rgba(48,209,88,0.1)' }};border:0.5px solid {{ $r->is_active ? 'rgba(255,69,58,0.3)' : 'rgba(48,209,88,0.3)' }};border-radius:10px;padding:8px 0;display:flex;align-items:center;justify-content:center;gap:5px;cursor:pointer;font-size:12px;font-weight:600;color:{{ $r->is_active ? 'var(--error)' : 'var(--success)' }}">
                            <span class="material-icons-round" style="font-size:15px">{{ $r->is_active ? 'pause_circle' : 'play_circle' }}</span>
                            {{ $r->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    {{-- Run Now (only if active & due) --}}
                    @if($isDue)
                        <form method="POST" action="{{ route('recurring.run-now', $r) }}" style="flex:1">
                            @csrf
                            <button type="submit" style="width:100%;background:rgba(255,159,10,0.12);border:0.5px solid rgba(255,159,10,0.3);border-radius:10px;padding:8px 0;display:flex;align-items:center;justify-content:center;gap:5px;cursor:pointer;font-size:12px;font-weight:600;color:var(--orange)">
                                <span class="material-icons-round" style="font-size:15px">bolt</span>
                                Jalankan Kini
                            </button>
                        </form>
                    @endif

                    {{-- Edit --}}
                    <a href="{{ route('recurring.edit', $r) }}" style="width:36px;height:36px;background:var(--surface);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--t2);flex-shrink:0">
                        <span class="material-icons-round" style="font-size:17px">edit</span>
                    </a>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('recurring.destroy', $r) }}" onsubmit="return confirm('Hapus tagihan {{ $r->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:36px;height:36px;background:var(--surface);border:none;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer;flex-shrink:0">
                            <span class="material-icons-round" style="font-size:17px">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Summary --}}
    <div class="px" style="margin-top:16px">
        @php $totalPerMonth = $recurrings->where('is_active', true)->sum('amount'); @endphp
        <div class="card" style="padding:14px;display:flex;justify-content:space-between;align-items:center">
            <div>
                <p style="font-size:12px;color:var(--t2);margin:0">Total tagihan aktif / bulan</p>
                <p style="font-size:11px;color:var(--t3);margin:2px 0 0">{{ $recurrings->where('is_active', true)->count() }} tagihan aktif</p>
            </div>
            <p style="font-size:18px;font-weight:700;color:var(--error);margin:0">Rp {{ number_format($totalPerMonth, 0, ',', '.') }}</p>
        </div>
    </div>
@endif

@endsection
