@extends('layouts.app')
@section('title', 'Edit Transaksi')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('transactions.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Edit Transaksi</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('transactions.update', $transaction) }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf @method('PUT')

        <div style="display:flex;background:var(--card);border:0.5px solid var(--border);border-radius:16px;overflow:hidden">
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="expense" style="display:none" {{ old('type',$transaction->type)==='expense'?'checked':'' }}>
                <div id="tab-exp" onclick="setType('expense')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;transition:all 0.15s;{{ old('type',$transaction->type)==='expense'?'background:rgba(255,69,58,0.15);color:var(--error)':'color:var(--t2)' }}">
                    Pengeluaran
                </div>
            </label>
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="income" style="display:none" {{ old('type',$transaction->type)==='income'?'checked':'' }}>
                <div id="tab-inc" onclick="setType('income')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;transition:all 0.15s;{{ old('type',$transaction->type)==='income'?'background:rgba(48,209,88,0.15);color:var(--success)':'color:var(--t2)' }}">
                    Pemasukan
                </div>
            </label>
        </div>

        <div>
            <label class="label">Nominal</label>
            <input type="number" name="amount" value="{{ old('amount',$transaction->amount) }}" class="input-field"
                style="font-size:24px;font-weight:700" min="0" step="1000" required>
        </div>

        <div>
            <label class="label">Kategori</label>
            <select name="category_id" class="input-field" required>
                <optgroup label="Pengeluaran">
                    @foreach($categories->where('type','expense') as $c)
                        <option value="{{ $c->id }}" {{ old('category_id',$transaction->category_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Pemasukan">
                    @foreach($categories->where('type','income') as $c)
                        <option value="{{ $c->id }}" {{ old('category_id',$transaction->category_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <div>
            <label class="label">Dompet</label>
            <select name="wallet_id" class="input-field" required>
                @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ old('wallet_id',$transaction->wallet_id)==$w->id?'selected':'' }}>{{ $w->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label">Tanggal & Waktu</label>
            <input type="datetime-local" name="date" value="{{ old('date',$transaction->date->format('Y-m-d\TH:i')) }}"
                class="input-field" required>
        </div>

        <div>
            <label class="label">Catatan</label>
            <textarea name="note" class="input-field" rows="2">{{ old('note',$transaction->note) }}</textarea>
        </div>

        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('transactions.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>

<script>
function setType(v) {
    document.querySelector('input[name="type"][value="expense"]').checked = v==='expense';
    document.querySelector('input[name="type"][value="income"]').checked = v==='income';
    document.getElementById('tab-exp').style.background = v==='expense' ? 'rgba(255,69,58,0.15)' : '';
    document.getElementById('tab-exp').style.color = v==='expense' ? 'var(--error)' : 'var(--t2)';
    document.getElementById('tab-inc').style.background = v==='income' ? 'rgba(48,209,88,0.15)' : '';
    document.getElementById('tab-inc').style.color = v==='income' ? 'var(--success)' : 'var(--t2)';
}
</script>
@endsection
