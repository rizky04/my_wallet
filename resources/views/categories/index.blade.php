@extends('layouts.app')
@section('title', 'Kategori')

@section('content')

<div class="app-bar-plain">
    <a href="{{ route('settings.index') }}" class="back-btn">
        <span class="material-icons-round">arrow_back_ios_new</span>
    </a>
    <h1 style="font-size:17px;font-weight:600;color:#fff;margin:0;flex:1">Kategori</h1>
    <a href="{{ route('categories.create') }}" style="width:36px;height:36px;background:var(--primary);border-radius:11px;display:flex;align-items:center;justify-content:center">
        <span class="material-icons-round" style="color:#fff;font-size:20px">add</span>
    </a>
</div>

<div class="px">
    {{-- Pengeluaran --}}
    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px">
        <span class="material-icons-round" style="font-size:16px;color:var(--error)">arrow_upward</span>
        <p style="font-size:13px;font-weight:600;color:var(--t2);margin:0;text-transform:uppercase;letter-spacing:0.6px">Pengeluaran ({{ $expenseCategories->count() }})</p>
    </div>
    <div class="section-card" style="margin-bottom:24px">
        @forelse($expenseCategories as $i => $cat)
            @if($i > 0)<div class="divider"></div>@endif
            <div style="padding:13px 16px;display:flex;align-items:center;gap:12px">
                <div class="icon-badge" style="background:{{ $cat->color }}22">
                    <span class="material-icons-round" style="color:{{ $cat->color }}">{{ $cat->icon }}</span>
                </div>
                <span style="flex:1;font-size:14px;font-weight:500;color:#fff">{{ $cat->name }}</span>
                <div style="display:flex;gap:4px">
                    <a href="{{ route('categories.edit', $cat) }}" style="width:28px;height:28px;background:var(--surface);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                        <span class="material-icons-round" style="font-size:14px">edit</span>
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Hapus {{ $cat->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:28px;height:28px;background:var(--surface);border:none;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                            <span class="material-icons-round" style="font-size:14px">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="padding:20px;text-align:center;font-size:13px;color:var(--t3)">Belum ada kategori</div>
        @endforelse
    </div>

    {{-- Pemasukan --}}
    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px">
        <span class="material-icons-round" style="font-size:16px;color:var(--success)">arrow_downward</span>
        <p style="font-size:13px;font-weight:600;color:var(--t2);margin:0;text-transform:uppercase;letter-spacing:0.6px">Pemasukan ({{ $incomeCategories->count() }})</p>
    </div>
    <div class="section-card">
        @forelse($incomeCategories as $i => $cat)
            @if($i > 0)<div class="divider"></div>@endif
            <div style="padding:13px 16px;display:flex;align-items:center;gap:12px">
                <div class="icon-badge" style="background:{{ $cat->color }}22">
                    <span class="material-icons-round" style="color:{{ $cat->color }}">{{ $cat->icon }}</span>
                </div>
                <span style="flex:1;font-size:14px;font-weight:500;color:#fff">{{ $cat->name }}</span>
                <div style="display:flex;gap:4px">
                    <a href="{{ route('categories.edit', $cat) }}" style="width:28px;height:28px;background:var(--surface);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2)">
                        <span class="material-icons-round" style="font-size:14px">edit</span>
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Hapus {{ $cat->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:28px;height:28px;background:var(--surface);border:none;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--t2);cursor:pointer">
                            <span class="material-icons-round" style="font-size:14px">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="padding:20px;text-align:center;font-size:13px;color:var(--t3)">Belum ada kategori</div>
        @endforelse
    </div>
</div>
@endsection
