@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('categories.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Edit Kategori</h1>
</div>

<div class="px" style="padding-top:8px">
    <div style="font-size:11px;font-weight:600;text-align:center;padding:8px;border-radius:10px;margin-bottom:20px;{{ $category->type==='expense'?'background:rgba(255,69,58,0.12);color:var(--error)':'background:rgba(48,209,88,0.12);color:var(--success)' }}">
        {{ $category->type === 'expense' ? 'KATEGORI PENGELUARAN' : 'KATEGORI PEMASUKAN' }}
    </div>
    <form method="POST" action="{{ route('categories.update', $category) }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf @method('PUT')
        <div><label class="label">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name',$category->name) }}" class="input-field" required>
        </div>
        @php $icons = ['restaurant','directions_car','shopping_bag','receipt','movie','local_hospital','school','more_horiz','payments','work','trending_up','card_giftcard','home','sports_esports','flight','phone_android','bolt','local_cafe','fitness_center','add_circle']; @endphp
        <div>
            <label class="label">Ikon</label>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px">
                @foreach($icons as $icon)
                    <label style="cursor:pointer">
                        <input type="radio" name="icon" value="{{ $icon }}" style="display:none" {{ old('icon',$category->icon)===$icon?'checked':'' }}>
                        <div onclick="selectIcon(this,'{{ $icon }}')" data-icon="{{ $icon }}"
                            style="padding:10px;border-radius:14px;background:var(--card);border:1px solid {{ old('icon',$category->icon)===$icon?'var(--primary)':'var(--border)' }};display:flex;align-items:center;justify-content:center">
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
                        <input type="radio" name="color" value="{{ $color }}" style="display:none" {{ old('color',$category->color)===$color?'checked':'' }}>
                        <div onclick="selectColor(this,'{{ $color }}')" data-color="{{ $color }}"
                            style="width:38px;height:38px;border-radius:50%;background:{{ $color }};border:2px solid {{ old('color',$category->color)===$color?'#fff':'transparent' }};transition:all 0.15s">
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
