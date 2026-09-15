<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova — Next-Gen Academic Assessment & Quiz Platform</title>
    <meta name="description" content="Acadova is the premier academic evaluation and real-time quiz platform for students and faculty. Experience instant analytics, mobile app integration, and live contest rankings.">
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-gradient: linear-gradient(135deg, #6C5CE7 0%, #A29BFE 100%);
            --accent-glow: linear-gradient(135deg, #00B894 0%, #00CEC9 100%);
            --hero-bg: #0B0F19;
            --dark-card: rgba(22, 27, 46, 0.75);
            --dark-border: rgba(255, 255, 255, 0.1);
            --text-heading: #FFFFFF;
            --text-body: #94A3B8;
            --text-muted: #64748B;
            --success: #00B894;
            --warning: #FDCB6E;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--hero-bg);
            color: var(--text-body);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Ambient Background Glow Effects */
        .bg-glow-1 {
            position: absolute;
            top: -150px;
            left: 20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(108, 92, 231, 0.3) 0%, rgba(108, 92, 231, 0) 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        .bg-glow-2 {
            position: absolute;
            top: 400px;
            right: 5%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 184, 148, 0.2) 0%, rgba(0, 184, 148, 0) 70%);
            filter: blur(100px);
            z-index: 0;
            pointer-events: none;
        }

        /* Sticky Glass Header Navigation */
        .navbar {
            height: 80px;
            background: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--dark-border);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            transition: all 0.3s ease;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-logo-box {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(108, 92, 231, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title-wrap {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 900;
            color: var(--text-heading);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-tag {
            font-size: 9.5px;
            font-weight: 800;
            color: #00CEC9;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 36px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #CBD5E1;
            font-weight: 600;
            font-size: 14.5px;
            transition: all 0.2s ease;
        }

        .nav-links a:hover {
            color: #A29BFE;
            text-shadow: 0 0 12px rgba(162, 155, 254, 0.5);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Standard Modern Buttons */
        .btn {
            padding: 11px 22px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(108, 92, 231, 0.55);
            color: white;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-heading);
            border: 1px solid var(--dark-border);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: white;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 160px 6% 100px;
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(108, 92, 231, 0.15);
            border: 1px solid rgba(108, 92, 231, 0.3);
            border-radius: 30px;
            color: #A29BFE;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .hero-content h1 {
            font-size: 56px;
            font-weight: 900;
            line-height: 1.15;
            color: var(--text-heading);
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero-content h1 span {
            background: linear-gradient(135deg, #A29BFE 0%, #00CEC9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            font-size: 18px;
            color: var(--text-body);
            margin-bottom: 36px;
            max-width: 580px;
            font-weight: 400;
        }

        .hero-cta-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        /* Live Platform Stats Bar */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding-top: 24px;
            border-top: 1px solid var(--dark-border);
        }

        .stat-item h3 {
            font-size: 28px;
            font-weight: 900;
            color: var(--text-heading);
            line-height: 1.2;
        }

        .stat-item h3 span {
            color: #00B894;
        }

        .stat-item p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 4px;
        }

        /* Hero Right Preview Card */
        .preview-card-wrap {
            position: relative;
        }

        .preview-card {
            background: var(--dark-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--dark-border);
            border-radius: 28px;
            padding: 36px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            position: relative;
        }

        .preview-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .app-identity {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .app-icon {
            width: 52px;
            height: 52px;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 6px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .app-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(0, 184, 148, 0.15);
            border: 1px solid rgba(0, 184, 148, 0.3);
            border-radius: 20px;
            color: #55E6C1;
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background: #00B894;
            border-radius: 50%;
            box-shadow: 0 0 10px #00B894;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .preview-feature-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 18px;
            padding: 20px;
            margin-top: 20px;
        }

        .feature-item-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .feature-item-row:last-child {
            margin-bottom: 0;
        }

        .feature-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(108, 92, 231, 0.2);
            color: #A29BFE;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        /* Features Section */
        .features-section {
            padding: 100px 6%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
        }

        .section-header span {
            color: #00CEC9;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 900;
            color: var(--text-heading);
            letter-spacing: -1px;
            margin: 10px 0 16px;
        }

        .section-header p {
            font-size: 16.5px;
            color: var(--text-body);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--dark-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--dark-border);
            border-radius: 24px;
            padding: 36px 30px;
            transition: all 0.35s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(108, 92, 231, 0.5);
            box-shadow: 0 20px 40px rgba(108, 92, 231, 0.2);
        }

        .feature-card-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }

        .feature-card h3 {
            font-size: 21px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 15px;
            color: var(--text-body);
            line-height: 1.6;
        }

        /* Active Quizzes Showcase */
        .quizzes-section {
            background: rgba(15, 23, 42, 0.6);
            border-top: 1px solid var(--dark-border);
            border-bottom: 1px solid var(--dark-border);
            padding: 100px 6%;
            position: relative;
            z-index: 1;
        }

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .quiz-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            padding: 26px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .quiz-card:hover {
            border-color: rgba(0, 184, 148, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 184, 148, 0.15);
        }

        .quiz-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .quiz-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            background: rgba(0, 184, 148, 0.15);
            color: #55E6C1;
            border: 1px solid rgba(0, 184, 148, 0.3);
            text-transform: uppercase;
        }

        .quiz-subject {
            font-size: 12px;
            font-weight: 700;
            color: #A29BFE;
        }

        .quiz-card-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .quiz-card-desc {
            font-size: 14px;
            color: var(--text-body);
            margin-bottom: 20px;
        }

        .quiz-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Footer Section */
        .footer {
            background: #070A12;
            border-top: 1px solid var(--dark-border);
            padding: 80px 6% 40px;
            position: relative;
            z-index: 1;
        }

        .footer-grid {
            max-width: 1400px;
            margin: 0 auto 60px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand p {
            color: var(--text-body);
            font-size: 14px;
            margin-top: 16px;
            max-width: 380px;
        }

        .footer-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-heading);
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            color: var(--text-body);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #A29BFE;
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 13.5px;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* Responsive Media Queries */
        @media (max-width: 992px) {
            .hero-section {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 130px;
            }
            .hero-content h1 { font-size: 42px; }
            .hero-content p { margin: 0 auto 32px; }
            .hero-cta-group { justify-content: center; }
            .stats-grid { justify-content: center; }
            .nav-links { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
        }

        @media (max-width: 600px) {
            .hero-content h1 { font-size: 32px; }
            .footer-grid { grid-template-columns: 1fr; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <!-- Navigation Header -->
    <nav class="navbar">
        <a href="/" class="brand-container">
            <div class="brand-logo-box">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
            </div>
            <div class="brand-title-wrap">
                <span class="brand-title">Acadova</span>
                <span class="brand-tag">POWERED BY NEODY IT</span>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#quizzes">Live Assessments</a></li>
            <li><a href="#about">About Platform</a></li>
            <li><a href="/help-center">Help Center</a></li>
        </ul>

        <div class="nav-actions">
            <a href="/student/login" class="btn btn-primary" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-graduation-cap"></i> Student Portal
            </a>
            <a href="/neodyit/login" class="btn btn-outline" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-lock"></i> Admin Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-bolt-lightning"></i> Real-Time Academic Assessment Engine
            </div>
            <h1>Empowering Learning with <span>Smart Assessments</span></h1>
            <p>Acadova delivers high-performance online exams, real-time analytics, instant grade reports, and seamless mobile synchronization for educational institutions.</p>
            
            <div class="hero-cta-group">
                <a href="/student/login" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Student Web Portal
                </a>
                <a href="/neodyit/login" class="btn btn-outline">
                    <i class="fa-solid fa-shield-halved"></i> Faculty & Admin Panel
                </a>
            </div>

            <!-- Live Stats -->
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>{{ number_format($stats['total_students']) }}<span>+</span></h3>
                    <p>Enrolled Students</p>
                </div>
                <div class="stat-item">
                    <h3>{{ number_format($stats['total_quizzes']) }}<span>+</span></h3>
                    <p>Published Quizzes</p>
                </div>
                <div class="stat-item">
                    <h3>{{ number_format($stats['total_attempts']) }}<span>+</span></h3>
                    <p>Quiz Submissions</p>
                </div>
            </div>
        </div>

        <!-- Preview Interactive Graphic -->
        <div class="preview-card-wrap">
            <div class="preview-card">
                <div class="preview-card-header">
                    <div class="app-identity">
                        <div class="app-icon">
                            <img src="{{ asset('logo.png') }}" alt="Acadova Icon">
                        </div>
                        <div>
                            <h3 style="font-size: 19px; font-weight: 800; color: #FFF;">Acadova Mobile App</h3>
                            <p style="font-size: 12.5px; color: #94A3B8;">Flutter & Laravel API Sync Engine</p>
                        </div>
                    </div>
                    <div class="status-pill">
                        <div class="pulse-dot"></div> Connected
                    </div>
                </div>

                <p style="font-size: 14.5px; color: #CBD5E1; line-height: 1.6;">
                    Fully synchronized with Neody IT cloud backend infrastructure for instant timed quiz validation and security compliance.
                </p>

                <div class="preview-feature-box">
                    <div class="feature-item-row">
                        <div class="feature-icon-circle">
                            <i class="fa-solid fa-stopwatch"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 700; color: #FFF;">Timed Quiz Engine</h4>
                            <p style="font-size: 12px; color: #94A3B8;">Auto-submit upon timer expiration</p>
                        </div>
                    </div>

                    <div class="feature-item-row">
                        <div class="feature-icon-circle" style="background: rgba(0, 184, 148, 0.2); color: #00B894;">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 700; color: #FFF;">Instant Score & Analytics</h4>
                            <p style="font-size: 12px; color: #94A3B8;">Instant performance feedback</p>
                        </div>
                    </div>

                    <div class="feature-item-row">
                        <div class="feature-icon-circle" style="background: rgba(255, 118, 117, 0.2); color: #FF7675;">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 700; color: #FFF;">Campaign Alerts</h4>
                            <p style="font-size: 12px; color: #94A3B8;">Campus contests and announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="features" class="features-section">
        <div class="section-header">
            <span>Core Capabilities</span>
            <h2>Built for Academic Excellence</h2>
            <p>Designed with intuitive interfaces and enterprise-grade reliability to serve modern classrooms and online learning environments.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-card-icon" style="background: rgba(108, 92, 231, 0.15); color: #A29BFE;">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3>Multiple Question Formats</h3>
                <p>Supports Multiple Choice (MCQ), True/False, Fill in the blanks, and Short answer evaluation modes.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon" style="background: rgba(0, 184, 148, 0.15); color: #00B894;">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3>Leaderboard & Rankings</h3>
                <p>Motivate learning through real-time score leaderboards, accuracy streaks, and subject mastery tracking.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon" style="background: rgba(253, 203, 110, 0.15); color: #FDCB6E;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3>Announcements & Campaigns</h3>
                <p>Broadcast critical updates, department notices, and special competition events directly to student apps.</p>
            </div>
        </div>
    </section>

    <!-- Featured Active Quizzes Showcase -->
    <section id="quizzes" class="quizzes-section">
        <div class="section-header">
            <span>Available Right Now</span>
            <h2>Live Active Quizzes</h2>
            <p>Check out active quizzes ready for attempt on the Acadova Mobile & Web Portal.</p>
        </div>

        <div class="quiz-grid">
            @forelse($activeQuizzes as $q)
                <div class="quiz-card">
                    <div>
                        <div class="quiz-card-top">
                            <span class="quiz-pill"><i class="fa-solid fa-circle"></i> Active</span>
                            <span class="quiz-subject">{{ $q->subject ?: 'General Knowledge' }}</span>
                        </div>
                        <h3 class="quiz-card-title">{{ $q->title }}</h3>
                        <p class="quiz-card-desc">{{ Str::limit($q->description ?: 'Test your knowledge on this subject.', 95) }}</p>
                    </div>
                    <div class="quiz-card-meta">
                        <span><i class="fa-regular fa-clock"></i> {{ $q->duration_minutes }} Mins</span>
                        <span><i class="fa-solid fa-clipboard-question"></i> {{ $q->questions_count }} Questions</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: var(--text-body); padding: 40px; background: var(--dark-card); border-radius: 20px; border: 1px solid var(--dark-border);">
                    <i class="fa-solid fa-calendar-check" style="font-size: 32px; color: #A29BFE; margin-bottom: 12px;"></i>
                    <p>No active public quizzes at this moment. Please log in to check assigned class assessments!</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer id="about" class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand-container" style="margin-bottom: 16px;">
                    <div class="brand-logo-box" style="width: 38px; height: 38px;">
                        <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
                    </div>
                    <span class="brand-title" style="font-size: 20px;">Acadova</span>
                </div>
                <p>Acadova is a high-performance assessment & interactive quiz platform engineered for modern schools, colleges, and university departments.</p>
            </div>

            <div>
                <h4 class="footer-title">Platform Access</h4>
                <ul class="footer-links">
                    <li><a href="/student/login"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Student Web Portal</a></li>
                    <li><a href="/neodyit/login"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Faculty & Admin Access</a></li>
                    <li><a href="#quizzes"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Live Quiz Directory</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">Support & Legal</h4>
                <ul class="footer-links">
                    <li><a href="/help-center"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Help Center</a></li>
                    <li><a href="/privacy-policy"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Privacy Policy</a></li>
                    <li><a href="/terms-of-service"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Terms of Service</a></li>
                    <li><a href="/delete-account"><i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i> Account Deletion</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-title">Engineering</h4>
                <p style="font-size: 13.5px; color: var(--text-body); line-height: 1.6;">
                    Designed, developed, and maintained by <strong style="color: #FFF;">Neody IT</strong> software solutions.
                </p>
                <div style="margin-top: 14px; font-size: 13px; color: #00B894; font-weight: 700;">
                    <i class="fa-solid fa-envelope"></i> support@neodyit.in
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} Acadova Platform. All rights reserved.</div>
            <div style="color: #00CEC9; font-weight: 800; font-size: 12px; letter-spacing: 1px;">
                POWERED BY NEODY IT
            </div>
        </div>
    </footer>

</body>
</html>
