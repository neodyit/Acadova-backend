<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova Admin Portal - Modern Dashboard & Management</title>
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

        /* Sidebar */
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

        .nav-item.active a i {
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

        /* Main Content Area */
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

        /* Mobile Hamburger & Overlay */
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
            transition: all 0.2s ease;
        }

        .mobile-toggle:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(3px);
            z-index: 95;
            display: none;
        }

        @media (max-width: 992px) {
            .mobile-toggle {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .header {
                padding: 0 16px;
            }

            .content {
                padding: 20px 16px;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .action-bar > div {
                width: 100%;
            }

            .action-bar .btn, .action-bar .form-control {
                width: 100%;
                max-width: 100% !important;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
                gap: 12px;
            }

            .stat-icon {
                width: 42px;
                height: 42px;
                font-size: 18px;
            }

            .stat-info h3 {
                font-size: 20px;
            }

            .quiz-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .filter-tabs {
                overflow-x: auto;
                padding-bottom: 8px;
                white-space: nowrap;
            }

            .user-badge span {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .modal-container {
                padding: 24px 16px;
            }
            .header-title {
                font-size: 16px;
            }
        }

        .content {
            padding: 36px;
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        .page-section {
            display: none;
            animation: fadeIn 0.25s ease-out;
        }

        .page-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

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
            box-shadow: 0 6px 18px rgba(108, 92, 231, 0.4);
        }

        .btn-secondary {
            background: #EDF2F7;
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            opacity: 0.9;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 22px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-info h3 {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .stat-info p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 2px;
        }

        /* Quiz & Card Grids */
        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .quiz-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: all 0.2s ease;
        }

        .quiz-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 24px rgba(108, 92, 231, 0.12);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-active { background: #DEF7EC; color: #03543F; }
        .badge-upcoming { background: #FEF08A; color: #713F12; }
        .badge-completed { background: #EDF2F7; color: #4A5568; }

        .quiz-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .quiz-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 18px;
            font-weight: 500;
        }

        .quiz-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 20px;
            padding: 12px;
            background: var(--bg);
            border-radius: 12px;
        }

        .quiz-actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }

        .quiz-actions .btn {
            flex: 1;
            justify-content: center;
            padding: 10px 14px;
            font-size: 13px;
        }

        /* Data Tables */
        .table-card {
            background: white;
            border-radius: 18px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #F8FAFC;
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 16px 20px;
            font-size: 14px;
            color: var(--dark);
            border-bottom: 1px solid #F1F5F9;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #F8FAFC;
        }

        /* Modals */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            max-width: 650px;
            width: 100%;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
        }

        .close-btn {
            background: #EDF2F7;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--text-muted);
        }

        .close-btn:hover { background: #E2E8F0; }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--dark);
            color: white;
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 2000;
            display: none;
            align-items: center;
            gap: 10px;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        .tab-btn {
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: var(--primary-light);
            color: var(--primary);
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="brand-text">
                <h2>Acadova</h2>
                <span>ADMIN PANEL</span>
            </div>
        </div>

        <div class="nav-section-title">Navigation</div>
        <ul class="nav-menu">
            <li class="nav-item" id="nav-dashboard">
                <a href="/admin/dashboard" onclick="navigateToSection('dashboard', event)">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li class="nav-item" id="nav-quizzes">
                <a href="/admin/quizzes" onclick="navigateToSection('quizzes', event)">
                    <i class="fa-solid fa-layer-group"></i> Quizzes
                </a>
            </li>
            <li class="nav-item" id="nav-campaigns">
                <a href="/admin/campaigns" onclick="navigateToSection('campaigns', event)">
                    <i class="fa-solid fa-bullhorn"></i> Campaigns & Notices
                </a>
            </li>
            <li class="nav-item" id="nav-users">
                <a href="/admin/users" onclick="navigateToSection('users', event)">
                    <i class="fa-solid fa-users"></i> Users Directory
                </a>
            </li>
            <li class="nav-item" id="nav-media">
                <a href="/admin/media" onclick="navigateToSection('media', event)">
                    <i class="fa-solid fa-folder-open"></i> Media Library
                </a>
            </li>
            <li class="nav-item" id="nav-settings">
                <a href="/admin/settings" onclick="navigateToSection('settings', event)">
                    <i class="fa-solid fa-sliders"></i> System Settings
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="sidebar-footer-icon">
                <i class="fa-solid fa-server"></i>
            </div>
            <div style="font-size: 12px;">
                <div style="font-weight: 700; color: var(--dark);">Acadova Engine</div>
                <div style="color: var(--text-muted); font-size: 11px;">v2026.1 • Production</div>
            </div>
        </div>
    </aside>

    <!-- Mobile Backdrop Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Top App Bar Header -->
        <header class="header">
            <div class="header-left">
                <button class="mobile-toggle" onclick="toggleMobileSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="header-title" id="pageTitle">Dashboard</h1>
                <div class="status-badge">
                    <span class="dot"></span> Backend Live
                </div>
            </div>
            <div class="user-badge">
                <div class="user-avatar">A</div>
                <span>Administrator</span>
            </div>
        </header>

        <!-- Dynamic Content Sections -->
        <div class="content">

            <!-- 1. DASHBOARD OVERVIEW SECTION -->
            <div class="page-section" id="section-dashboard">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">Overview Analytics</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Live summary of student enrollment, active quizzes, and performance metrics.</p>
                    </div>
                </div>

                <!-- Aggregate Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #EEF2FF; color: var(--primary);">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalStudents">0</h3>
                            <p>Registered Students</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #E6FFFA; color: var(--secondary);">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalQuizzes">0</h3>
                            <p>Total Quizzes</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FEF3C7; color: var(--warning);">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statActiveCampaigns">0</h3>
                            <p>Active Campaigns</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #FEE2E2; color: var(--danger);">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="statTotalAttempts">0</h3>
                            <p>Quiz Attempts</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div class="table-card" style="margin-top: 24px;">
                    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 16px; font-weight: 800;">Recent Quiz Submissions</h3>
                        <a href="/admin/users" onclick="navigateToSection('users', event)" style="color: var(--primary); font-size: 13px; font-weight: 700; text-decoration: none;">View All Students &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Quiz Title</th>
                                    <th>Score Earned</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </thead>
                            <tbody id="dashboardAttemptsBody">
                                <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Loading live attempts...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. QUIZZES SECTION -->
            <div class="page-section" id="section-quizzes">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">Manage Quizzes</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Create, schedule, and configure dynamic single/multiple choice quizzes.</p>
                    </div>
                    <button class="btn btn-primary" onclick="openCreateQuizModal()">
                        <i class="fa-solid fa-plus"></i> Create New Quiz
                    </button>
                </div>

                <div class="filter-tabs">
                    <button class="tab-btn active" onclick="filterQuizzes('all', this)">All Quizzes</button>
                    <button class="tab-btn" onclick="filterQuizzes('active', this)">Active (Live)</button>
                    <button class="tab-btn" onclick="filterQuizzes('upcoming', this)">Upcoming</button>
                    <button class="tab-btn" onclick="filterQuizzes('completed', this)">Completed</button>
                </div>

                <div class="quiz-grid" id="quizGrid">
                    <!-- Rendered dynamically -->
                </div>
            </div>

            <!-- 3. CAMPAIGNS SECTION -->
            <div class="page-section" id="section-campaigns">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">Manage Campaigns & Notices</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Broadcast banners, announcements, and promotional notices to the mobile app.</p>
                    </div>
                    <button class="btn btn-primary" onclick="openCreateCampaignModal()">
                        <i class="fa-solid fa-plus"></i> Create Campaign
                    </button>
                </div>

                <div class="quiz-grid" id="campaignGrid">
                    <!-- Rendered dynamically -->
                </div>
            </div>

            <!-- 4. USERS DIRECTORY SECTION -->
            <div class="page-section" id="section-users">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">Users Directory & Management</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Manage student & faculty accounts, edit profiles, and reset passwords.</p>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <input type="text" id="userSearchInput" onkeyup="searchUsers()" placeholder="Search name, email, dept..." class="form-control" style="max-width: 260px;">
                        <button class="btn btn-primary" onclick="openCreateUserModal()">
                            <i class="fa-solid fa-user-plus"></i> Add New User
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>User Details</th>
                                    <th>Role</th>
                                    <th>Roll / Faculty ID</th>
                                    <th>Department</th>
                                    <th>Quizzes Taken</th>
                                    <th>Joined Date</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Loading users directory...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. MEDIA LIBRARY SECTION -->
            <div class="page-section" id="section-media">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">Media Library & Storage</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Upload media files into structured directories (avatars, campaigns, documents).</p>
                    </div>
                </div>

                <div class="table-card" style="padding: 24px; margin-bottom: 28px;">
                    <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 14px;"><i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary);"></i> Upload New File</h3>
                    <form id="mediaUploadForm" onsubmit="handleUploadMedia(event)" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                        <input type="file" id="mediaFileInput" class="form-control" style="max-width: 320px;" required>
                        <select id="mediaFolderSelect" class="form-control" style="max-width: 180px;">
                            <option value="general">General</option>
                            <option value="avatars">Avatars</option>
                            <option value="campaigns">Campaigns</option>
                            <option value="questions">Questions</option>
                            <option value="documents">Documents</option>
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload Media</button>
                    </form>
                </div>

                <div class="quiz-grid" id="mediaGrid">
                    <!-- Rendered dynamically -->
                </div>
            </div>

            <!-- 6. SYSTEM SETTINGS SECTION -->
            <div class="page-section" id="section-settings">
                <div class="action-bar">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 800;">System Settings & Status</h1>
                        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Server environmental variables, database connectivity, and sample downloads.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                    <div class="quiz-card">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <div style="width: 40px; height: 40px; background: #EEF2FF; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 800;">MySQL Database</h3>
                                <span style="font-size: 12px; color: #10B981; font-weight: 700;">● Connected</span>
                            </div>
                        </div>
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Production Hostinger MySQL instance storing users, quizzes, attempts, and campaigns.</p>
                        <div style="font-size: 12px; background: var(--bg); padding: 12px; border-radius: 10px;">
                            <div><strong>Host:</strong> localhost</div>
                            <div><strong>Database:</strong> u990377294_Acadova</div>
                        </div>
                    </div>

                    <div class="quiz-card">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <div style="width: 40px; height: 40px; background: #E6FFFA; color: var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                <i class="fa-solid fa-file-csv"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 800;">CSV Question Templates</h3>
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Bulk Import Format</span>
                            </div>
                        </div>
                        <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Download pre-formatted CSV template for single & multiple answer questions.</p>
                        <a href="/sample-csv" download="sample_questions.csv" class="btn btn-secondary" style="justify-content: center;">
                            <i class="fa-solid fa-download"></i> Download Sample CSV
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modals -->

    <!-- Create / Edit Quiz Modal -->
    <div class="modal-overlay" id="createQuizModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title" id="quizModalTitleText">Create New Quiz</div>
                <button class="close-btn" onclick="closeModal('createQuizModal')">&times;</button>
            </div>
            <form id="createQuizForm" onsubmit="handleSaveQuiz(event)">
                <input type="hidden" id="editingQuizId">
                <div class="form-group">
                    <label>Quiz Title</label>
                    <input type="text" id="quizTitle" class="form-control" placeholder="e.g. Operating Systems Scheduling Quiz" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Subject / Category</label>
                        <input type="text" id="quizSubject" class="form-control" placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="form-group">
                        <label>Instructor Name</label>
                        <input type="text" id="quizInstructor" class="form-control" placeholder="e.g. Dr. Aman" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Duration (Minutes)</label>
                        <input type="number" id="quizDuration" class="form-control" value="15" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="quizStatus" class="form-control" onchange="toggleScheduledDateInput()">
                            <option value="active">Active (Live for Students)</option>
                            <option value="upcoming">Upcoming (Scheduled)</option>
                            <option value="completed">Completed / Archived</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="scheduledGroup" style="display: none;">
                    <label><i class="fa-regular fa-clock"></i> Schedule Date & Time</label>
                    <input type="datetime-local" id="quizScheduledAt" class="form-control">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="quizDescription" class="form-control" rows="3" placeholder="Brief instructions for students..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createQuizModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Quiz</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create / Edit Campaign Modal -->
    <div class="modal-overlay" id="createCampaignModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title" id="campaignModalTitleText">Create Campaign / Notice</div>
                <button class="close-btn" onclick="closeModal('createCampaignModal')">&times;</button>
            </div>
            <form id="createCampaignForm" onsubmit="handleSaveCampaign(event)">
                <input type="hidden" id="editingCampaignId">
                <div class="form-group">
                    <label>Campaign Title</label>
                    <input type="text" id="campaignTitle" class="form-control" placeholder="e.g. 🏆 Annual Tech Quiz League 2026" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Badge / Category</label>
                        <input type="text" id="campaignBadge" class="form-control" placeholder="e.g. Featured Event" required>
                    </div>
                    <div class="form-group">
                        <label>Banner Theme Color</label>
                        <select id="campaignColor" class="form-control">
                            <option value="purple">Purple Gradient</option>
                            <option value="orange">Orange / Coral</option>
                            <option value="teal">Teal / Emerald</option>
                            <option value="blue">Ocean Blue</option>
                            <option value="pink">Pink / Rose</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select id="campaignStatus" class="form-control">
                            <option value="active">Active (Visible on Dashboard)</option>
                            <option value="inactive">Inactive (Hidden)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Action Link URL (Optional)</label>
                        <input type="url" id="campaignLink" class="form-control" placeholder="https://example.com/register">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description / Notice Body</label>
                    <textarea id="campaignDescription" class="form-control" rows="3" placeholder="Write detailed notice..." required></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createCampaignModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Campaign</button>
                </div>
    </div>

    <!-- Create / Edit User Modal -->
    <div class="modal-overlay" id="createUserModal">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title" id="userModalTitleText">Add New User</div>
                <button class="close-btn" onclick="closeModal('createUserModal')">&times;</button>
            </div>
            <form id="createUserForm" onsubmit="handleSaveUser(event)">
                <input type="hidden" id="editingUserId">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="userName" class="form-control" placeholder="e.g. Aman Sharma" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" id="userEmail" class="form-control" placeholder="e.g. aman@acadova.com" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Role</label>
                        <select id="userRole" class="form-control" onchange="toggleUserRoleFields()">
                            <option value="student">Student</option>
                            <option value="faculty">Faculty / Instructor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label id="userRollLabel">Roll Number</label>
                        <input type="text" id="userRollOrFaculty" class="form-control" placeholder="e.g. CS2026001">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Department / Branch</label>
                        <input type="text" id="userDept" class="form-control" placeholder="e.g. Computer Science">
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" id="userPhone" class="form-control" placeholder="e.g. +91 9876543210">
                    </div>
                </div>

                <div class="form-group">
                    <label id="userPasswordLabel">Password</label>
                    <input type="password" id="userPassword" class="form-control" placeholder="Minimum 6 characters">
                    <small id="userPasswordHelp" style="color: var(--text-muted); font-size: 11.5px; display: none;">Leave empty to keep current password.</small>
                </div>

                <div class="form-group">
                    <label>Bio / Note (Optional)</label>
                    <textarea id="userBio" class="form-control" rows="2" placeholder="Student or faculty notes..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Questions Modal -->
    <div class="modal-overlay" id="manageQuestionsModal">
        <div class="modal-container" style="max-width: 800px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="manageModalQuizTitle">Manage Questions</div>
                    <span style="font-size: 13px; color: var(--text-muted);" id="manageModalQuizSub">General Science</span>
                </div>
                <button class="close-btn" onclick="closeModal('manageQuestionsModal')">&times;</button>
            </div>

            <div style="background: var(--bg); border: 1px dashed var(--primary); border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div>
                    <div style="font-weight: 700; font-size: 14px;"><i class="fa-solid fa-file-csv" style="color: var(--primary);"></i> Bulk Import CSV</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Import questions using CSV file. <a href="/sample-csv" download="sample_questions.csv" style="color: var(--primary); font-weight: 700;">Download Template</a></div>
                </div>
                <form id="csvImportForm" style="display: flex; gap: 8px;" onsubmit="handleImportCsv(event)">
                    <input type="file" id="csvFileInput" accept=".csv" class="form-control" style="padding: 6px; font-size: 12px; max-width: 200px;" required>
                    <button type="submit" class="btn btn-secondary" style="font-size: 12px;"><i class="fa-solid fa-upload"></i> Import</button>
                </form>
            </div>

            <div id="questionsList"></div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid var(--border);">

            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--primary);" id="questionFormHeader">
                <i class="fa-solid fa-plus-circle"></i> Add Question
            </h3>
            <form id="addQuestionForm" onsubmit="handleAddOrUpdateQuestion(event)">
                <input type="hidden" id="activeQuizId">
                <input type="hidden" id="editingQuestionId">

                <div class="form-group">
                    <label>Question Text</label>
                    <textarea id="qText" class="form-control" rows="2" placeholder="e.g. What is CPU?" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Question Type</label>
                        <select id="qType" class="form-control" onchange="toggleQuestionTypeUI()">
                            <option value="single">Single Choice (1 Correct Answer)</option>
                            <option value="multiple">Multiple Choice (Multiple Correct Answers)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group"><label>Option 1</label><input type="text" id="qOpt1" class="form-control" required></div>
                    <div class="form-group"><label>Option 2</label><input type="text" id="qOpt2" class="form-control" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Option 3</label><input type="text" id="qOpt3" class="form-control" required></div>
                    <div class="form-group"><label>Option 4</label><input type="text" id="qOpt4" class="form-control" required></div>
                </div>

                <div class="form-group" id="singleChoiceGroup">
                    <label>Correct Option</label>
                    <select id="qCorrectSingle" class="form-control">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select>
                </div>

                <div class="form-group" id="multiChoiceGroup" style="display: none;">
                    <label>Select All Correct Options</label>
                    <div style="display: flex; gap: 16px; margin-top: 8px;">
                        <label><input type="checkbox" value="1" class="multi-check"> Option 1</label>
                        <label><input type="checkbox" value="2" class="multi-check"> Option 2</label>
                        <label><input type="checkbox" value="3" class="multi-check"> Option 3</label>
                        <label><input type="checkbox" value="4" class="multi-check"> Option 4</label>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="resetQuestionForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="saveQuestionBtn">Add Question</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-circle-check" style="color: #10B981;"></i>
        <span id="toastMessage">Action completed successfully</span>
    </div>

    <!-- Scripting -->
    <script>
        const INITIAL_SECTION = "{{ $activeSection ?? 'dashboard' }}";
        let quizzesData = [];
        let campaignsData = [];
        let usersData = [];
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', () => {
            navigateToSection(INITIAL_SECTION, null, false);
            window.addEventListener('popstate', (e) => {
                const path = window.location.pathname.replace('/admin/', '').replace('/admin', '') || 'dashboard';
                navigateToSection(path, null, false);
            });
        });

        function navigateToSection(section, event, updateHistory = true) {
            if (event) event.preventDefault();

            const validSections = ['dashboard', 'quizzes', 'campaigns', 'users', 'media', 'settings'];
            if (!validSections.includes(section)) section = 'dashboard';

            document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));

            const targetSection = document.getElementById(`section-${section}`);
            const targetNav = document.getElementById(`nav-${section}`);

            if (targetSection) targetSection.classList.add('active');
            if (targetNav) targetNav.classList.add('active');

            const pageTitles = {
                dashboard: 'Dashboard Overview',
                quizzes: 'Quizzes Management',
                campaigns: 'Campaigns & Announcements',
                users: 'Users Directory',
                media: 'Media Library & Files',
                settings: 'System & Database Settings'
            };
            document.getElementById('pageTitle').innerText = pageTitles[section] || 'Dashboard';

            if (updateHistory) {
                window.history.pushState({ section }, '', `/admin/${section}`);
            }

            if (window.innerWidth <= 992) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                if (sidebar) sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
            }

            // Load section data
            if (section === 'dashboard') {
                loadDashboardStats();
                loadRecentAttempts();
            } else if (section === 'quizzes') {
                loadQuizzes();
            } else if (section === 'campaigns') {
                loadCampaigns();
            } else if (section === 'users') {
                loadUsers();
            } else if (section === 'media') {
                loadMediaFiles();
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
        }

        async function loadDashboardStats() {
            try {
                const res = await fetch('/api/admin/stats');
                const json = await res.json();
                if (json.success) {
                    document.getElementById('statTotalStudents').innerText = json.data.total_students || 0;
                    document.getElementById('statTotalQuizzes').innerText = json.data.total_quizzes || 0;
                    document.getElementById('statActiveCampaigns').innerText = json.data.active_campaigns || 0;
                    document.getElementById('statTotalAttempts').innerText = json.data.total_attempts || 0;
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function loadRecentAttempts() {
            try {
                const res = await fetch('/api/admin/attempts');
                const json = await res.json();
                const tbody = document.getElementById('dashboardAttemptsBody');
                if (json.success && json.data.length > 0) {
                    tbody.innerHTML = json.data.map(att => `
                        <tr>
                            <td>
                                <strong>${att.student_name}</strong><br>
                                <span style="font-size: 11.5px; color: var(--text-muted);">${att.student_email}</span>
                            </td>
                            <td><strong>${att.quiz_title}</strong></td>
                            <td><span class="badge badge-active">${att.score} / ${att.total_questions}</span></td>
                            <td>${att.created_at}</td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No attempts logged yet.</td></tr>`;
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function loadQuizzes() {
            try {
                const res = await fetch('/api/quizzes');
                const json = await res.json();
                quizzesData = Array.isArray(json) ? json : (json.data || []);
                renderQuizzes();
            } catch (e) {
                showToast('Failed to load quizzes');
            }
        }

        function renderQuizzes() {
            const grid = document.getElementById('quizGrid');
            let filtered = quizzesData;
            if (currentFilter !== 'all') {
                filtered = quizzesData.filter(q => q.status === currentFilter);
            }

            if (filtered.length === 0) {
                grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No ${currentFilter} quizzes found.</div>`;
                return;
            }

            grid.innerHTML = filtered.map(q => `
                <div class="quiz-card">
                    <div class="quiz-header">
                        <span class="badge badge-${q.status}">${q.status}</span>
                        <span style="font-size: 12px; font-weight: 700; color: var(--primary);">${q.subject || 'General'}</span>
                    </div>
                    <div class="quiz-title">${q.title}</div>
                    <div class="quiz-sub">${q.description || 'No description'}</div>
                    <div class="quiz-meta">
                        <span><i class="fa-regular fa-clock"></i> ${q.duration} mins</span>
                        <span><i class="fa-solid fa-list"></i> ${q.questions_count || 0} Questions</span>
                    </div>
                    <div class="quiz-actions">
                        <button class="btn btn-secondary" onclick="openManageQuestionsModal(${q.id}, '${escapeJs(q.title)}', '${escapeJs(q.subject || 'General')}')">Questions</button>
                        <button class="btn btn-secondary" onclick="editQuiz(${q.id})"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-danger" onclick="deleteQuiz(${q.id})"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `).join('');
        }

        function filterQuizzes(status, btn) {
            currentFilter = status;
            document.querySelectorAll('.filter-tabs .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderQuizzes();
        }

        async function loadCampaigns() {
            try {
                const res = await fetch('/api/campaigns');
                const json = await res.json();
                campaignsData = json.data || json || [];
                renderCampaigns();
            } catch (e) {
                showToast('Failed to load campaigns');
            }
        }

        function renderCampaigns() {
            const grid = document.getElementById('campaignGrid');
            if (campaignsData.length === 0) {
                grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No campaigns found.</div>`;
                return;
            }

            grid.innerHTML = campaignsData.map(c => `
                <div class="quiz-card">
                    <div class="quiz-header">
                        <span class="badge badge-${c.status === 'active' ? 'active' : 'completed'}">${c.status}</span>
                        <span style="font-size: 12px; font-weight: 700; color: var(--primary);">${c.badge || 'Notice'}</span>
                    </div>
                    <div class="quiz-title">${c.title}</div>
                    <div class="quiz-sub">${c.description}</div>
                    <div class="quiz-actions">
                        <button class="btn btn-secondary" onclick="toggleCampaignStatus(${c.id}, '${c.status === 'active' ? 'inactive' : 'active'}')">${c.status === 'active' ? 'Hide' : 'Activate'}</button>
                        <button class="btn btn-danger" onclick="deleteCampaign(${c.id})"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            `).join('');
        }

        async function loadUsers() {
            try {
                const res = await fetch('/api/admin/users');
                const json = await res.json();
                usersData = json.data || [];
                renderUsersTable(usersData);
            } catch (e) {
                showToast('Failed to load users');
            }
        }

        function renderUsersTable(list) {
            const tbody = document.getElementById('usersTableBody');
            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-muted);">No users found.</td></tr>`;
                return;
            }

            tbody.innerHTML = list.map(u => `
                <tr>
                    <td>
                        <strong>${u.name}</strong><br>
                        <span style="font-size: 11.5px; color: var(--text-muted);">${u.email}</span>
                    </td>
                    <td><span class="badge ${u.role === 'STUDENT' ? 'badge-active' : 'badge-upcoming'}">${u.role}</span></td>
                    <td><strong>${u.roll_number || u.faculty_id || 'N/A'}</strong></td>
                    <td>${u.department || 'Not Specified'}</td>
                    <td><strong>${u.attempts_count}</strong> Quizzes</td>
                    <td>${u.created_at}</td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px;" onclick="editUser(${u.id})"><i class="fa-solid fa-pen"></i> Edit</button>
                        <button class="btn btn-danger" style="padding: 6px 10px; font-size: 12px;" onclick="deleteUser(${u.id})"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        function searchUsers() {
            const query = document.getElementById('userSearchInput').value.toLowerCase();
            const filtered = usersData.filter(u => 
                u.name.toLowerCase().includes(query) || 
                u.email.toLowerCase().includes(query) ||
                (u.department && u.department.toLowerCase().includes(query))
            );
            renderUsersTable(filtered);
        }

        // User Management CRUD Handlers
        function openCreateUserModal() {
            document.getElementById('editingUserId').value = '';
            document.getElementById('createUserForm').reset();
            document.getElementById('userModalTitleText').innerText = 'Add New User';
            document.getElementById('userPassword').required = true;
            document.getElementById('userPasswordHelp').style.display = 'none';
            toggleUserRoleFields();
            openModal('createUserModal');
        }

        function editUser(id) {
            const u = usersData.find(item => item.id == id);
            if (!u) return;
            document.getElementById('editingUserId').value = u.id;
            document.getElementById('userName').value = u.name || '';
            document.getElementById('userEmail').value = u.email || '';
            document.getElementById('userRole').value = (u.role || 'student').toLowerCase();
            document.getElementById('userRollOrFaculty').value = u.roll_number || u.faculty_id || '';
            document.getElementById('userDept').value = u.department || '';
            document.getElementById('userPhone').value = u.phone || '';
            document.getElementById('userBio').value = u.bio || '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('userPasswordHelp').style.display = 'block';
            document.getElementById('userModalTitleText').innerText = 'Edit User Details';
            toggleUserRoleFields();
            openModal('createUserModal');
        }

        function toggleUserRoleFields() {
            const role = document.getElementById('userRole').value;
            const label = document.getElementById('userRollLabel');
            label.innerText = (role === 'faculty') ? 'Faculty ID' : 'Roll Number';
        }

        async function handleSaveUser(e) {
            e.preventDefault();
            const userId = document.getElementById('editingUserId').value;
            const role = document.getElementById('userRole').value;
            const rollVal = document.getElementById('userRollOrFaculty').value;

            const payload = {
                name: document.getElementById('userName').value,
                email: document.getElementById('userEmail').value,
                role: role,
                department: document.getElementById('userDept').value,
                phone: document.getElementById('userPhone').value,
                bio: document.getElementById('userBio').value,
                roll_number: role === 'student' ? rollVal : null,
                faculty_id: role === 'faculty' ? rollVal : null,
            };

            const pwd = document.getElementById('userPassword').value;
            if (pwd) payload.password = pwd;

            const url = userId ? `/api/admin/users/${userId}` : '/api/admin/users';
            const method = userId ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    showToast(userId ? 'User updated successfully!' : 'User created successfully!');
                    closeModal('createUserModal');
                    loadUsers();
                    loadDashboardStats();
                } else {
                    showToast(json.message || 'Failed to save user');
                }
            } catch (err) {
                showToast('Error saving user');
            }
        }

        async function deleteUser(id) {
            if (!confirm('Are you sure you want to delete this user? All their quiz attempt records will also be removed.')) return;
            try {
                const res = await fetch(`/api/admin/users/${id}`, { method: 'DELETE' });
                const json = await res.json();
                if (res.ok && json.success) {
                    showToast('User account deleted');
                    loadUsers();
                    loadDashboardStats();
                } else {
                    showToast(json.message || 'Failed to delete user');
                }
            } catch (e) {
                showToast('Error deleting user');
            }
        }

        async function loadMediaFiles() {
            try {
                const res = await fetch('/api/media');
                const json = await res.json();
                const files = json.data || [];
                const grid = document.getElementById('mediaGrid');

                if (files.length === 0) {
                    grid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted);">No uploaded media files found.</div>`;
                    return;
                }

                grid.innerHTML = files.map(f => `
                    <div class="quiz-card">
                        <div style="height: 140px; background: #F1F5F9; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; overflow: hidden;">
                            ${isImage(f.file_name) 
                                ? `<img src="${f.url}" style="width: 100%; height: 100%; object-fit: cover;">`
                                : `<i class="fa-solid fa-file" style="font-size: 36px; color: var(--text-muted);"></i>`
                            }
                        </div>
                        <div class="quiz-title" style="font-size: 14px; word-break: break-all;">${f.file_name}</div>
                        <div class="quiz-sub" style="font-size: 12px; margin-bottom: 12px;">${(f.size_bytes / 1024).toFixed(1)} KB • ${f.last_modified}</div>
                        <button class="btn btn-secondary" style="font-size: 12px; padding: 8px 12px;" onclick="copyToClipboard('${f.url}')">
                            <i class="fa-solid fa-copy"></i> Copy Direct URL
                        </button>
                    </div>
                `).join('');
            } catch (e) {
                showToast('Failed to load media files');
            }
        }

        function isImage(filename) {
            return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(filename);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('Media URL copied to clipboard!');
        }

        async function handleUploadMedia(e) {
            e.preventDefault();
            const fileInput = document.getElementById('mediaFileInput');
            const folder = document.getElementById('mediaFolderSelect').value;

            if (!fileInput.files || fileInput.files.length === 0) return;

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('folder', folder);

            try {
                const res = await fetch('/api/upload', {
                    method: 'POST',
                    body: formData,
                });
                const json = await res.json();
                if (json.success) {
                    showToast('File uploaded successfully!');
                    fileInput.value = '';
                    loadMediaFiles();
                } else {
                    showToast(json.message || 'Upload failed');
                }
            } catch (e) {
                showToast('File upload error');
            }
        }

        // Quiz Modals & CRUD
        function openCreateQuizModal() {
            document.getElementById('editingQuizId').value = '';
            document.getElementById('createQuizForm').reset();
            document.getElementById('quizModalTitleText').innerText = 'Create New Quiz';
            toggleScheduledDateInput();
            openModal('createQuizModal');
        }

        function toggleScheduledDateInput() {
            const status = document.getElementById('quizStatus').value;
            document.getElementById('scheduledGroup').style.display = (status === 'upcoming') ? 'block' : 'none';
        }

        async function handleSaveQuiz(e) {
            e.preventDefault();
            const quizId = document.getElementById('editingQuizId').value;
            const payload = {
                title: document.getElementById('quizTitle').value,
                subject: document.getElementById('quizSubject').value,
                instructor: document.getElementById('quizInstructor').value,
                duration: parseInt(document.getElementById('quizDuration').value),
                status: document.getElementById('quizStatus').value,
                scheduled_at: document.getElementById('quizScheduledAt').value || null,
                description: document.getElementById('quizDescription').value,
            };

            const url = quizId ? `/api/quizzes/${quizId}` : '/api/quizzes';
            const method = quizId ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (res.ok || json.id || json.success) {
                    showToast(quizId ? 'Quiz updated successfully!' : 'Quiz created successfully!');
                    closeModal('createQuizModal');
                    loadQuizzes();
                } else {
                    showToast('Failed to save quiz');
                }
            } catch (err) {
                showToast('Error saving quiz');
            }
        }

        function editQuiz(id) {
            const q = quizzesData.find(item => item.id === id);
            if (!q) return;
            document.getElementById('editingQuizId').value = q.id;
            document.getElementById('quizTitle').value = q.title || '';
            document.getElementById('quizSubject').value = q.subject || '';
            document.getElementById('quizInstructor').value = q.instructor || '';
            document.getElementById('quizDuration').value = q.duration || 15;
            document.getElementById('quizStatus').value = q.status || 'active';
            document.getElementById('quizDescription').value = q.description || '';
            document.getElementById('quizModalTitleText').innerText = 'Edit Quiz Details';
            toggleScheduledDateInput();
            openModal('createQuizModal');
        }

        async function deleteQuiz(id) {
            if (!confirm('Are you sure you want to delete this quiz?')) return;
            try {
                const res = await fetch(`/api/quizzes/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    showToast('Quiz deleted');
                    loadQuizzes();
                }
            } catch (e) {
                showToast('Failed to delete quiz');
            }
        }

        // Questions Management
        async function openManageQuestionsModal(quizId, title, subject) {
            document.getElementById('activeQuizId').value = quizId;
            document.getElementById('manageModalQuizTitle').innerText = title;
            document.getElementById('manageModalQuizSub').innerText = subject;
            resetQuestionForm();
            openModal('manageQuestionsModal');
            loadQuestionsList(quizId);
        }

        async function loadQuestionsList(quizId) {
            const container = document.getElementById('questionsList');
            container.innerHTML = '<div style="text-align: center; color: var(--text-muted);">Loading questions...</div>';
            try {
                const res = await fetch(`/api/quizzes/${quizId}`);
                const data = await res.json();
                const questions = data.questions || [];

                if (questions.length === 0) {
                    container.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 20px;">No questions added yet. Use the form below or import CSV.</div>';
                    return;
                }

                container.innerHTML = questions.map((q, idx) => `
                    <div style="background: var(--bg); border-radius: 12px; padding: 16px; margin-bottom: 12px; border: 1px solid var(--border);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <span style="font-weight: 800; font-size: 14px;">Q${idx + 1}. ${q.question_text}</span>
                            <div style="display: flex; gap: 6px;">
                                <button class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;" onclick="deleteQuestion(${q.id})"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 6px;">Type: <strong>${q.type === 'multiple' ? 'Multiple Answers' : 'Single Answer'}</strong></div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 12.5px;">
                            <div>1. ${q.option_1}</div>
                            <div>2. ${q.option_2}</div>
                            <div>3. ${q.option_3}</div>
                            <div>4. ${q.option_4}</div>
                        </div>
                        <div style="margin-top: 8px; font-weight: 700; color: var(--secondary); font-size: 12px;">Correct Answer: ${q.correct_option}</div>
                    </div>
                `).join('');
            } catch (e) {
                container.innerHTML = '<div style="color: var(--danger);">Failed to load questions.</div>';
            }
        }

        async function handleAddOrUpdateQuestion(e) {
            e.preventDefault();
            const quizId = document.getElementById('activeQuizId').value;
            const qType = document.getElementById('qType').value;
            
            let correctOption = document.getElementById('qCorrectSingle').value;
            if (qType === 'multiple') {
                const selected = Array.from(document.querySelectorAll('.multi-check:checked')).map(c => c.value);
                if (selected.length === 0) {
                    showToast('Please select at least one correct option');
                    return;
                }
                correctOption = selected.join('|');
            }

            const payload = {
                question_text: document.getElementById('qText').value,
                type: qType,
                option_1: document.getElementById('qOpt1').value,
                option_2: document.getElementById('qOpt2').value,
                option_3: document.getElementById('qOpt3').value,
                option_4: document.getElementById('qOpt4').value,
                correct_option: correctOption,
            };

            try {
                const res = await fetch(`/api/quizzes/${quizId}/questions`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    showToast('Question added!');
                    resetQuestionForm();
                    loadQuestionsList(quizId);
                    loadQuizzes();
                }
            } catch (e) {
                showToast('Error adding question');
            }
        }

        async function deleteQuestion(qId) {
            if (!confirm('Delete this question?')) return;
            const quizId = document.getElementById('activeQuizId').value;
            try {
                const res = await fetch(`/api/questions/${qId}`, { method: 'DELETE' });
                if (res.ok) {
                    showToast('Question deleted');
                    loadQuestionsList(quizId);
                    loadQuizzes();
                }
            } catch (e) {
                showToast('Failed to delete question');
            }
        }

        async function handleImportCsv(e) {
            e.preventDefault();
            const quizId = document.getElementById('activeQuizId').value;
            const csvInput = document.getElementById('csvFileInput');
            if (!csvInput.files || csvInput.files.length === 0) return;

            const formData = new FormData();
            formData.append('csv_file', csvInput.files[0]);

            try {
                const res = await fetch(`/api/quizzes/${quizId}/import-csv`, {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                if (json.success) {
                    showToast(`Imported ${json.imported_count} questions!`);
                    csvInput.value = '';
                    loadQuestionsList(quizId);
                    loadQuizzes();
                } else {
                    showToast(json.message || 'CSV import failed');
                }
            } catch (e) {
                showToast('CSV import error');
            }
        }

        function toggleQuestionTypeUI() {
            const type = document.getElementById('qType').value;
            document.getElementById('singleChoiceGroup').style.display = (type === 'single') ? 'block' : 'none';
            document.getElementById('multiChoiceGroup').style.display = (type === 'multiple') ? 'block' : 'none';
        }

        function resetQuestionForm() {
            document.getElementById('addQuestionForm').reset();
            document.getElementById('editingQuestionId').value = '';
            toggleQuestionTypeUI();
        }

        // Campaign Modals & Actions
        function openCreateCampaignModal() {
            document.getElementById('editingCampaignId').value = '';
            document.getElementById('createCampaignForm').reset();
            openModal('createCampaignModal');
        }

        async function handleSaveCampaign(e) {
            e.preventDefault();
            const payload = {
                title: document.getElementById('campaignTitle').value,
                badge: document.getElementById('campaignBadge').value,
                banner_color: document.getElementById('campaignColor').value,
                status: document.getElementById('campaignStatus').value,
                link_url: document.getElementById('campaignLink').value || null,
                description: document.getElementById('campaignDescription').value,
            };

            try {
                const res = await fetch('/api/campaigns', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    showToast('Campaign created!');
                    closeModal('createCampaignModal');
                    loadCampaigns();
                }
            } catch (e) {
                showToast('Error saving campaign');
            }
        }

        async function toggleCampaignStatus(id, newStatus) {
            try {
                const res = await fetch(`/api/campaigns/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                });
                if (res.ok) {
                    showToast(`Campaign status updated to ${newStatus}`);
                    loadCampaigns();
                }
            } catch (e) {
                showToast('Failed to update campaign');
            }
        }

        async function deleteCampaign(id) {
            if (!confirm('Delete this campaign notice?')) return;
            try {
                const res = await fetch(`/api/campaigns/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    showToast('Campaign deleted');
                    loadCampaigns();
                }
            } catch (e) {
                showToast('Failed to delete campaign');
            }
        }

        // Utilities
        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').innerText = msg;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3000);
        }

        function escapeJs(str) {
            return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
        }
    </script>
</body>
</html>
