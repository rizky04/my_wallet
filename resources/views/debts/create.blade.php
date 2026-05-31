@extends('layouts.app')
@section('title', 'Tambah Hutang/Piutang')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('debts.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Tambah Hutang/Piutang</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('debts.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf
        <div style="display:flex;background:var(--card);border:0.5px solid var(--border);border-radius:16px;overflow:hidden">
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="debt" style="display:none" {{ old('type','debt')==='debt'?'checked':'' }}>
                <div id="tab-debt" onclick="setType('debt')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;transition:all 0.15s;{{ old('type','debt')==='debt'?'background:rgba(255,69,58,0.15);color:var(--error)':'color:var(--t2)' }}">
                    Saya Berhutang
                </div>
            </label>
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="loan" style="display:none" {{ old('type')==='loan'?'checked':'' }}>
                <div id="tab-loan" onclick="setType('loan')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;transition:all 0.15s;{{ old('type')==='loan'?'background:rgba(48,209,88,0.15);color:var(--success)':'color:var(--t2)' }}">
                    Saya Meminjamkan
                </div>
            </label>
        </div>
        <div>
            <label class="label">Nama</label>
            <input type="text" name="person_name" value="{{ old('person_name') }}" class="input-field" placeholder="Nama orang" required>
        </div>
        <div>
            <label class="label">Jumlah</label>
            <input type="number" name="amount" value="{{ old('amount') }}" class="input-field"
                style="font-size:22px;font-weight:700" placeholder="0" min="0" step="1000" required>
        </div>
        <div>
            <label class="label">Jatuh Tempo (opsional)</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" class="input-field">
        </div>
        <div>
            <label class="label">Catatan (opsional)</label>
            <input type="text" name="note" value="{{ old('note') }}" class="input-field" placeholder="Untuk keperluan...">
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('debts.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>
<script>
function setType(v) {
    document.querySelector('input[name="type"][value="debt"]').checked = v==='debt';
    document.querySelector('input[name="type"][value="loan"]').checked = v==='loan';
    document.getElementById('tab-debt').style.background = v==='debt'?'rgba(255,69,58,0.15)':'';
    document.getElementById('tab-debt').style.color = v==='debt'?'var(--error)':'var(--t2)';
    document.getElementById('tab-loan').style.background = v==='loan'?'rgba(48,209,88,0.15)':'';
    document.getElementById('tab-loan').style.color = v==='loan'?'var(--success)':'var(--t2)';
}
</script>
@endsection
