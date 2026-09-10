<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova - Smart Quiz & Assessment Platform | Powered by Neody IT</title>
    <meta name="description" content="Acadova is the premier online assessment and interactive quiz platform for students and faculty. Experience real-time leaderboard rankings, subject mastery, and instant results.">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --secondary: #00B894;
            --accent: #FFA502;
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
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Header Navigation */
        .navbar {
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 12px;
            padding: 4px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 14px rgba(108, 92, 231, 0.15);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 22px;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .brand-text span {
            font-size: 10px;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            color: white;
            box-shadow: 0 8px 24px rgba(108, 92, 231, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(108, 92, 231, 0.45);
        }

        .btn-secondary {
            background: #EDF2F7;
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }

        /* Hero Section */
        .hero {
            padding: 160px 5% 100px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: #EEF2FF;
            color: var(--primary);
            border-radius: 30px;
            font-size: 12.5px;
            font-weight: 800;
            margin-bottom: 24px;
            border: 1px solid #C7D2FE;
        }

        .hero-content h1 {
            font-size: 52px;
            font-weight: 900;
            line-height: 1.15;
            color: var(--dark);
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero-content h1 span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            font-size: 18px;
            color: var(--text-muted);
            margin-bottom: 36px;
            font-weight: 500;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-preview-card {
            background: linear-gradient(135deg, #1E1B4B, #0F172A);
            border-radius: 28px;
            padding: 32px;
            color: white;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4);
            border: 1px solid #334155;
            position: relative;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .preview-app-icon {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 14px;
            padding: 6px;
        }

        .preview-app-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .preview-stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 24px;
        }

        .preview-stat h4 {
            font-size: 22px;
            font-weight: 900;
            color: #55E6C1;
        }

        .preview-stat p {
            font-size: 12px;
            color: #94A3B8;
            font-weight: 600;
        }

        /* Features Section */
        .section-title {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 60px;
        }

        .section-title h2 {
            font-size: 36px;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: -1px;
        }

        .section-title p {
            font-size: 16px;
            color: var(--text-muted);
            margin-top: 10px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
            max-width: 1300px;
            margin: 0 auto 100px;
            padding: 0 5%;
        }

        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 36px 28px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: var(--primary);
            box-shadow: 0 16px 36px rgba(108, 92, 231, 0.12);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 14.5px;
            color: var(--text-muted);
        }

        /* Active Quizzes showcase */
        .quizzes-section {
            background: #F1F5F9;
            padding: 100px 5%;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .quiz-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .quiz-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .quiz-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            background: #DEF7EC;
            color: #03543F;
            text-transform: uppercase;
        }

        .quiz-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
            margin: 12px 0 6px;
        }

        .quiz-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .quiz-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            border-top: 1px solid #F1F5F9;
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: #0F172A;
            color: white;
            padding: 80px 5% 40px;
        }

        .footer-content {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .footer-brand h2 {
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .footer-brand p {
            color: #94A3B8;
            max-width: 400px;
            font-size: 14px;
        }

        .footer-bottom {
            max-width: 1300px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #94A3B8;
            font-size: 13px;
        }

        @media (max-width: 992px) {
            .hero { grid-template-columns: 1fr; text-align: center; padding-top: 130px; }
            .hero-content h1 { font-size: 38px; }
            .hero-buttons { justify-content: center; }
            .footer-content { grid-template-columns: 1fr; gap: 30px; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <!-- Header Navigation Bar -->
    <nav class="navbar">
        <a href="/" class="brand-container">
            <div class="brand-logo">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo">
            </div>
            <div class="brand-text">
                <h1>Acadova</h1>
                <span>POWERED BY NEODY IT</span>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#quizzes">Live Assessments</a></li>
            <li><a href="#about">About Platform</a></li>
        </ul>

        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="/neodyit/login" class="btn btn-secondary" style="font-size: 13px; padding: 10px 18px;">
                <i class="fa-solid fa-lock"></i> Portal Access
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-sparkles"></i> Next-Gen Examination System
            </div>
            <h1>Empowering Students & Educators with <span>Smart Assessments</span></h1>
            <p>Acadova offers a seamless mobile and web assessment experience. Experience timed quizzes, instant performance analytics, and dynamic campaign notifications.</p>
            <div class="hero-buttons">
                <a href="#quizzes" class="btn btn-primary">
                    <i class="fa-solid fa-play"></i> Explore Active Quizzes
                </a>
                <a href="/neodyit/login" class="btn btn-secondary">
                    <i class="fa-solid fa-shield-halved"></i> Faculty Login
                </a>
            </div>
        </div>

        <div class="hero-preview-card">
            <div class="preview-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="preview-app-icon">
                        <img src="{{ asset('logo.png') }}" alt="Acadova Icon">
                    </div>
                    <div>
                        <h3 style="font-size: 18px; font-weight: 800;">Acadova Mobile App</h3>
                        <p style="font-size: 12px; color: #94A3B8;">Android & Web Sync Engine</p>
                    </div>
                </div>
                <span style="background: rgba(16, 185, 129, 0.2); color: #34D399; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                    LIVE CONNECTED
                </span>
            </div>

            <p style="font-size: 14px; color: #CBD5E1; line-height: 1.5;">
                Fully synchronized with Neody IT backend infrastructure for low-latency submission processing and Play Store compliance.
            </p>

            <div class="preview-stats-row">
                <div class="preview-stat">
                    <h4>{{ $stats['total_students'] }}</h4>
                    <p>Enrolled Students</p>
                </div>
                <div class="preview-stat">
                    <h4>{{ $stats['total_quizzes'] }}</h4>
                    <p>Quizzes Published</p>
                </div>
                <div class="preview-stat">
                    <h4>{{ $stats['total_attempts'] }}</h4>
                    <p>Submissions</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="features" style="padding: 60px 0;">
        <div class="section-title">
            <h2>Designed for Seamless Learning</h2>
            <p>Discover why students and institutions rely on Acadova for continuous evaluation and contest tracking.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: #EEF2FF; color: var(--primary);">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3>Real-Time Timed Quizzes</h3>
                <p>Configurable per-question durations with instant auto-submission and grade calculations upon timer completion.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #E6FFFA; color: var(--secondary);">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3>Detailed Score Breakdown</h3>
                <p>Students receive immediate feedback, question review accuracy, and performance indicators after submitting.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #FFFBEB; color: var(--accent);">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3>Interactive Campaigns</h3>
                <p>Stay informed with dynamic announcement banners, upcoming exam notifications, and campus competition notices.</p>
            </div>
        </div>
    </section>

    <!-- Active Quizzes Showcase -->
    <section id="quizzes" class="quizzes-section">
        <div class="section-title">
            <h2>Featured Active Quizzes</h2>
            <p>Check out currently available assessments ready for participation on the Acadova Mobile App.</p>
        </div>

        <div class="quiz-grid">
            @forelse($activeQuizzes as $q)
                <div class="quiz-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <span class="quiz-badge">Available Now</span>
                        <span style="font-size: 12px; font-weight: 800; color: var(--primary);">{{ $q->subject ?: 'General' }}</span>
                    </div>
                    <div class="quiz-title">{{ $q->title }}</div>
                    <div class="quiz-sub">{{ Str::limit($q->description ?: 'Interactive test assessment.', 90) }}</div>
                    <div class="quiz-footer">
                        <span><i class="fa-regular fa-clock"></i> {{ $q->duration_minutes }} mins</span>
                        <span><i class="fa-solid fa-list"></i> {{ $q->questions_count }} Questions</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">
                    No active public quizzes right now. Stay tuned for upcoming exam announcements!
                </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer id="about" class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 36px; height: 36px; background: white; border-radius: 10px; padding: 4px;">
                        <img src="{{ asset('logo.png') }}" alt="Acadova Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h2>Acadova</h2>
                </div>
                <p>Acadova is a high-performance quiz and assessment platform built for educational excellence and seamless mobile learning.</p>
            </div>

            <div>
                <h4 style="font-size: 16px; font-weight: 800; margin-bottom: 16px; color: white;">Quick Links</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 14px; color: #94A3B8;">
                    <li><a href="#features" style="color: inherit; text-decoration: none;">Platform Features</a></li>
                    <li><a href="#quizzes" style="color: inherit; text-decoration: none;">Live Quizzes</a></li>
                    <li><a href="/neodyit/login" style="color: inherit; text-decoration: none;">Admin Access</a></li>
                </ul>
            </div>

            <div>
                <h4 style="font-size: 16px; font-weight: 800; margin-bottom: 16px; color: white;">Engineering</h4>
                <p style="font-size: 13.5px; color: #94A3B8;">Designed, developed, and maintained by <strong>Neody IT</strong> software solutions.</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} Acadova. All rights reserved.</div>
            <div style="color: #55E6C1; font-weight: 800; font-size: 12px;">POWERED BY NEODY IT</div>
        </div>
    </footer>

</body>
</html>
