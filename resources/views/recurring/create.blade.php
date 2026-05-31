@extends('layouts.app')
@section('title', 'Tambah Tagihan Berulang')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('recurring.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Tambah Tagihan Berulang</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('recurring.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf

        <div>
            <label class="label">Nama Tagihan</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input-field"
                placeholder="WiFi, Listrik, Kontrakan, Netflix..." required>
        </div>

        <div>
            <label class="label">Nominal / Bulan</label>
            <input type="number" name="amount" value="{{ old('amount') }}" class="input-field"
                style="font-size:24px;font-weight:700" placeholder="0" min="1" step="any" required>
        </div>

        <div>
            <label class="label">Tanggal Tagihan Tiap Bulan</label>
            <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px">
                @foreach(range(1, 28) as $day)
                    <label style="cursor:pointer">
                        <input type="radio" name="day_of_month" value="{{ $day }}" style="display:none"
                            {{ old('day_of_month', 1) == $day ? 'checked' : '' }}>
                        <div onclick="selectDay(this, {{ $day }})" data-day="{{ $day }}"
                            style="padding:8px 4px;border-radius:10px;text-align:center;font-size:13px;font-weight:600;background:var(--card);border:1px solid {{ old('day_of_month', 1) == $day ? 'var(--primary)' : 'var(--border)' }};color:{{ old('day_of_month', 1) == $day ? 'var(--primary)' : 'var(--t2)' }};transition:all 0.15s">
                            {{ $day }}
                        </div>
                    </label>
                @endforeach
            </div>
            <p style="font-size:11px;color:var(--t3);margin:6px 0 0">Maksimal tanggal 28 agar aman di semua bulan.</p>
        </div>

        <div>
            <label class="label">Kategori Pengeluaran</label>
            <select name="category_id" class="input-field" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Dompet</label>
            <select name="wallet_id" class="input-field" required>
                <option value="">Pilih dompet</option>
                @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ old('wallet_id') == $w->id ? 'selected' : '' }}>
                        {{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Catatan (opsional)</label>
            <input type="text" name="note" value="{{ old('note') }}" class="input-field"
                placeholder="Contoh: Paket 100 Mbps, Voucher 1 bulan...">
        </div>

        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('recurring.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>

<script>
function selectDay(el, day) {
    document.querySelectorAll('[data-day]').forEach(d => {
        d.style.borderColor = 'var(--border)';
        d.style.color = 'var(--t2)';
    });
    el.style.borderColor = 'var(--primary)';
    el.style.color = 'var(--primary)';
    document.querySelector('input[name="day_of_month"][value="'+day+'"]').checked = true;
}
</script>
@endsection
