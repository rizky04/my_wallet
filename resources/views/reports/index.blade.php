@extends('layouts.app')
@section('title', 'Laporan')

@section('content')

<div class="app-bar">
    <h1 style="font-size:22px;font-weight:700;color:#fff;margin:0 0 12px">Laporan</h1>
    <form method="GET" style="display:flex;gap:8px">
        <select name="month" class="input-field" style="flex:1;font-size:13px;padding:10px 12px">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
        <select name="year" class="input-field" style="width:90px;font-size:13px;padding:10px 12px">
            @foreach(range(now()->year-3,now()->year) as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" style="background:var(--primary);border:none;border-radius:12px;padding:0 14px;color:#fff;font-weight:600;font-size:13px;cursor:pointer">OK</button>
    </form>
</div>

{{-- Summary --}}
<div class="px" style="display:flex;gap:10px;margin-bottom:20px">
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Pemasukan</p>
        <p style="font-size:16px;font-weight:700;color:var(--success);margin:0">Rp {{ number_format($totalIncome,0,',','.') }}</p>
    </div>
    <div class="card" style="flex:1;padding:14px">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Pengeluaran</p>
        <p style="font-size:16px;font-weight:700;color:var(--error);margin:0">Rp {{ number_format($totalExpense,0,',','.') }}</p>
    </div>
</div>
@php $bal = $totalIncome - $totalExpense; @endphp
<div class="px" style="margin-bottom:24px">
    <div class="card" style="padding:14px;text-align:center">
        <p style="font-size:11px;color:var(--t2);margin:0 0 4px">Selisih Bulan Ini</p>
        <p style="font-size:20px;font-weight:700;color:{{ $bal>=0?'var(--success)':'var(--error)' }};margin:0">
            {{ $bal>=0?'+':'' }}Rp {{ number_format($bal,0,',','.') }}
        </p>
    </div>
</div>

{{-- Pie Chart --}}
<div class="px" style="margin-bottom:24px">
    <p style="font-size:16px;font-weight:700;color:#fff;margin:0 0 12px">Pengeluaran per Kategori</p>
    @if($categoryBreakdown->isEmpty())
        <div class="card" style="padding:32px;text-align:center;color:var(--t3)">
            <p style="margin:0;font-size:13px">Tidak ada pengeluaran bulan ini</p>
        </div>
    @else
        <div class="card" style="padding:20px">
            <div style="display:flex;justify-content:center;margin-bottom:20px">
                <canvas id="pieChart" width="200" height="200"></canvas>
            </div>
            @foreach($categoryBreakdown as $item)
                @php $pct = $totalExpense > 0 ? ($item['total']/$totalExpense)*100 : 0; @endphp
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $item['color'] }};flex-shrink:0"></div>
                    <span style="flex:1;font-size:13px;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $item['name'] }}</span>
                    <span style="font-size:12px;color:var(--t2)">{{ number_format($pct,0) }}%</span>
                    <span style="font-size:13px;font-weight:600;color:#fff">Rp {{ number_format($item['total'],0,',','.') }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Bar Chart --}}
<div class="px" style="margin-bottom:8px">
    <p style="font-size:16px;font-weight:700;color:#fff;margin:0 0 12px">Tren Bulanan {{ $year }}</p>
    <div class="card" style="padding:20px">
        <canvas id="barChart" height="200"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#8E8E93';

@if($categoryBreakdown->isNotEmpty())
new Chart(document.getElementById('pieChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryBreakdown->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($categoryBreakdown->pluck('total')) !!},
            backgroundColor: {!! json_encode($categoryBreakdown->pluck('color')) !!},
            borderWidth: 2, borderColor: '#1E1E1E',
        }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '68%' }
});
@endif

new Chart(document.getElementById('barChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [
            { label:'Pemasukan', data: {!! json_encode(collect($monthlyTrend)->pluck('income')) !!}, backgroundColor:'rgba(48,209,88,0.7)', borderRadius: 5 },
            { label:'Pengeluaran', data: {!! json_encode(collect($monthlyTrend)->pluck('expense')) !!}, backgroundColor:'rgba(255,69,58,0.7)', borderRadius: 5 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position:'top', labels:{ color:'#8E8E93', boxWidth:10, font:{size:11} } } },
        scales: {
            x: { grid:{ color:'#2C2C2E' }, ticks:{ color:'#8E8E93', font:{size:10} } },
            y: { grid:{ color:'#2C2C2E' }, ticks:{ color:'#8E8E93', font:{size:10}, callback: v => v>=1000000?(v/1000000).toFixed(0)+'jt':(v/1000).toFixed(0)+'rb' } }
        }
    }
});
</script>
@endpush
