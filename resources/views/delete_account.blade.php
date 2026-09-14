<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion Request - Acadova Quiz App | Neody IT</title>
    <meta name="description" content="Submit an account deletion request for Acadova Quiz App. Learn about data removal, account deletion process, and data retention policy.">
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
            --danger: #B91C1C;
            --danger-bg: #FEF2F2;
            --success: #15803D;
            --success-bg: #F0FDF4;
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
            max-width: 1000px;
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
        }

        /* Container & Cards */
        .main-container {
            max-width: 900px;
            margin: 40px auto 0 auto;
            padding: 0 24px;
        }

        .hero-card {
            background: linear-gradient(135deg, #78350F 0%, #B45309 100%);
            color: white;
            padding: 40px;
            border-radius: var(--radius-lg);
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(120, 53, 15, 0.2);
            position: relative;
            overflow: hidden;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero-desc {
            font-size: 15px;
            opacity: 0.92;
            max-width: 700px;
        }

        .content-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success);
            color: var(--success);
            padding: 16px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-box {
            background-color: var(--surface-secondary);
            border-left: 4px solid var(--danger);
            padding: 20px;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            margin-bottom: 30px;
        }

        .info-box-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--danger);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box ul {
            padding-left: 20px;
            margin-top: 8px;
        }

        .info-box li {
            font-size: 14px;
            color: #333333;
            margin-bottom: 6px;
        }

        /* Form Controls */
        .form-title {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--surface-secondary);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            font-family: inherit;
            font-size: 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: var(--surface-secondary);
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(180, 83, 9, 0.12);
        }

        .form-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: var(--text-muted);
            cursor: pointer;
            margin-top: 10px;
        }

        .form-checkbox-label input {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background: linear-gradient(135deg, var(--danger) 0%, #991B1B 100%);
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            padding: 14px 28px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(185, 28, 28, 0.25);
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(185, 28, 28, 0.35);
        }

        .contact-box {
            background-color: var(--surface-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            margin-top: 30px;
            font-size: 14px;
        }

        /* Footer */
        footer {
            max-width: 900px;
            margin: 40px auto 0 auto;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .content-card, .hero-card {
                padding: 24px 20px;
            }
            .hero-title {
                font-size: 26px;
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
            <a href="/" class="nav-back-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Home
            </a>
        </div>
    </header>

    <div class="main-container">

        <!-- Hero Header -->
        <div class="hero-card">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;">
                <img src="/logo.png" alt="Acadova Logo" style="width: 50px; height: 50px; object-fit: contain; background: white; padding: 4px; border-radius: 12px;">
                <div>
                    <h1 class="hero-title">Account Deletion Request</h1>
                </div>
            </div>
            <p class="hero-desc">Submit a request to permanently delete your Acadova user account and remove your personal assessment data.</p>
        </div>

        <!-- Form & Info Card -->
        <div class="content-card">

            @if(session('success'))
                <div class="alert-success">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Impact Info Box -->
            <div class="info-box">
                <div class="info-box-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    Important Information Before Requesting Deletion
                </div>
                <ul>
                    <li><strong>Data Removed:</strong> Your profile details, login credentials, authentication tokens, and personal app settings will be permanently purged.</li>
                    <li><strong>Academic Assessment Records:</strong> Historical quiz submissions and scores recorded for institutional evaluations may be retained in anonymized format as required by your academic institution.</li>
                    <li><strong>Processing Timeline:</strong> Deletion requests are processed within <strong>24 to 48 hours</strong> following identity verification.</li>
                </ul>
            </div>

            <!-- Deletion Request Form -->
            <h2 class="form-title">Submit Deletion Form</h2>

            <form action="{{ route('account.delete.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Full Name <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Mayank Tiwari" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Registered Email Address <span style="color: var(--danger);">*</span></label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="e.g. student@institution.edu" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Account Role <span style="color: var(--danger);">*</span></label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="" disabled selected>Select your account role</option>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty Member</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="reason">Reason for Deletion (Optional)</label>
                    <textarea id="reason" name="reason" rows="3" class="form-textarea" placeholder="Please let us know why you wish to delete your account..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-checkbox-label">
                        <input type="checkbox" name="confirm" required>
                        <span>I confirm that I want to permanently delete my Acadova account and understand that this action cannot be undone.</span>
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    Submit Account Deletion Request
                </button>
            </form>

            <div class="contact-box">
                <p><strong>Need Help or Have Questions?</strong></p>
                <p>You can also request account deletion directly by emailing our data protection team at <a href="mailto:mayank@neodyit.in" style="color: var(--primary); font-weight: 600;">mayank@neodyit.in</a> with the subject line <em>"Account Deletion Request - Acadova"</em>.</p>
            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Neody IT. All rights reserved. &bull; Acadova Quiz Platform</p>
    </footer>

</body>
</html>
