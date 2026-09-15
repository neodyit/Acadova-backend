<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Acadova Quiz App | Neody IT</title>
    <meta name="description" content="Terms of Service for Acadova Quiz App by Neody IT. Read guidelines for academic integrity, user accounts, and platform usage.">
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

        /* Layout Main Grid */
        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 32px;
        }

        /* Sidebar Table of Contents */
        .sidebar {
            position: sticky;
            top: 96px;
            height: fit-content;
        }

        .toc-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--card-shadow);
        }

        .toc-title {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toc-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toc-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            display: block;
        }

        .toc-link:hover, .toc-link.active {
            background-color: var(--surface-secondary);
            color: var(--primary);
            font-weight: 600;
        }

        /* Main Legal Content */
        .legal-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 48px;
            box-shadow: var(--card-shadow);
        }

        .policy-section {
            margin-bottom: 40px;
            scroll-margin-top: 110px;
        }

        .section-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid var(--surface-secondary);
            padding-bottom: 10px;
        }

        .section-number {
            width: 30px;
            height: 30px;
            background: var(--surface-secondary);
            color: var(--primary);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        p {
            margin-bottom: 16px;
            color: #333333;
            font-size: 15px;
        }

        ul {
            margin-bottom: 16px;
            padding-left: 20px;
            color: #333333;
        }

        li {
            margin-bottom: 8px;
            font-size: 15px;
        }

        .highlight-box {
            background-color: var(--surface-secondary);
            border-left: 4px solid var(--primary);
            padding: 16px 20px;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            margin: 20px 0;
            font-size: 14px;
            color: var(--text-main);
        }

        /* Footer nav bar inside footer */
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

        @media (max-width: 900px) {
            .nav-container {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
                padding: 14px 16px;
            }
            .brand-logo {
                justify-content: center;
            }
            .nav-links {
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 6px;
                -webkit-overflow-scrolling: touch;
                justify-content: flex-start;
            }
            .hero-section {
                margin: 20px auto 20px auto;
                padding: 0 16px;
            }
            .hero-card {
                padding: 28px 20px;
            }
            .hero-title {
                font-size: 26px;
            }
            .content-container {
                grid-template-columns: 1fr;
                padding: 0 16px;
            }
            .sidebar {
                display: none;
            }
            .legal-card {
                padding: 24px 18px;
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
                <a href="{{ route('terms.service') }}" class="nav-link-btn active">Terms of Service</a>
                <a href="{{ route('help.center') }}" class="nav-link-btn">Help Center</a>
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
                    <span class="hero-badge">User Agreement</span>
                    <h1 class="hero-title" style="margin-bottom: 0;">Terms of Service</h1>
                </div>
            </div>
            <div class="hero-meta">
                Effective Date: <strong>September 15, 2026</strong> &bull; Last Updated: <strong>September 15, 2026</strong>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-container">

        <!-- Table of Contents Sidebar -->
        <aside class="sidebar">
            <div class="toc-card">
                <div class="toc-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line></svg>
                    Navigation
                </div>
                <ul class="toc-list">
                    <li><a href="#acceptance" class="toc-link">1. Acceptance of Terms</a></li>
                    <li><a href="#accounts" class="toc-link">2. User Accounts</a></li>
                    <li><a href="#integrity" class="toc-link">3. Academic Integrity</a></li>
                    <li><a href="#intellectual-property" class="toc-link">4. Content & Copyright</a></li>
                    <li><a href="#prohibited" class="toc-link">5. Prohibited Conduct</a></li>
                    <li><a href="#termination" class="toc-link">6. Account Termination</a></li>
                    <li><a href="#disclaimer" class="toc-link">7. Limitation of Liability</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Legal Content -->
        <main class="legal-card">

            <section id="acceptance" class="policy-section">
                <h2 class="section-heading"><span class="section-number">1</span> Acceptance of Terms</h2>
                <p>Welcome to <strong>Acadova</strong>, an educational quiz platform provided by <strong>Neody IT</strong> ("we", "us", "our"). By downloading, installing, or accessing our app or website, you ("User", "Student", "Faculty") agree to be bound by these Terms of Service.</p>
                <div class="highlight-box">
                    <strong>Important Note:</strong> If you do not agree to these terms, please do not access or use Acadova.
                </div>
            </section>

            <section id="accounts" class="policy-section">
                <h2 class="section-heading"><span class="section-number">2</span> User Accounts & Roles</h2>
                <p>Acadova supports dual account roles tailored for academic educational evaluation:</p>
                <ul>
                    <li><strong>Faculty Members:</strong> Responsible for creating assessments, managing question banks, creating test campaigns, and reviewing student score reports.</li>
                    <li><strong>Students:</strong> Access assigned quizzes, take timed assessments, review evaluation results, and monitor personal academic progress.</li>
                </ul>
                <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
            </section>

            <section id="integrity" class="policy-section">
                <h2 class="section-heading"><span class="section-number">3</span> Academic Integrity & Anti-Cheating Guidelines</h2>
                <p>Acadova is designed to maintain high academic evaluation standards. Users agree to:</p>
                <ul>
                    <li>Complete all assigned quizzes and assessments independently without unauthorized assistance.</li>
                    <li>Not distribute, copy, screenshot, or share proprietary quiz questions or answer keys.</li>
                    <li>Not attempt to reverse-engineer, exploit, or tamper with quiz timer limits or submission systems.</li>
                </ul>
            </section>

            <section id="intellectual-property" class="policy-section">
                <h2 class="section-heading"><span class="section-number">4</span> Content & Intellectual Property</h2>
                <p>All software, brand logos, user interfaces, design assets, and app infrastructure belong exclusively to <strong>Neody IT</strong>. Quiz questions created by faculty remain the property of their respective academic institution or author.</p>
            </section>

            <section id="prohibited" class="policy-section">
                <h2 class="section-heading"><span class="section-number">5</span> Prohibited Conduct</h2>
                <p>Users must not engage in any activity that interferes with or disrupts the service, including introducing malware, spamming, impersonating other users, or attempting unauthorized access to server endpoints.</p>
            </section>

            <section id="termination" class="policy-section">
                <h2 class="section-heading"><span class="section-number">6</span> Account Suspension & Termination</h2>
                <p>We reserve the right to suspend or terminate accounts that violate academic integrity guidelines, engage in fraudulent behavior, or breach these Terms of Service.</p>
            </section>

            <section id="disclaimer" class="policy-section">
                <h2 class="section-heading"><span class="section-number">7</span> Limitation of Liability</h2>
                <p>Acadova is provided "as is" without warranty of any kind. Neody IT shall not be held liable for any indirect, incidental, or consequential damages resulting from platform downtime or network interruptions during quiz submissions.</p>
            </section>

        </main>
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
