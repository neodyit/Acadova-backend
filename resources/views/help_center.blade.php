<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center & FAQs - Acadova Quiz App | Neody IT</title>
    <meta name="description" content="Help Center, FAQs, troubleshooting guides, and support contact for Acadova Quiz App by Neody IT.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #F5ECDD;
            --surface-color: #FFFFFF;
            --surface-secondary: #FAF4EB;
            --primary: #B45309;
            --primary-light: #D97706;
            --primary-dark: #78350F;
            --text-main: #111111;
            --text-muted: #555555;
            --border-color: #E5D5C0;
            --card-shadow: 0 10px 30px -5px rgba(120, 53, 15, 0.08);
            --radius-lg: 20px;
            --radius-md: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.7;
            padding-bottom: 60px;
        }

        /* Navbar Header */
        header {
            background-color: var(--surface-color);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo-img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 12px;
            background: white;
            padding: 4px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Nav Links bar */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: var(--surface-secondary);
            color: var(--primary-dark);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 13px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .nav-link-btn:hover, .nav-link-btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Page Hero Header */
        .hero-section {
            max-width: 1200px;
            margin: 40px auto 30px auto;
            padding: 0 24px;
        }

        .hero-card {
            background: linear-gradient(135deg, #78350F 0%, #B45309 100%);
            color: white;
            padding: 48px 40px;
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(120, 53, 15, 0.2);
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .hero-meta {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Main Content */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .faq-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
        }

        .faq-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(120, 53, 15, 0.12);
        }

        .faq-icon {
            width: 48px;
            height: 48px;
            background: var(--surface-secondary);
            color: var(--primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .faq-question {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }

        .faq-answer {
            font-size: 15px;
            color: #444444;
            line-height: 1.6;
        }

        .support-card {
            background: linear-gradient(135deg, #FAF4EB 0%, #F5ECDD 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 40px;
            text-align: center;
            box-shadow: var(--card-shadow);
        }

        .support-title {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }

        .support-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 30px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 16px;
            margin-top: 20px;
            box-shadow: 0 8px 20px rgba(180, 83, 9, 0.25);
            transition: all 0.2s ease;
        }

        .support-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(180, 83, 9, 0.35);
        }

        /* Footer Navigation */
        footer {
            max-width: 1200px;
            margin: 50px auto 0 auto;
            padding: 0 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .footer-nav a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .footer-nav a:hover {
            text-decoration: underline;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .nav-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
                padding: 12px 16px;
            }

            .nav-links {
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 4px;
                -webkit-overflow-scrolling: touch;
            }

            .nav-link-btn {
                font-size: 12px;
                padding: 6px 14px;
            }

            .hero-section {
                margin: 20px auto 20px auto;
                padding: 0 16px;
            }

            .hero-card {
                padding: 24px 20px;
            }

            .hero-card > div {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px !important;
            }

            .hero-title {
                font-size: 24px !important;
            }

            .main-container {
                padding: 0 16px;
            }

            .faq-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .faq-card {
                padding: 20px;
            }

            .support-card {
                padding: 24px 18px;
            }

            .support-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <a href="/" class="brand-logo">
                <img src="/logo.png" alt="Acadova Logo" class="brand-logo-img" onerror="this.style.display='none';">
                <div>
                    <div class="brand-name">Acadova</div>
                    <div class="brand-subtitle">By Neody IT</div>
                </div>
            </a>
            <div class="nav-links">
                <a href="{{ route('privacy.policy') }}" class="nav-link-btn">Privacy Policy</a>
                <a href="{{ route('terms.service') }}" class="nav-link-btn">Terms of Service</a>
                <a href="{{ route('help.center') }}" class="nav-link-btn active">Help Center</a>
                <a href="{{ route('account.delete') }}" class="nav-link-btn">Account Deletion</a>
            </div>
        </div>
    </header>

    <!-- Hero Header -->
    <div class="hero-section">
        <div class="hero-card">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <img src="/logo.png" alt="Acadova Logo" style="width: 56px; height: 56px; object-fit: contain; background: white; padding: 6px; border-radius: 16px;">
                <div>
                    <span class="hero-badge">Support & Documentation</span>
                    <h1 class="hero-title" style="margin-bottom: 0;">Help Center & FAQs</h1>
                </div>
            </div>
            <div class="hero-meta">
                Frequently asked questions and guides for Students and Faculty members.
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        
        <div class="faq-grid">

            <div class="faq-card">
                <div class="faq-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3 class="faq-question">How do I sign in on Desktop/Mobile?</h3>
                <p class="faq-answer">Students and Faculty can sign in using their registered institution email and password. On desktop platforms, a browser window opens automatically to safely complete authentication.</p>
            </div>

            <div class="faq-card">
                <div class="faq-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3 class="faq-question">How are quiz scores calculated?</h3>
                <p class="faq-answer">Scores are automatically graded upon submission based on the question key provided by your instructor. Instant feedback and correct answer explanations are displayed if enabled by the faculty member.</p>
            </div>

            <div class="faq-card">
                <div class="faq-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h3 class="faq-question">Is my academic data secure?</h3>
                <p class="faq-answer">Yes, all user credentials and test submissions are encrypted using SSL/TLS and saved securely on dedicated servers. Read our Privacy Policy for full details.</p>
            </div>

            <div class="faq-card">
                <div class="faq-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </div>
                <h3 class="faq-question">How do I request account deletion?</h3>
                <p class="faq-answer">You can submit an online Account Deletion Request directly from the app or website footer link. Deletion requests are processed within 24 to 48 hours.</p>
            </div>

        </div>

        <div class="support-card">
            <h2 class="support-title">Still Have Questions?</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Our dedicated engineering and technical support team at Neody IT is here to assist you.</p>
            <a href="mailto:mayank@neodyit.in" class="support-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Contact Technical Support
            </a>
        </div>

    </div>

    <!-- Footer Navigation -->
    <footer>
        <div class="footer-nav">
            <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
            <span>&bull;</span>
            <a href="{{ route('terms.service') }}">Terms of Service</a>
            <span>&bull;</span>
            <a href="{{ route('help.center') }}">Help Center</a>
            <span>&bull;</span>
            <a href="{{ route('account.delete') }}">Account Deletion</a>
        </div>
        <p>&copy; 2026 Neody IT. All rights reserved. &bull; Acadova Quiz Platform</p>
    </footer>

</body>
</html>
