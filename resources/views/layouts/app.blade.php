<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A0A0A">
    <title>@yield('title', 'Keuanganku')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        :root {
            --bg:          #0A0A0A;
            --surface:     #1A1A1A;
            --card:        #1E1E1E;
            --border:      #2A2A2A;
            --divider:     #2C2C2E;
            --primary:     #FF3B30;
            --primary-dim: rgba(255,59,48,0.15);
            --success:     #30D158;
            --error:       #FF453A;
            --warning:     #FFD60A;
            --blue:        #0A84FF;
            --purple:      #BF5AF2;
            --orange:      #FF9F0A;
            --t1:          #FFFFFF;
            --t2:          #8E8E93;
            --t3:          #48484A;
            --nav-h:       68px;
        }

        html, body {
            background: var(--bg);
            color: var(--t1);
            font-family: -apple-system, 'SF Pro Display', 'Helvetica Neue', system-ui, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── Phone shell ─────────────────────────── */
        #app-shell {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--bg);
            position: relative;
        }

        /* Outer background dimming for desktop */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: #000;
            z-index: -1;
        }
        @media(min-width:431px) {
            body { background: #050505; }
        }

        /* ─── Content area ────────────────────────── */
        #page-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: calc(var(--nav-h) + 12px);
        }

        /* ─── Bottom Navigation ───────────────────── */
        #bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            height: var(--nav-h);
            background: var(--surface);
            border-top: 0.5px solid var(--border);
            display: flex;
            align-items: center;
            z-index: 100;
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 8px 0;
            cursor: pointer;
            text-decoration: none;
            color: var(--t2);
            transition: color 0.15s;
        }

        .nav-item.active { color: var(--primary); }

        .nav-item .nav-indicator {
            width: 36px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }
        .nav-item.active .nav-indicator {
            background: var(--primary-dim);
        }

        .nav-item .material-icons-round { font-size: 22px; }
        .nav-item span.nav-label { font-size: 10px; font-weight: 600; letter-spacing: 0.2px; }

        /* ─── Components ──────────────────────────── */
        .card {
            background: var(--card);
            border: 0.5px solid var(--border);
            border-radius: 20px;
        }

        .section-card {
            background: var(--card);
            border: 0.5px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            padding: 16px;
            width: 100%;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn-primary:hover { opacity: 0.88; }
        .btn-primary.sm { padding: 10px 18px; font-size: 13px; border-radius: 12px; width: auto; }

        .btn-ghost {
            background: var(--card);
            color: var(--t1);
            border: 0.5px solid var(--border);
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            padding: 15px;
            width: 100%;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-ghost:hover { border-color: var(--primary); color: var(--primary); }

        .input-field {
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--t1);
            padding: 15px 16px;
            border-radius: 14px;
            width: 100%;
            font-size: 15px;
            transition: border-color 0.2s;
            font-family: inherit;
        }
        .input-field:focus { outline: none; border-color: var(--primary); border-width: 1.5px; }
        .input-field::placeholder { color: var(--t3); }
        select.input-field option { background: var(--surface); }

        .label {
            color: var(--t2);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .progress-bar {
            background: var(--border);
            border-radius: 999px;
            overflow: hidden;
            height: 5px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.6s ease;
        }

        .icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-badge .material-icons-round { font-size: 19px; }

        .divider { height: 0.5px; background: var(--divider); margin-left: 60px; }

        /* ─── App Bar ─────────────────────────────── */
        .app-bar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--bg);
            padding: 12px 20px 10px;
        }

        .app-bar-plain {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--bg);
        }

        .back-btn {
            width: 36px;
            height: 36px;
            background: var(--card);
            border: 0.5px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--t2);
            flex-shrink: 0;
            text-decoration: none;
        }
        .back-btn .material-icons-round { font-size: 20px; }

        /* Toast flash */
        .flash-success {
            background: rgba(48,209,88,0.12);
            border: 0.5px solid rgba(48,209,88,0.3);
            color: var(--success);
            padding: 12px 16px;
            border-radius: 14px;
            margin: 0 20px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .flash-error {
            background: rgba(255,69,58,0.12);
            border: 0.5px solid rgba(255,69,58,0.3);
            color: var(--error);
            padding: 12px 16px;
            border-radius: 14px;
            margin: 0 20px 12px;
            font-size: 13px;
        }

        /* Badge type */
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        /* Chevron */
        .chevron { color: var(--t3); font-size: 18px !important; }

        /* Amount style */
        .amount-income { color: var(--success); }
        .amount-expense { color: var(--error); }

        /* Page padding */
        .px { padding-left: 20px; padding-right: 20px; }

        a { text-decoration: none; color: inherit; }

        /* Hide scrollbar on mobile */
        #page-content::-webkit-scrollbar { display: none; }
        #page-content { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('head')
</head>
<body>
<div id="app-shell">
    <div id="page-content">
        {{-- Flash --}}
        @if(session('success'))
            <div class="flash-success" style="margin-top:12px">
                <span class="material-icons-round" style="font-size:18px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="flash-error" style="margin-top:12px">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                    <span class="material-icons-round" style="font-size:16px">error</span>
                    <strong>Ada kesalahan:</strong>
                </div>
                <ul style="margin:0;padding-left:20px">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Bottom Navigation --}}
    <nav id="bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-indicator">
                <span class="material-icons-round">
                    {{ request()->routeIs('dashboard') ? 'home' : 'home' }}
                </span>
            </div>
            <span class="nav-label">Beranda</span>
        </a>
        <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <div class="nav-indicator">
                <span class="material-icons-round">receipt_long</span>
            </div>
            <span class="nav-label">Transaksi</span>
        </a>
        <a href="{{ route('wallets.index') }}" class="nav-item {{ request()->routeIs('wallets.*') || request()->routeIs('transfers.*') ? 'active' : '' }}">
            <div class="nav-indicator">
                <span class="material-icons-round">account_balance_wallet</span>
            </div>
            <span class="nav-label">Dompet</span>
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <div class="nav-indicator">
                <span class="material-icons-round">bar_chart</span>
            </div>
            <span class="nav-label">Laporan</span>
        </a>
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') || request()->routeIs('budgets.*') || request()->routeIs('debts.*') || request()->routeIs('goals.*') || request()->routeIs('categories.*') ? 'active' : '' }}">
            <div class="nav-indicator">
                <span class="material-icons-round">settings</span>
            </div>
            <span class="nav-label">Pengaturan</span>
        </a>
    </nav>
</div>
@stack('scripts')
</body>
</html>
