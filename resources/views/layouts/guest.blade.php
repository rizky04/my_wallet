<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0A0A0A">
    <title>@yield('title', 'Keuanganku')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --bg: #0A0A0A; --surface: #1A1A1A; --card: #1E1E1E; --border: #2A2A2A;
            --primary: #FF3B30; --error: #FF453A; --t1: #fff; --t2: #8E8E93; --t3: #48484A;
        }
        html, body { background: var(--bg); color: var(--t1); font-family: -apple-system,'SF Pro Display','Helvetica Neue',system-ui,sans-serif; min-height: 100vh; }
        body { display: flex; align-items: center; justify-content: center; }
        #shell { width: 100%; max-width: 430px; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; padding: 32px 24px 48px; }
        .input-field { background: var(--card); border: 1px solid var(--border); color: var(--t1); padding: 15px 16px; border-radius: 14px; width: 100%; font-size: 15px; transition: border-color 0.2s; font-family: inherit; }
        .input-field:focus { outline: none; border-color: var(--primary); border-width: 1.5px; }
        .input-field::placeholder { color: var(--t3); }
        .label { color: var(--t2); font-size: 13px; font-weight: 500; margin-bottom: 6px; display: block; }
        .btn-primary { background: var(--primary); color: #fff; border: none; border-radius: 16px; font-weight: 700; font-size: 16px; padding: 17px; width: 100%; cursor: pointer; transition: opacity 0.15s; font-family: inherit; }
        .btn-primary:hover { opacity: 0.88; }
    </style>
</head>
<body>
<div id="shell">
    {{-- Logo --}}
    <div style="text-align:center;margin-bottom:36px">
        <div style="width:72px;height:72px;border-radius:22px;background:var(--primary);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px">
            <span class="material-icons-round" style="font-size:36px;color:#fff">account_balance_wallet</span>
        </div>
        <h1 style="font-size:24px;font-weight:700;color:#fff;margin:0 0 4px">Keuanganku</h1>
        <p style="font-size:13px;color:var(--t2);margin:0">Kelola keuangan pribadi Anda</p>
    </div>

    @if($errors->any())
        <div style="background:rgba(255,69,58,0.12);border:0.5px solid rgba(255,69,58,0.3);color:var(--error);padding:12px 16px;border-radius:14px;margin-bottom:16px;font-size:13px">
            @foreach($errors->all() as $e)
                <p style="margin:0">{{ $e }}</p>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
