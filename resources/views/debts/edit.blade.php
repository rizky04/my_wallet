@extends('layouts.app')
@section('title', 'Edit Hutang/Piutang')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('debts.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Edit Hutang/Piutang</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('debts.update', $debt) }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf @method('PUT')
        <div><label class="label">Nama</label>
            <input type="text" name="person_name" value="{{ old('person_name',$debt->person_name) }}" class="input-field" required>
        </div>
        <div><label class="label">Jumlah Total</label>
            <input type="number" name="amount" value="{{ old('amount',$debt->amount) }}" class="input-field" style="font-size:20px;font-weight:700" min="0" step="1000" required>
        </div>
        <div><label class="label">Sudah Dibayar</label>
            <input type="number" name="paid_amount" value="{{ old('paid_amount',$debt->paid_amount) }}" class="input-field" min="0" step="1000" required>
        </div>
        <div><label class="label">Status</label>
            <select name="status" class="input-field" required>
                <option value="active" {{ old('status',$debt->status)==='active'?'selected':'' }}>Aktif</option>
                <option value="paid" {{ old('status',$debt->status)==='paid'?'selected':'' }}>Lunas</option>
            </select>
        </div>
        <div><label class="label">Jatuh Tempo</label>
            <input type="date" name="due_date" value="{{ old('due_date',$debt->due_date?->format('Y-m-d')) }}" class="input-field">
        </div>
        <div><label class="label">Catatan</label>
            <input type="text" name="note" value="{{ old('note',$debt->note) }}" class="input-field">
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('debts.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>
@endsection
