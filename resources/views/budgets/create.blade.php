@extends('layouts.app')
@section('title', 'Tambah Anggaran')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('budgets.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Tambah Anggaran</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('budgets.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf
        <div>
            <label class="label">Kategori Pengeluaran</label>
            <select name="category_id" class="input-field" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ old('category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Batas Anggaran</label>
            <input type="number" name="amount_limit" value="{{ old('amount_limit') }}" class="input-field"
                style="font-size:22px;font-weight:700" placeholder="0" min="1" step="10000" required>
        </div>
        <div style="display:flex;gap:12px">
            <div style="flex:1">
                <label class="label">Bulan</label>
                <select name="month" class="input-field" required>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ old('month',$now->month)==$m?'selected':'' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="width:100px">
                <label class="label">Tahun</label>
                <select name="year" class="input-field" required>
                    @foreach(range(now()->year-1,now()->year+1) as $y)
                        <option value="{{ $y }}" {{ old('year',$now->year)==$y?'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('budgets.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>
@endsection
