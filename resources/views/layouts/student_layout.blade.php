<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal - Acadova')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --secondary: #00B894;
            --dark: #0F172A;
            --light-bg: #F8FAFC;
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
            background-color: var(--light-bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .student-navbar {
            height: 72px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: white;
            border-radius: 10px;
            padding: 3px;
            border: 1px solid var(--border);
        }

        .brand-logo img {
            width: 100%; height: 100%; object-fit: contain;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }

        .btn-logout {
            background: #FFF5F5;
            color: #E53E3E;
            border: 1px solid #FEB2B2;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: #FED7D7;
        }

        .main-container {
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            padding: 32px 24px 60px;
            flex: 1;
        }

        /* Buttons */
        .btn {
            padding: 12px 22px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
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

        .btn-success {
            background: var(--secondary);
            color: white;
        }

        .btn-secondary {
            background: #EDF2F7;
            color: var(--dark);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--dark);
            color: white;
            padding: 14px 22px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 9999;
        }
    </style>
    @yield('styles')
</head>
<body>

    <header class="student-navbar">
        <a href="{{ route('student.dashboard') }}" class="brand-link">
            <div class="brand-logo">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
            </div>
            <span class="brand-name">Acadova Student</span>
        </a>

        <div class="user-menu">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}</div>
                <div>
                    <div style="font-weight: 800; font-size: 14px; color: var(--dark);">{{ Auth::user()->name }}</div>
                    <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 600;">{{ Auth::user()->roll_number ?? Auth::user()->email }}</div>
                </div>
            </div>

            <form action="{{ route('student.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <main class="main-container">
        @yield('content')
    </main>

    <div class="toast" id="toast">
        <i class="fa-solid fa-circle-check" style="color: #10B981;"></i>
        <span id="toastMessage">Action completed</span>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
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
