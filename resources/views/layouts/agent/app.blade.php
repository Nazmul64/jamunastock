<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $site_name = setting('title') ?: config('app.name', 'JAMUNA STOCK MARKET PLC');
        $site_favicon = setting('favicon') ? asset('upload/images/backend/logo/' . setting('favicon')) : (setting('logo') ? asset('upload/images/backend/logo/' . setting('logo')) : asset('images/favicon.ico'));
    @endphp

    <link rel="icon" type="image/x-icon" href="{{ $site_favicon }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $site_favicon }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Agent Portal') - {{ $site_name }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/themify-icons/themify-icons.css">

    <style>
        :root {
            --agent-bg: #0b1329;
            --agent-card-bg: #111c44;
            --agent-sidebar-bg: #0c1427;
            --agent-border: rgba(255, 255, 255, 0.08);
            --agent-text: #ffffff;
            --agent-muted: #8a99b5;
            --agent-primary: #3b82f6;
            --agent-success: #10b981;
            --agent-warning: #f59e0b;
            --agent-danger: #ef4444;
            --agent-purple: #8b5cf6;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
            background-color: #080e1e !important;
            color: #e2e8f0 !important;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .agent-navbar {
            background: rgba(12, 20, 39, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--agent-border);
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .agent-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
        }

        .agent-point-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #34d399;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
        }

        .agent-layout {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        /* Sidebar */
        .agent-sidebar {
            width: 260px;
            background: var(--agent-sidebar-bg);
            border-right: 1px solid var(--agent-border);
            padding: 20px 14px;
            flex-shrink: 0;
        }

        .agent-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 12px;
            color: var(--agent-muted);
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .agent-nav-link i {
            font-size: 17px;
            width: 22px;
            text-align: center;
        }

        .agent-nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .agent-nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
        }

        .agent-nav-category {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            padding: 14px 16px 6px;
        }

        /* Content Body */
        .agent-content {
            flex: 1;
            padding: 28px;
            min-width: 0;
            background: #080e1e;
        }

        /* Common Dark Card */
        .dark-card {
            background: #111c44;
            border: 1px solid var(--agent-border);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .dark-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .dark-card-title {
            font-size: 17px;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Custom Dark Inputs */
        .dark-input {
            background: #0c1427 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
        }

        .dark-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
            outline: none !important;
        }

        /* Dark Table */
        .dark-table {
            width: 100%;
            margin-bottom: 0;
            color: #cbd5e1;
            border-collapse: separate;
            border-spacing: 0;
        }

        .dark-table th {
            background: rgba(12, 20, 39, 0.6);
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--agent-border);
        }

        .dark-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13.5px;
            vertical-align: middle;
        }

        .dark-table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .status-pill.success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-pill.warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .status-pill.danger  { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .status-pill.info    { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }

        @media (max-width: 991px) {
            .agent-sidebar { display: none; }
            .agent-content { padding: 16px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Navigation Bar -->
    <header class="agent-navbar">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('agent.dashboard') }}" class="agent-brand">
                <i class="fa fa-cubes text-primary fs-3"></i>
                <div>
                    <h5 class="mb-0 fw-bold" style="font-size: 16px; color: #fff;">{{ $site_name }}</h5>
                    <small style="font-size: 11px; color: #94a3b8;">Marketing & Distribution Agent Portal</small>
                </div>
            </a>

            @if(isset($agentPoint))
            <span class="agent-point-pill ms-2">
                <i class="fa fa-map-marker"></i> {{ $agentPoint->name }} ({{ $agentPoint->area }})
            </span>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary text-white" style="border-radius: 8px;">
                <i class="fa fa-external-link me-1"></i> Website
            </a>

            <div class="dropdown">
                <button class="btn btn-sm btn-dark dropdown-toggle text-white d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" style="border-radius: 8px; border: 1px solid var(--agent-border);">
                    <i class="fa fa-user-circle fs-5 text-warning"></i>
                    <span>{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="background: #111c44; border: 1px solid var(--agent-border);">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                <i class="fa fa-sign-out"></i> সাইন আউট (Logout)
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="agent-layout">
        <!-- Sidebar Navigation -->
        <aside class="agent-sidebar">
            <div class="agent-nav-category">মেইন মেনু (Main Menu)</div>
            <a href="{{ route('agent.dashboard') }}" class="agent-nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                <i class="fa fa-dashboard text-primary"></i>
                <span>ড্যাশবোর্ড (Dashboard)</span>
            </a>
            <a href="{{ route('agent.stock') }}" class="agent-nav-link {{ request()->routeIs('agent.stock') ? 'active' : '' }}">
                <i class="fa fa-cubes text-success"></i>
                <span>পণ্য স্টক লেজার (Stock)</span>
            </a>
            <a href="{{ route('agent.supplies.index') }}" class="agent-nav-link {{ request()->routeIs('agent.supplies*') ? 'active' : '' }}">
                <i class="fa fa-inbox text-info"></i>
                <span>ইনকামিং সাপ্লাই (Supplier)</span>
            </a>
            <a href="{{ route('agent.dispatches') }}" class="agent-nav-link {{ request()->routeIs('agent.dispatches*') ? 'active' : '' }}">
                <i class="fa fa-truck text-warning"></i>
                <span>ইনকামিং ডিসপ্যাচ (Receive)</span>
            </a>

            <div class="agent-nav-category">ডেলিভারি ও সদস্য (Delivery & Members)</div>
            <a href="{{ route('agent.members') }}" class="agent-nav-link {{ request()->routeIs('agent.members*') ? 'active' : '' }}">
                <i class="fa fa-id-card text-info"></i>
                <span>নিবন্ধিত কার্ড হোল্ডার</span>
            </a>
            <a href="{{ route('agent.requests') }}" class="agent-nav-link {{ request()->routeIs('agent.requests*') ? 'active' : '' }}">
                <i class="fa fa-shopping-basket text-purple"></i>
                <span>মাসিক বাজার রিকোয়েস্ট</span>
            </a>
            <a href="{{ route('agent.delivery_history') }}" class="agent-nav-link {{ request()->routeIs('agent.delivery_history*') ? 'active' : '' }}">
                <i class="fa fa-history text-danger"></i>
                <span>ডেলিভারি হিস্ট্রি (History)</span>
            </a>

            <div class="agent-nav-category">সিস্টেম</div>
            <a href="{{ url('/') }}" class="agent-nav-link">
                <i class="fa fa-home text-muted"></i>
                <span>হোমপেজ (Website)</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="agent-nav-link border-0 w-100 text-start bg-transparent text-danger">
                    <i class="fa fa-sign-out"></i>
                    <span>লগআউট (Logout)</span>
                </button>
            </form>
        </aside>

        <!-- Main Content Area -->
        <main class="agent-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; border-radius: 12px;">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; border-radius: 12px;">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.4); color: #60a5fa; border-radius: 12px;">
                    <i class="fa fa-info-circle me-2"></i> {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')
</body>
</html>
