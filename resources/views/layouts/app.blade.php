<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Targets') | Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:#f0f2f8;display:flex;height:100vh;overflow:hidden;color:#1e293b;}

        /* ── SIDEBAR ── */
        .sidebar{width:210px;min-height:100vh;background:#fff;border-right:1px solid #e8eaf0;display:flex;flex-direction:column;position:fixed;left:0;top:0;bottom:0;z-index:100;transition:width .3s;}
        .sidebar.collapsed{width:60px;}
        .sidebar-logo{display:flex;align-items:center;gap:10px;padding:18px 16px;border-bottom:1px solid #e8eaf0;min-height:60px;}
        .sidebar-logo .logo-text{font-size:.9rem;font-weight:700;color:#3b4edb;white-space:nowrap;overflow:hidden;transition:opacity .2s;}
        .sidebar.collapsed .logo-text{opacity:0;width:0;}
        .logo-icon{width:28px;height:28px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .logo-icon svg{color:#fff;}

        .nav-section{padding:10px 0;}
        .nav-section-label{font-size:.65rem;font-weight:700;letter-spacing:.08em;color:#94a3b8;padding:8px 16px 4px;text-transform:uppercase;display:flex;align-items:center;gap:8px;white-space:nowrap;overflow:hidden;}
        .sidebar.collapsed .nav-section-label{padding:8px 16px 4px;justify-content:center;}
        .sidebar.collapsed .nav-section-label span{display:none;}

        .nav-item{display:flex;align-items:center;gap:10px;padding:9px 16px;font-size:.82rem;color:#475569;cursor:pointer;transition:background .15s,color .15s;white-space:nowrap;overflow:hidden;text-decoration:none;border-left:3px solid transparent;}
        .nav-item:hover{background:#f1f4ff;color:#4f46e5;}
        .nav-item.active{background:#eef0ff;color:#4f46e5;border-left-color:#4f46e5;font-weight:600;}
        .nav-item-icon{flex-shrink:0;width:16px;height:16px;opacity:.7;}
        .nav-item.active .nav-item-icon{opacity:1;}
        .sidebar.collapsed .nav-item span{display:none;}
        .sidebar.collapsed .nav-item{justify-content:center;padding:10px;}

        /* ── TOPBAR ── */
        .main-wrap{margin-left:210px;display:flex;flex-direction:column;flex:1;height:100vh;transition:margin-left .3s;overflow:hidden;}
        .main-wrap.expanded{margin-left:60px;}

        .topbar{height:60px;background:#fff;border-bottom:1px solid #e8eaf0;display:flex;align-items:center;padding:0 24px;gap:12px;position:sticky;top:0;z-index:50;}
        .topbar-toggle{width:32px;height:32px;border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:8px;color:#64748b;transition:background .15s;}
        .topbar-toggle:hover{background:#f1f4ff;color:#4f46e5;}
        .topbar-title{font-size:.95rem;font-weight:600;color:#1e293b;flex:1;}

        .topbar-actions{display:flex;align-items:center;gap:8px;}
        .topbar-btn{width:34px;height:34px;border:none;background:none;cursor:pointer;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#64748b;transition:background .15s;}
        .topbar-btn:hover{background:#f1f4ff;color:#4f46e5;}

        .store-badge{display:flex;align-items:center;justify-content:center;gap:7px;background:#f1f4ff;border:1px solid #c7d2fe;border-radius:999px;padding:6px 14px;font-size:.72rem;font-weight:700;color:#4f46e5;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap;max-width:220px;overflow:hidden;text-overflow:ellipsis;cursor:default;transition:background .15s;}
        .store-badge:hover{background:#e0e7ff;}

        /* ── CONTENT ── */
        .content{padding:28px 32px;flex:1;overflow-y:auto;background:#f0f2f8;}
        .page-title{font-size:2rem;font-weight:800;color:#0f172a;margin-bottom:4px;}
        .page-sub{color:#64748b;font-size:.88rem;margin-bottom:28px;}

        .btn-action{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:10px 20px;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;transition:opacity .2s,transform .15s;box-shadow:0 4px 14px rgba(79,70,229,.3);}
        .btn-action:hover{opacity:.9;transform:translateY(-1px);}

        /* ── TABLES ── */
        .table-card{background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid #e8eaf0;overflow:hidden;margin-top:24px;}
        .data-table{width:100%;border-collapse:collapse;text-align:left;}
        .data-table th{background:#f8fafc;padding:16px 20px;font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e8eaf0;letter-spacing: 0.05em;}
        .data-table td{padding:18px 20px;font-size:.82rem;border-bottom:1px solid #f1f5f9;color:#334155;vertical-align: middle;}
        .data-table tr:last-child td{border-bottom:none;}
        .data-table tr:hover{background:#f8faff;}

        /* ── FORMS ── */
        .form-card{background:#fff;border-radius:14px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid #e8eaf0;max-width:800px;margin-top:24px;}
        .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;}
        .form-group{margin-bottom:20px;}
        .form-label{display:block;font-size:.85rem;font-weight:600;color:#475569;margin-bottom:8px;}
        .form-input{width:100%;padding:12px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.9rem;outline:none;transition:border-color .2s;}
        .form-input:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.1);}

        /* ── ALERTS ── */
        .alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 20px;border-radius:10px;margin-bottom:20px;font-size:.88rem;}

        .btn-edit{color:#4f46e5;text-decoration:none;font-weight:600;margin-right:12px;}
        .btn-delete{color:#ef4444;background:none;border:none;cursor:pointer;font-weight:600;font-family:inherit;}

        /* ── Mobile drawer (≤900px) ── */
        .sidebar-backdrop{display:none;position:fixed;inset:0;background:rgba(15,23,42,.48);z-index:90;opacity:0;transition:opacity .2s;pointer-events:none;-webkit-tap-highlight-color:transparent;}
        .sidebar-backdrop.is-visible{display:block;opacity:1;pointer-events:auto;}
        body.sidebar-drawer-open{overflow:hidden;touch-action:none;}

        @media (max-width:900px){
            .sidebar{width:min(288px,86vw)!important;transform:translateX(-100%);box-shadow:none;transition:transform .28s ease,box-shadow .28s ease;}
            .sidebar.is-open{transform:translateX(0)!important;box-shadow:8px 0 32px rgba(0,0,0,.14);}
            .sidebar.collapsed{width:min(288px,86vw)!important;}
            .sidebar .nav-item span{display:inline!important;}
            .sidebar.collapsed .nav-item{justify-content:flex-start;padding:9px 16px;}
            .sidebar.collapsed .nav-item span{display:inline!important;}
            .sidebar .logo-text{opacity:1!important;width:auto!important;max-width:none;}
            .sidebar.collapsed .logo-text{opacity:1!important;width:auto!important;}
            .main-wrap,.main-wrap.expanded{margin-left:0!important;width:100%;max-width:100%;min-width:0;}
            .topbar{padding:0 12px;gap:8px;height:56px;}
            .topbar-toggle{min-width:44px;min-height:44px;flex-shrink:0;}
            .topbar-title{font-size:.88rem;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
            .store-badge{max-width:min(140px,38vw);padding:5px 10px;font-size:.62rem;}
            .content{padding:16px 14px;}
            .page-title{font-size:1.35rem;line-height:1.2;}
            .page-sub{font-size:.82rem;margin-bottom:18px;}
            .form-card{padding:22px 16px;margin-top:16px;border-radius:12px;}
            .form-grid{grid-template-columns:1fr!important;gap:14px;}
            .table-card{margin-top:16px;border-radius:12px;overflow-x:auto;-webkit-overflow-scrolling:touch;}
            .data-table{min-width:520px;}
            .data-table th,.data-table td{padding:12px 12px;font-size:.76rem;}
        }
    </style>
    @yield('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <span class="logo-text">Management System</span>
    </div>

    <div class="nav-section">
        <a href="{{ route('targets.index') }}" class="nav-item {{ request()->routeIs('targets.*') ? 'active' : '' }}">
            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Targets</span>
        </a>
        <a href="{{ route('daily-report.index') }}" class="nav-item {{ request()->routeIs('daily-report.*') ? 'active' : '' }}">
            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Daily Report</span>
        </a>
        <a href="{{ route('monthly-summary.index') }}" class="nav-item {{ request()->routeIs('monthly-summary.*') ? 'active' : '' }}">
            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Monthly Summary</span>
        </a>
        <a href="{{ route('targets.createRode') }}" class="nav-item {{ request()->routeIs('targets.createRode') ? 'active' : '' }}">
            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Rode</span>
        </a>
        <a href="{{ route('targets.createSR') }}" class="nav-item {{ request()->routeIs('targets.createSR') ? 'active' : '' }}">
            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Add SR</span>
        </a>
    </div>
</aside>

<div class="sidebar-backdrop" id="sidebar-backdrop" aria-hidden="true"></div>

<div class="main-wrap" id="main-wrap">
    <header class="topbar">
        <button class="topbar-toggle" id="toggle-btn">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <span class="topbar-title">@yield('title')</span>
        
        <div class="topbar-actions">
            @php
                $contextLabel = match (true) {
                    request()->routeIs('monthly-summary.*') => 'Monthly Summary',
                    request()->routeIs('daily-report.*') => 'Daily Report',
                    request()->routeIs('targets.index') => 'Targets',
                    request()->routeIs('targets.create') => 'Add Target',
                    request()->routeIs('targets.edit') => 'Edit Target',
                    request()->routeIs('targets.show') => 'Target',
                    request()->routeIs('targets.createRode') => 'Add Rode',
                    request()->routeIs('targets.createSR') => 'Add SR',
                    request()->routeIs('targets.*') => 'Targets',
                    default => 'Store 1',
                };
            @endphp
            <div class="store-badge" title="{{ $contextLabel }}">{{ strtoupper($contextLabel) }}</div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="topbar-btn" title="Logout">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <div class="content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const mainWrap = document.getElementById('main-wrap');
        const toggleBtn = document.getElementById('toggle-btn');
        const backdrop = document.getElementById('sidebar-backdrop');
        const mq = window.matchMedia('(max-width: 900px)');

        function isMobileLayout() {
            return mq.matches;
        }

        function openDrawer() {
            sidebar.classList.add('is-open');
            if (backdrop) {
                backdrop.classList.add('is-visible');
                backdrop.setAttribute('aria-hidden', 'false');
            }
            document.body.classList.add('sidebar-drawer-open');
        }

        function closeDrawer() {
            sidebar.classList.remove('is-open');
            if (backdrop) {
                backdrop.classList.remove('is-visible');
                backdrop.setAttribute('aria-hidden', 'true');
            }
            document.body.classList.remove('sidebar-drawer-open');
        }

        toggleBtn.addEventListener('click', function () {
            if (isMobileLayout()) {
                if (sidebar.classList.contains('is-open')) {
                    closeDrawer();
                } else {
                    openDrawer();
                }
                return;
            }
            closeDrawer();
            sidebar.classList.toggle('collapsed');
            mainWrap.classList.toggle('expanded');
        });

        if (backdrop) {
            backdrop.addEventListener('click', closeDrawer);
        }

        mq.addEventListener('change', function (e) {
            if (!e.matches) {
                closeDrawer();
            }
        });

        document.querySelectorAll('.sidebar .nav-item').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobileLayout()) {
                    closeDrawer();
                }
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isMobileLayout() && sidebar.classList.contains('is-open')) {
                closeDrawer();
            }
        });
    })();
</script>
@yield('scripts')
</body>
</html>
