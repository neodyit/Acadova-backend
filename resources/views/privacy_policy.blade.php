<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Acadova Quiz App | Neody IT</title>
    <meta name="description" content="Privacy Policy for Acadova Quiz App by Neody IT. Learn how we collect, protect, and manage your personal and academic data.">
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

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(180, 83, 9, 0.25);
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

        .nav-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--surface-secondary);
            color: var(--primary-dark);
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .nav-back-btn:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-1px);
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

        .hero-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
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

        .policy-section:last-child {
            margin-bottom: 0;
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

        .contact-box {
            background: linear-gradient(135deg, #FAF4EB 0%, #F5ECDD 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            margin-top: 16px;
        }

        .contact-box p {
            margin-bottom: 8px;
        }

        /* Footer */
        footer {
            max-width: 1200px;
            margin: 50px auto 0 auto;
            padding: 0 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .content-container {
                grid-template-columns: 1fr;
            }
            .sidebar {
                display: none;
            }
            .legal-card {
                padding: 28px 20px;
            }
            .hero-card {
                padding: 32px 24px;
            }
            .hero-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <a href="/" class="brand-logo">
                <img src="/logo.png" alt="Acadova Logo" class="brand-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="brand-icon" style="display: none;">A</div>
                <div>
                    <div class="brand-name">Acadova</div>
                    <div class="brand-subtitle">By Neody IT</div>
                </div>
            </a>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('privacy.policy') }}" class="nav-back-btn" style="background-color: var(--primary); color: white; border-color: var(--primary);">Privacy Policy</a>
                <a href="{{ route('terms.service') }}" class="nav-back-btn">Terms of Service</a>
                <a href="{{ route('help.center') }}" class="nav-back-btn">Help Center</a>
                <a href="{{ route('account.delete') }}" class="nav-back-btn">Account Deletion</a>
            </div>
        </div>
    </header>

    <!-- Hero Header -->
    <div class="hero-section">
        <div class="hero-card">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <img src="/logo.png" alt="Acadova Logo" style="width: 56px; height: 56px; object-fit: contain; background: white; padding: 6px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <div>
                    <span class="hero-badge">Official Policy</span>
                    <h1 class="hero-title" style="margin-bottom: 0;">Privacy Policy</h1>
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
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    Contents
                </div>
                <ul class="toc-list">
                    <li><a href="#overview" class="toc-link">1. Overview</a></li>
                    <li><a href="#information-collection" class="toc-link">2. Data We Collect</a></li>
                    <li><a href="#how-we-use-data" class="toc-link">3. How We Use Data</a></li>
                    <li><a href="#data-sharing" class="toc-link">4. Sharing & Disclosure</a></li>
                    <li><a href="#security" class="toc-link">5. Data Security</a></li>
                    <li><a href="#rights-deletion" class="toc-link">6. User Rights & Deletion</a></li>
                    <li><a href="#children" class="toc-link">7. Children's Privacy</a></li>
                    <li><a href="#contact" class="toc-link">8. Contact Us</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Privacy Policy Details -->
        <main class="legal-card">

            <section id="overview" class="policy-section">
                <h2 class="section-heading"><span class="section-number">1</span> Overview & Introduction</h2>
                <p>Welcome to <strong>Acadova</strong> ("App", "we", "us", or "our"), an educational quiz and academic assessment platform developed and operated by <strong>Neody IT</strong>.</p>
                <p>We are committed to protecting the privacy, security, and integrity of personal and academic information collected from users ("User", "Student", "Faculty", "you"). This Privacy Policy explains our practices regarding the collection, use, storage, and disclosure of your information when you use our mobile application and web services.</p>
                <div class="highlight-box">
                    <strong>Key Commitment:</strong> We do not sell your personal data. Your academic assessment records are processed strictly to deliver educational evaluation services to registered institutions and users.
                </div>
            </section>

            <section id="information-collection" class="policy-section">
                <h2 class="section-heading"><span class="section-number">2</span> Information We Collect</h2>
                <p>To provide a seamless assessment experience, we collect the following types of information:</p>
                <ul>
                    <li><strong>Account Information:</strong> Full name, institutional email address, user role (Student/Faculty), profile picture, and authentication credentials (such as Google Sign-In tokens or encrypted account passwords).</li>
                    <li><strong>Academic & Quiz Performance Data:</strong> Quiz responses, submission timestamps, score records, attempt metrics, assigned batch/department details, and campaign progress.</li>
                    <li><strong>Device & Technical Diagnostics:</strong> Operating system version, device model, app version, network status, unique device identifiers, and error logs for diagnostic and performance optimization purposes.</li>
                    <li><strong>Media & Uploads:</strong> Images or documents optionally uploaded by faculty members for quiz question context or student assignment attachments.</li>
                </ul>
            </section>

            <section id="how-we-use-data" class="policy-section">
                <h2 class="section-heading"><span class="section-number">3</span> How We Use Your Information</h2>
                <p>We utilize the collected information strictly for legitimate academic and functional purposes, including:</p>
                <ul>
                    <li>Authenticating user access and enforcing role-based permissions (Faculty vs. Student).</li>
                    <li>Conducting, evaluating, and generating real-time score reports for quizzes and assessments.</li>
                    <li>Enabling faculty members to monitor class performance and campaign progress.</li>
                    <li>Improving application stability, resolving technical bugs, and optimizing user experience.</li>
                    <li>Preventing unauthorized access, fraudulent activity, and academic dishonesty.</li>
                </ul>
            </section>

            <section id="data-sharing" class="policy-section">
                <h2 class="section-heading"><span class="section-number">4</span> Data Sharing & Disclosure</h2>
                <p>We strictly limit third-party access to your personal data. We do not sell, rent, or trade user data to advertisers or commercial marketers.</p>
                <p>Information may only be shared under the following limited circumstances:</p>
                <ul>
                    <li><strong>Authorized Academic Institutions:</strong> Faculty and administrators at your affiliated institution can access student assessment scores and submission reports.</li>
                    <li><strong>Trusted Service Providers:</strong> Secure infrastructure providers (e.g., Google Firebase / Google Authentication services) used solely to support authentication, database operations, and error logging.</li>
                    <li><strong>Legal Requirements:</strong> If required by law, court order, or governmental regulations to protect the rights, safety, or property of Neody IT or its users.</li>
                </ul>
            </section>

            <section id="security" class="policy-section">
                <h2 class="section-heading"><span class="section-number">5</span> Data Security & Storage</h2>
                <p>We implement robust industry-standard security measures to safeguard your information:</p>
                <ul>
                    <li>All data transmitted between the Acadova mobile application and our backend servers is encrypted in transit using <strong>SSL/TLS (HTTPS)</strong> protocols.</li>
                    <li>Authentication tokens are securely stored and validated using Sanctum token authentication.</li>
                    <li>Backend databases are hosted in secured server environments with strict access control mechanisms.</li>
                </ul>
            </section>

            <section id="rights-deletion" class="policy-section">
                <h2 class="section-heading"><span class="section-number">6</span> User Rights & Account Deletion</h2>
                <p>You have the right to access, update, or request the deletion of your personal information stored with Acadova:</p>
                <ul>
                    <li><strong>Access & Update:</strong> You can review and edit your profile details directly within the mobile application settings.</li>
                    <li><strong>Account & Data Deletion Request:</strong> If you wish to delete your account and associated personal data, you may submit a request through the app settings or by contacting our support team at <a href="mailto:mayank@neodyit.in" style="color: var(--primary); font-weight: 600;">mayank@neodyit.in</a>.</li>
                </ul>
            </section>

            <section id="children" class="policy-section">
                <h2 class="section-heading"><span class="section-number">7</span> Children's Privacy</h2>
                <p>Acadova is designed for academic institutions, higher education, and school students participating in supervised institutional assessments. We do not knowingly collect personal information directly from children under 13 without verifiable institutional or parental consent.</p>
            </section>

            <section id="contact" class="policy-section">
                <h2 class="section-heading"><span class="section-number">8</span> Contact Information</h2>
                <p>If you have any questions, concerns, or inquiries regarding this Privacy Policy or our data privacy practices, please contact us:</p>
                <div class="contact-box">
                    <p><strong>App Developer & Organization:</strong> Neody IT</p>
                    <p><strong>Application:</strong> Acadova Quiz App</p>
                    <p><strong>Support Email:</strong> <a href="mailto:mayank@neodyit.in" style="color: var(--primary); font-weight:600;">mayank@neodyit.in</a></p>
                    <p><strong>Website:</strong> <a href="https://neodyit.com" target="_blank" style="color: var(--primary); font-weight:600;">https://neodyit.com</a></p>
                </div>
            </section>

        </main>
    </div>

    <!-- Footer -->
    <footer>
        <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 16px; flex-wrap: wrap;">
            <a href="{{ route('privacy.policy') }}" style="color: var(--primary); font-weight:600; text-decoration: none;">Privacy Policy</a>
            <span>&bull;</span>
            <a href="{{ route('terms.service') }}" style="color: var(--primary); font-weight:600; text-decoration: none;">Terms of Service</a>
            <span>&bull;</span>
            <a href="{{ route('help.center') }}" style="color: var(--primary); font-weight:600; text-decoration: none;">Help Center</a>
            <span>&bull;</span>
            <a href="{{ route('account.delete') }}" style="color: var(--primary); font-weight:600; text-decoration: none;">Account Deletion</a>
        </div>
        <p>&copy; 2026 Neody IT. All rights reserved. &bull; Acadova Quiz Platform</p>
    </footer>

</body>
</html>
