<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova — Smart Quiz & Assessment Platform | Powered by Neody IT</title>
    <meta name="description" content="Acadova is the premier academic quiz and online examination platform. Download the Android App from Google Play Store, get the Windows Desktop (.exe) app, or access the Web Portal.">
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            /* AppTheme Palette */
            --background: #F5ECDD;
            --main-text: #111111;
            --primary: #B45309;
            --primary-light: #D97706;
            --primary-dark: #78350F;
            --card-bg: #FFFFFF;
            --surface-light: #EFE6D5;
            --text-muted: #666666;
            --border: #E5D5C0;

            --success: #15803D;
            --warning: #D97706;
            --info: #0369A1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--background);
            color: var(--main-text);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Ambient Header Glow */
        .header-bg-glow {
            position: absolute;
            top: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 500px;
            background: radial-gradient(circle, rgba(180, 83, 9, 0.15) 0%, rgba(245, 236, 221, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Sticky Navigation Bar */
        .navbar {
            height: 80px;
            background: rgba(245, 236, 221, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
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
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo-box {
            width: 44px;
            height: 44px;
            background: #FFFFFF;
            border-radius: 14px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(180, 83, 9, 0.15);
            border: 1px solid var(--border);
        }

        .brand-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 900;
            color: var(--main-text);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 9.5px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--main-text);
            font-weight: 700;
            font-size: 14.5px;
            transition: all 0.25s ease;
        }

        .nav-menu a:hover {
            color: var(--primary);
        }

        .nav-cta-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Mobile Hamburger Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--main-text);
            font-size: 22px;
            cursor: pointer;
            padding: 6px;
        }

        /* Mobile Drawer */
        .mobile-drawer {
            display: none;
            position: fixed;
            top: 80px; left: 0; right: 0;
            background: #F5ECDD;
            border-bottom: 2px solid var(--border);
            padding: 24px;
            z-index: 999;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }

        .mobile-drawer.active {
            display: flex;
        }

        .mobile-drawer a {
            color: var(--main-text);
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }

        /* Buttons Styling (AppTheme Matching) */
        .btn {
            padding: 12px 24px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 8px 22px rgba(180, 83, 9, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(180, 83, 9, 0.45);
            color: white;
        }

        .btn-download-play {
            background: #111111;
            color: white;
            box-shadow: 0 6px 20px rgba(17, 17, 17, 0.2);
        }

        .btn-download-play:hover {
            background: #2A2A2A;
            transform: translateY(-2px);
            color: white;
        }

        .btn-download-win {
            background: linear-gradient(135deg, #0078D4 0%, #00A4EF 100%);
            color: white;
            box-shadow: 0 8px 22px rgba(0, 120, 212, 0.3);
        }

        .btn-download-win:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 120, 212, 0.45);
            color: white;
        }

        .btn-outline {
            background: white;
            color: var(--main-text);
            border: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .btn-outline:hover {
            background: var(--surface-light);
            border-color: var(--primary);
            transform: translateY(-2px);
            color: var(--primary);
        }

        .btn-app-store-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }

        .btn-app-store-content .subtext {
            font-size: 10px;
            font-weight: 600;
            opacity: 0.85;
            text-transform: uppercase;
        }

        .btn-app-store-content .maintext {
            font-size: 14px;
            font-weight: 800;
        }

        /* Hero Section */
        .hero-container {
            position: relative;
            padding: 160px 6% 90px;
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 60px;
            align-items: center;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(180, 83, 9, 0.12);
            border: 1px solid rgba(180, 83, 9, 0.3);
            border-radius: 30px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: 54px;
            font-weight: 900;
            line-height: 1.15;
            color: var(--main-text);
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero-title span {
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--text-muted);
            margin-bottom: 36px;
            max-width: 600px;
            font-weight: 500;
        }

        .download-buttons-wrap {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding-top: 28px;
            border-top: 1.5px dashed var(--border);
        }

        .stat-card h3 {
            font-size: 32px;
            font-weight: 900;
            color: var(--primary);
            line-height: 1.1;
        }

        .stat-card p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 700;
            margin-top: 4px;
        }

        /* Hero Right Preview Card */
        .preview-widget {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 20px 50px rgba(180, 83, 9, 0.12);
            position: relative;
        }

        .preview-widget-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .app-badge-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .app-badge-icon {
            width: 48px;
            height: 48px;
            background: var(--background);
            border-radius: 14px;
            padding: 5px;
            border: 1px solid var(--border);
        }

        .app-badge-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .live-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(21, 128, 61, 0.12);
            border: 1px solid rgba(21, 128, 61, 0.3);
            border-radius: 20px;
            color: var(--success);
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .pulse-light {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse-ring 1.8s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.8; }
        }

        .preview-feature-box {
            background: var(--surface-light);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
        }

        .feature-mini-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }

        .feature-mini-row:last-child {
            margin-bottom: 0;
        }

        .mini-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #FFFFFF;
            color: var(--primary);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* Platform Downloads Banner */
        .downloads-banner-section {
            background: #EFE6D5;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 80px 6%;
            position: relative;
            z-index: 1;
        }

        .section-title-wrap {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 60px;
        }

        .section-tag {
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 42px;
            font-weight: 900;
            color: var(--main-text);
            letter-spacing: -1px;
            margin: 10px 0 16px;
        }

        .section-desc {
            font-size: 16.5px;
            color: var(--text-muted);
        }

        .downloads-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .download-platform-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 34px 28px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .download-platform-card:hover {
            transform: translateY(-6px);
            border-color: var(--primary);
            box-shadow: 0 16px 36px rgba(180, 83, 9, 0.15);
        }

        .platform-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            background: var(--background);
            border: 1px solid var(--border);
            color: var(--primary);
        }

        .download-platform-card h3 {
            font-size: 20px;
            font-weight: 800;
            color: var(--main-text);
            margin-bottom: 8px;
        }

        .download-platform-card p {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        /* Features Section */
        .features-section {
            padding: 100px 6%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .features-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 28px;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 34px 28px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            transition: all 0.35s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 16px 36px rgba(180, 83, 9, 0.12);
        }

        .feature-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: var(--background);
            border: 1px solid var(--border);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 22px;
        }

        .feature-card h3 {
            font-size: 21px;
            font-weight: 800;
            color: var(--main-text);
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 14.5px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* 3-Step Walkthrough Section */
        .workflow-section {
            background: #EFE6D5;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 100px 6%;
            position: relative;
            z-index: 1;
        }

        .workflow-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }

        .workflow-step-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 36px 28px;
        }

        .step-number {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--primary);
            color: white;
            font-size: 18px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 6px 18px rgba(180, 83, 9, 0.3);
        }

        .workflow-step-card h3 {
            font-size: 20px;
            font-weight: 800;
            color: var(--main-text);
            margin-bottom: 12px;
        }

        .workflow-step-card p {
            font-size: 14.5px;
            color: var(--text-muted);
        }

        /* Active Quizzes Section */
        .quizzes-section {
            padding: 100px 6%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .quiz-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 26px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .quiz-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(180, 83, 9, 0.12);
        }

        .quiz-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .quiz-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            background: rgba(21, 128, 61, 0.12);
            color: var(--success);
            border: 1px solid rgba(21, 128, 61, 0.3);
            text-transform: uppercase;
        }

        .quiz-subject {
            font-size: 12px;
            font-weight: 800;
            color: var(--primary);
        }

        .quiz-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--main-text);
            margin-bottom: 8px;
        }

        .quiz-desc {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .quiz-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* FAQ Accordion Section */
        .faq-section {
            padding: 90px 6%;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .faq-item {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px;
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-question {
            padding: 22px 26px;
            font-size: 17px;
            font-weight: 800;
            color: var(--main-text);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .faq-answer {
            padding: 0 26px 22px;
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
            color: var(--primary);
        }

        /* Footer Section */
        .footer {
            background: #EFE6D5;
            border-top: 1px solid var(--border);
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
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 16px;
            max-width: 380px;
        }

        .footer-heading {
            font-size: 16px;
            font-weight: 800;
            color: var(--main-text);
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 13.5px;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* Mobile Responsiveness Media Queries */
        @media (max-width: 992px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 130px;
            }
            .hero-title { font-size: 42px; }
            .hero-subtitle { margin: 0 auto 32px; }
            .download-buttons-wrap { justify-content: center; }
            .stats-row { justify-content: center; }
            .nav-menu, .nav-cta-group { display: none; }
            .mobile-toggle { display: block; }
            .downloads-grid, .workflow-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
        }

        @media (max-width: 600px) {
            .hero-title { font-size: 32px; }
            .section-title { font-size: 30px; }
            .btn { width: 100%; justify-content: center; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="header-bg-glow"></div>

    <!-- Navigation Header -->
    <nav class="navbar">
        <a href="/" class="brand-container">
            <div class="brand-logo-box">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
            </div>
            <div class="brand-text">
                <span class="brand-name">Acadova</span>
                <span class="brand-subtitle">POWERED BY NEODY IT</span>
            </div>
        </a>

        <!-- Desktop Menu Links -->
        <ul class="nav-menu">
            <li><a href="#downloads">Apps & Downloads</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#workflow">How It Works</a></li>
            <li><a href="#quizzes">Live Assessments</a></li>
            <li><a href="/help-center">Help Center</a></li>
        </ul>

        <!-- Desktop Action CTAs -->
        <div class="nav-cta-group">
            <a href="/student/login" class="btn btn-primary" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-graduation-cap"></i> Student Portal
            </a>
            <a href="/neodyit/login" class="btn btn-outline" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-user-shield"></i> Faculty Access
            </a>
        </div>

        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Navigation Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-drawer" id="mobileDrawer">
        <a href="#downloads"><i class="fa-solid fa-download"></i> Downloads</a>
        <a href="#features"><i class="fa-solid fa-star"></i> Features</a>
        <a href="#workflow"><i class="fa-solid fa-diagram-project"></i> How It Works</a>
        <a href="#quizzes"><i class="fa-solid fa-list-check"></i> Live Assessments</a>
        <a href="/help-center"><i class="fa-solid fa-circle-question"></i> Help Center</a>
        <a href="/student/login" style="color: var(--primary);"><i class="fa-solid fa-graduation-cap"></i> Student Web Portal</a>
        <a href="/neodyit/login" style="color: var(--main-text);"><i class="fa-solid fa-user-shield"></i> Admin & Faculty Login</a>
    </div>

    <!-- Hero Section -->
    <section class="hero-container">
        <div class="hero-left">
            <div class="hero-badge">
                <i class="fa-solid fa-graduation-cap"></i> Academic Quiz & Examination Suite
            </div>
            <h1 class="hero-title">Empowering Learning with <span>Smart Assessments</span></h1>
            <p class="hero-subtitle">Acadova delivers timed quizzes, instant performance reports, automated grading, and live scoreboards across Android Mobile, Windows PC, and Web Browsers.</p>
            
            <!-- Download & Portal CTA Group -->
            <div class="download-buttons-wrap">
                <!-- Play Store Link -->
                <a href="https://play.google.com/store/apps/details?id=com.neodyit.acadova" target="_blank" class="btn btn-download-play">
                    <i class="fa-brands fa-google-play" style="font-size: 22px; color: #00F0FF;"></i>
                    <div class="btn-app-store-content">
                        <span class="subtext">GET IT ON</span>
                        <span class="maintext">Google Play</span>
                    </div>
                </a>

                <!-- Windows Executable Setup Link -->
                <a href="/downloads/Acadova-Setup.exe" class="btn btn-download-win">
                    <i class="fa-brands fa-windows" style="font-size: 22px;"></i>
                    <div class="btn-app-store-content">
                        <span class="subtext">DOWNLOAD FOR</span>
                        <span class="maintext">Windows PC (.exe)</span>
                    </div>
                </a>

                <!-- Student Portal Button -->
                <a href="/student/login" class="btn btn-primary">
                    <i class="fa-solid fa-globe"></i> Web Portal
                </a>
            </div>

            <!-- Stats Bar -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3>{{ number_format($stats['total_students']) }}<span>+</span></h3>
                    <p>Enrolled Students</p>
                </div>
                <div class="stat-card">
                    <h3>{{ number_format($stats['total_quizzes']) }}<span>+</span></h3>
                    <p>Quizzes Published</p>
                </div>
                <div class="stat-card">
                    <h3>{{ number_format($stats['total_attempts']) }}<span>+</span></h3>
                    <p>Submissions</p>
                </div>
            </div>
        </div>

        <!-- Hero Right Preview Widget -->
        <div class="hero-right">
            <div class="preview-widget">
                <div class="preview-widget-header">
                    <div class="app-badge-info">
                        <div class="app-badge-icon">
                            <img src="{{ asset('logo.png') }}" alt="Acadova Icon">
                        </div>
                        <div>
                            <h3 style="font-size: 19px; font-weight: 800; color: var(--main-text);">Acadova Ecosystem</h3>
                            <p style="font-size: 12.5px; color: var(--text-muted);">Android • Windows • Web</p>
                        </div>
                    </div>
                    <div class="live-status-pill">
                        <div class="pulse-light"></div> LIVE CONNECTED
                    </div>
                </div>

                <p style="font-size: 14.5px; color: var(--text-muted); line-height: 1.6;">
                    Powered by Neody IT backend infrastructure for real-time exam validation, instant submission processing, and zero latency.
                </p>

                <div class="preview-feature-box">
                    <div class="feature-mini-row">
                        <div class="mini-icon">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--main-text);">Timed Quiz Engine</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">Auto-submit on timer completion</p>
                        </div>
                    </div>

                    <div class="feature-mini-row">
                        <div class="mini-icon">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--main-text);">Instant Score Breakdown</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">Detailed answer feedback & accuracy</p>
                        </div>
                    </div>

                    <div class="feature-mini-row">
                        <div class="mini-icon">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 800; color: var(--main-text);">Campaign Notice Board</h4>
                            <p style="font-size: 12px; color: var(--text-muted);">Campus contests and announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Downloads Banner Section -->
    <section id="downloads" class="downloads-banner-section">
        <div class="section-title-wrap">
            <span class="section-tag">Multi-Platform Access</span>
            <h2 class="section-title">Available Anywhere You Learn</h2>
            <p class="section-desc">Download Acadova natively on your favorite device or access the platform directly in your web browser.</p>
        </div>

        <div class="downloads-grid">
            <!-- Google Play Store -->
            <div class="download-platform-card">
                <div class="platform-icon-wrap">
                    <i class="fa-brands fa-android"></i>
                </div>
                <h3>Android App</h3>
                <p>Native mobile experience optimized for phones & tablets. Take quizzes on the go.</p>
                <a href="https://play.google.com/store/apps/details?id=com.neodyit.acadova" target="_blank" class="btn btn-download-play" style="width: 100%; justify-content: center;">
                    <i class="fa-brands fa-google-play"></i> Get on Google Play
                </a>
            </div>

            <!-- Windows PC (.exe) -->
            <div class="download-platform-card">
                <div class="platform-icon-wrap" style="color: #00A4EF;">
                    <i class="fa-brands fa-windows"></i>
                </div>
                <h3>Windows PC (.exe)</h3>
                <p>Standalone desktop installer for computer labs, proctored exams, and large screens.</p>
                <a href="/downloads/Acadova-Setup.exe" class="btn btn-download-win" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-download"></i> Download Setup (.exe)
                </a>
            </div>

            <!-- Web Browser Portal -->
            <div class="download-platform-card">
                <div class="platform-icon-wrap">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <h3>Web Portal</h3>
                <p>Zero installation required. Access your account instantly from any modern web browser.</p>
                <a href="/student/login" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Launch Web App
                </a>
            </div>
        </div>
    </section>

    <!-- Key Platform Features Section -->
    <section id="features" class="features-section">
        <div class="section-title-wrap">
            <span class="section-tag">Core Features</span>
            <h2 class="section-title">Designed for Modern Academics</h2>
            <p class="section-desc">Experience intelligent features built to streamline exam management for teachers and study evaluation for students.</p>
        </div>

        <div class="features-cards-grid">
            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <h3>Timed Assessments</h3>
                <p>Configurable quiz timers, auto-submission mechanisms, and strict question duration limits.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3>Live Leaderboards</h3>
                <p>Real-time student rankings, subject accuracy scores, and performance milestones.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3>Campus Notice Alerts</h3>
                <p>Broadcast critical updates, competition announcements, and upcoming exam schedules.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3>Multi-Device Sync</h3>
                <p>Seamless state synchronization across Android App, Windows Desktop, and Web Browser.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <h3>Department Filtering</h3>
                <p>Categorized assessments tailored to specific academic departments, branches, and semesters.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card-icon">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <h3>Automated Grading</h3>
                <p>Instant scoring, detailed answer review keys, and downloadable result reports.</p>
            </div>
        </div>
    </section>

    <!-- 3-Step Walkthrough Section -->
    <section id="workflow" class="workflow-section">
        <div class="section-title-wrap">
            <span class="section-tag">Simple Process</span>
            <h2 class="section-title">How Acadova Works</h2>
            <p class="section-desc">Get started in under 2 minutes with our streamlined evaluation process.</p>
        </div>

        <div class="workflow-grid">
            <div class="workflow-step-card">
                <div class="step-number">1</div>
                <h3>Login & Select Course</h3>
                <p>Sign in using your student credentials on the Android App, Windows PC (.exe), or Web Portal. Choose your department and branch.</p>
            </div>

            <div class="workflow-step-card">
                <div class="step-number">2</div>
                <h3>Attempt Timed Quiz</h3>
                <p>Answer questions before the timer completes. Experience real-time progress indicators and smooth response saving.</p>
            </div>

            <div class="workflow-step-card">
                <div class="step-number">3</div>
                <h3>Instant Score & Feedback</h3>
                <p>Receive your automated grade report immediately upon submission, view correct answers, and climb the leaderboard.</p>
            </div>
        </div>
    </section>

    <!-- Featured Active Quizzes Section -->
    <section id="quizzes" class="quizzes-section">
        <div class="section-title-wrap">
            <span class="section-tag">Live Showcase</span>
            <h2 class="section-title">Featured Active Quizzes</h2>
            <p class="section-desc">Browse live public assessments currently active on Acadova.</p>
        </div>

        <div class="quiz-grid">
            @forelse($activeQuizzes as $q)
                <div class="quiz-card">
                    <div>
                        <div class="quiz-card-header">
                            <span class="quiz-badge"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Available</span>
                            <span class="quiz-subject">{{ $q->subject ?: 'General Knowledge' }}</span>
                        </div>
                        <h3 class="quiz-title">{{ $q->title }}</h3>
                        <p class="quiz-desc">{{ Str::limit($q->description ?: 'Interactive timed assessment.', 95) }}</p>
                    </div>
                    <div class="quiz-meta">
                        <span><i class="fa-regular fa-clock"></i> {{ $q->duration_minutes }} Mins</span>
                        <span><i class="fa-solid fa-list-check"></i> {{ $q->questions_count }} Questions</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 50px; background: var(--card-bg); border-radius: 24px; border: 1px solid var(--border);">
                    <i class="fa-solid fa-calendar-check" style="font-size: 36px; color: var(--primary); margin-bottom: 12px;"></i>
                    <p style="font-size: 16px; font-weight: 700;">No public quizzes currently active. Log in to access your course quizzes!</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- FAQ Accordion Section -->
    <section class="faq-section">
        <div class="section-title-wrap">
            <span class="section-tag">Got Questions?</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Where can I download the Acadova app?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                You can download the official Android App directly from the <strong>Google Play Store</strong>, download the standalone <strong>Windows PC (.exe)</strong> installer, or use the instant Web Portal right from any browser.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>How do students log into their account?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Students can log in using their registered email or enrollment ID provided by their faculty/department. If you forgot your password, use the reset link on the login page.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>Are quiz scores computed automatically?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Yes! Acadova features real-time automated score calculations. Immediately upon submitting a quiz, students receive their total mark, accuracy percentage, and complete question feedback.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <span>How can faculty create and manage quizzes?</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Faculty members can access the Admin Portal (`/neodyit/login`) to build question banks, publish timed quizzes, set department allocations, and view student progress analytics.
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand-container" style="margin-bottom: 16px;">
                    <div class="brand-logo-box" style="width: 38px; height: 38px;">
                        <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
                    </div>
                    <span class="brand-name" style="font-size: 20px;">Acadova</span>
                </div>
                <p>Acadova is a next-generation academic evaluation platform designed and engineered by Neody IT for colleges, schools, and educational institutes.</p>
            </div>

            <div>
                <h4 class="footer-heading">Downloads & Portal</h4>
                <ul class="footer-links">
                    <li><a href="https://play.google.com/store/apps/details?id=com.neodyit.acadova" target="_blank"><i class="fa-brands fa-google-play"></i> Android App (Google Play)</a></li>
                    <li><a href="/downloads/Acadova-Setup.exe"><i class="fa-brands fa-windows"></i> Windows PC (.exe)</a></li>
                    <li><a href="/student/login"><i class="fa-solid fa-globe"></i> Student Web Portal</a></li>
                    <li><a href="/neodyit/login"><i class="fa-solid fa-user-shield"></i> Admin & Faculty Portal</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Support & Legal</h4>
                <ul class="footer-links">
                    <li><a href="/help-center"><i class="fa-solid fa-circle-question"></i> Help Center</a></li>
                    <li><a href="/privacy-policy"><i class="fa-solid fa-shield-cat"></i> Privacy Policy</a></li>
                    <li><a href="/terms-of-service"><i class="fa-solid fa-file-contract"></i> Terms of Service</a></li>
                    <li><a href="/delete-account"><i class="fa-solid fa-user-minus"></i> Account Deletion</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Engineering</h4>
                <p style="font-size: 13.5px; color: var(--text-muted); line-height: 1.6;">
                    Designed, developed, and maintained by <strong style="color: var(--main-text);">Neody IT</strong> software solutions.
                </p>
                <div style="margin-top: 14px; font-size: 13.5px; color: var(--primary); font-weight: 800;">
                    <i class="fa-solid fa-envelope"></i> support@neodyit.in
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} Acadova Platform. All rights reserved.</div>
            <div style="color: var(--primary); font-weight: 800; font-size: 12px; letter-spacing: 1px;">
                POWERED BY NEODY IT
            </div>
        </div>
    </footer>

    <!-- Interactive JavaScript -->
    <script>
        // Mobile Navigation Drawer Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileDrawer = document.getElementById('mobileDrawer');

        mobileToggle.addEventListener('click', () => {
            mobileDrawer.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (mobileDrawer.classList.contains('active')) {
                icon.className = 'fa-solid fa-xmark';
            } else {
                icon.className = 'fa-solid fa-bars';
            }
        });

        // FAQ Accordion Toggle
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                parent.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
