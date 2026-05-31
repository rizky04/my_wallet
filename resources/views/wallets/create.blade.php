@extends('layouts.app')
@section('title', 'Tambah Dompet')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('wallets.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0">Tambah Dompet</h1>
</div>

<div class="px" style="padding-top:8px">
    <form method="POST" action="{{ route('wallets.store') }}" style="display:flex;flex-direction:column;gap:20px">
        @csrf
        <div>
            <label class="label">Nama Dompet</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input-field" placeholder="BCA, Gopay, Tunai..." required>
        </div>
        <div>
            <label class="label">Saldo Awal</label>
            <input type="number" name="balance" value="{{ old('balance',0) }}" class="input-field" style="font-size:20px;font-weight:700" min="0" step="1000" required>
        </div>
        @php $icons = ['account_balance_wallet','account_balance','savings','credit_card','payments','attach_money','monetization_on','local_atm','wallet','currency_exchange']; @endphp
        <div>
            <label class="label">Ikon</label>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px">
                @foreach($icons as $icon)
                    <label style="cursor:pointer">
                        <input type="radio" name="icon" value="{{ $icon }}" style="display:none" {{ old('icon','account_balance_wallet')===$icon?'checked':'' }}>
                        <div onclick="selectIcon(this,'{{ $icon }}')" data-icon="{{ $icon }}"
                            style="padding:12px;border-radius:14px;background:var(--card);border:1px solid {{ old('icon','account_balance_wallet')===$icon?'var(--primary)':'var(--border)' }};display:flex;align-items:center;justify-content:center;transition:border 0.15s">
                            <span class="material-icons-round" style="font-size:22px;color:var(--t2)">{{ $icon }}</span>
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
                            style="width:38px;height:38px;border-radius:50%;background:{{ $color }};border:2px solid {{ old('color','#FF3B30')===$color?'#fff':'transparent' }};transition:all 0.15s;transform:scale({{ old('color','#FF3B30')===$color?'1.15':'1' }})">
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
        <div style="display:flex;gap:12px;padding-bottom:8px">
            <a href="{{ route('wallets.index') }}" class="btn-ghost" style="flex:1;text-align:center;padding:15px">Batal</a>
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
    document.querySelectorAll('[data-color]').forEach(d => { d.style.borderColor='transparent'; d.style.transform='scale(1)'; });
    el.style.borderColor = '#fff';
    el.style.transform = 'scale(1.15)';
    document.querySelector('input[name="color"][value="'+val+'"]').checked = true;
}
</script>
@endsection
