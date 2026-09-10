<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acadova Admin Portal')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --primary-light: #EEF2FF;
            --secondary: #00B894;
            --danger: #FF7675;
            --warning: #FFA502;
            --dark: #2D3436;
            --bg: #F8F9FA;
            --card-bg: #FFFFFF;
            --border: #E2E8F0;
            --text-muted: #64748B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 270px;
            background: #FFFFFF;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 24px 18px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding: 0 10px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 6px 16px rgba(108, 92, 231, 0.35);
        }

        .brand-text h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .brand-text span {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94A3B8;
            letter-spacing: 1px;
            margin: 18px 12px 6px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item.active a, .nav-item a:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            background: #F1F5F9;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-footer-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Main Wrapper & Top Header */
        .main-wrapper {
            flex: 1;
            margin-left: 270px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            height: 72px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 36px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
        }

        .status-badge {
            padding: 5px 12px;
            background: #E6FFFA;
            color: #047857;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge .dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .user-profile-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px;
            background: var(--bg);
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
        }

        .btn-logout {
            padding: 8px 14px;
            border-radius: 12px;
            background: #FFF5F5;
            color: var(--danger);
            border: 1px solid #FED7D7;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* Mobile Responsive */
        .mobile-toggle {
            display: none;
            background: #F1F5F9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 18px;
            color: var(--dark);
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(3px);
            z-index: 95;
            display: none;
        }

        @media (max-width: 992px) {
            .mobile-toggle { display: flex; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .main-wrapper { margin-left: 0; }
            .header { padding: 0 16px; }
            .content { padding: 20px 16px; }
        }

        .content {
            padding: 36px;
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        /* Generic Component Cards & Buttons */
        .btn {
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px rgba(108, 92, 231, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-secondary { background: #EDF2F7; color: var(--dark); }
        .btn-secondary:hover { background: #E2E8F0; }
        .btn-danger { background: var(--danger); color: white; }

        /* Tables & Modals */
        .table-card {
            background: white;
            border-radius: 18px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #F8FAFC; padding: 16px 20px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 16px 20px; font-size: 14px; color: var(--dark); border-bottom: 1px solid #F1F5F9; }
        tr:hover td { background: #F8FAFC; }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.active { display: flex; }

        .modal-container {
            background: white;
            border-radius: 20px;
            max-width: 650px;
            width: 100%;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .modal-title { font-size: 20px; font-weight: 800; color: var(--dark); }
        .close-btn { background: #EDF2F7; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--text-muted); }

        .form-group { margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 576px) { .form-row { grid-template-columns: 1fr; } }
        label { display: block; font-size: 13px; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); font-size: 14px; outline: none; transition: all 0.2s ease; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15); }

        /* Toast notifications */
        .toast {
            position: fixed; bottom: 24px; right: 24px; background: var(--dark); color: white; padding: 14px 24px; border-radius: 14px; font-size: 14px; font-weight: 600; box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 2000; display: none; align-items: center; gap: 10px;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon" style="background: white; overflow: hidden; padding: 4px; box-shadow: 0 6px 16px rgba(108, 92, 231, 0.25); border: 1px solid var(--border);">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text">
                <h2>Acadova</h2>
                <span style="color: #00B894; font-size: 10px;">POWERED BY NEODY IT</span>
            </div>
        </div>

        <div class="nav-section-title">Navigation</div>
        <ul class="nav-menu">
            <li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/quizzes*') ? 'active' : '' }}">
                <a href="{{ route('admin.quizzes') }}">
                    <i class="fa-solid fa-layer-group"></i> Quizzes
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/campaigns*') ? 'active' : '' }}">
                <a href="{{ route('admin.campaigns') }}">
                    <i class="fa-solid fa-bullhorn"></i> Campaigns & Notices
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users') }}">
                    <i class="fa-solid fa-users"></i> Users Directory
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/media*') ? 'active' : '' }}">
                <a href="{{ route('admin.media') }}">
                    <i class="fa-solid fa-folder-open"></i> Media Library
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}">
                    <i class="fa-solid fa-sliders"></i> System Settings
                </a>
            </li>
        </ul>

        <div class="sidebar-footer" style="background: #F1F5F9; border: 1px solid var(--border);">
            <div class="sidebar-footer-icon" style="background: #00B894;">
                <i class="fa-solid fa-microchip"></i>
            </div>
            <div style="font-size: 12px;">
                <div style="font-weight: 800; color: var(--dark);">Neody IT Suite</div>
                <div style="color: var(--text-muted); font-size: 11px;">Acadova Core v2026.3</div>
            </div>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="header">
            <div class="header-left">
                <button class="mobile-toggle" onclick="toggleMobileSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="header-title">@yield('page_title', 'Dashboard')</h1>
                <div class="status-badge">
                    <span class="dot"></span> Session Secured
                </div>
            </div>
            <div class="user-profile-menu">
                <div class="user-badge">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                    <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout" title="Sign Out">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Global Toast -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-circle-check" style="color: #10B981;"></i>
        <span id="toastMessage">Action completed successfully</span>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function toggleMobileSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').innerText = msg;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }
    </script>
    @yield('scripts')
</body>
</html>
