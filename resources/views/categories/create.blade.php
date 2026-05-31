@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('categories.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Tambah Kategori</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('categories.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf
        <div style="display:flex;background:var(--card);border:0.5px solid var(--border);border-radius:16px;overflow:hidden">
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="expense" style="display:none" {{ old('type','expense')==='expense'?'checked':'' }}>
                <div id="tab-exp" onclick="setType('expense')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;{{ old('type','expense')==='expense'?'background:rgba(255,69,58,0.15);color:var(--error)':'color:var(--t2)' }}">
                    Pengeluaran
                </div>
            </label>
            <label style="flex:1;cursor:pointer">
                <input type="radio" name="type" value="income" style="display:none" {{ old('type')==='income'?'checked':'' }}>
                <div id="tab-inc" onclick="setType('income')" style="padding:14px;text-align:center;font-weight:600;font-size:14px;{{ old('type')==='income'?'background:rgba(48,209,88,0.15);color:var(--success)':'color:var(--t2)' }}">
                    Pemasukan
                </div>
            </label>
        </div>
        <div><label class="label">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input-field" placeholder="Nama kategori" required>
        </div>
        @php $icons = ['restaurant','directions_car','shopping_bag','receipt','movie','local_hospital','school','more_horiz','payments','work','trending_up','card_giftcard','home','sports_esports','flight','phone_android','bolt','local_cafe','fitness_center','add_circle']; @endphp
        <div>
            <label class="label">Ikon</label>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px">
                @foreach($icons as $icon)
                    <label style="cursor:pointer">
                        <input type="radio" name="icon" value="{{ $icon }}" style="display:none" {{ old('icon','category')===$icon?'checked':'' }}>
                        <div onclick="selectIcon(this,'{{ $icon }}')" data-icon="{{ $icon }}"
                            style="padding:10px;border-radius:14px;background:var(--card);border:1px solid {{ old('icon')===$icon?'var(--primary)':'var(--border)' }};display:flex;align-items:center;justify-content:center">
                            <span class="material-icons-round" style="font-size:20px;color:var(--t2)">{{ $icon }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        @php $colors = ['#FF3B30','#30D158','#0A84FF','#BF5AF2','#FF9F0A','#FF453A','#5E5CE6','#64D2FF','#FF6369','#30B0C7']; @endphp
        <div>
            <label class="label">Warna</label>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                @foreach($colors as $color)
                    <label style="cursor:pointer">
                        <input type="radio" name="color" value="{{ $color }}" style="display:none" {{ old('color','#FF3B30')===$color?'checked':'' }}>
                        <div onclick="selectColor(this,'{{ $color }}')" data-color="{{ $color }}"
                            style="width:38px;height:38px;border-radius:50%;background:{{ $color }};border:2px solid {{ old('color','#FF3B30')===$color?'#fff':'transparent' }};transition:all 0.15s">
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('categories.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
            <button type="submit" class="btn-primary" style="flex:1">Simpan</button>
        </div>
    </form>
</div>
<script>
function setType(v) {
    document.querySelector('input[name="type"][value="expense"]').checked = v==='expense';
    document.querySelector('input[name="type"][value="income"]').checked = v==='income';
    document.getElementById('tab-exp').style.background = v==='expense'?'rgba(255,69,58,0.15)':'';
    document.getElementById('tab-exp').style.color = v==='expense'?'var(--error)':'var(--t2)';
    document.getElementById('tab-inc').style.background = v==='income'?'rgba(48,209,88,0.15)':'';
    document.getElementById('tab-inc').style.color = v==='income'?'var(--success)':'var(--t2)';
}
function selectIcon(el, val) {
    document.querySelectorAll('[data-icon]').forEach(d => d.style.borderColor = 'var(--border)');
    el.style.borderColor = 'var(--primary)';
    document.querySelector('input[name="icon"][value="'+val+'"]').checked = true;
}
function selectColor(el, val) {
    document.querySelectorAll('[data-color]').forEach(d => { d.style.borderColor='transparent'; });
    el.style.borderColor = '#fff';
    document.querySelector('input[name="color"][value="'+val+'"]').checked = true;
}
</script>
@endsection
