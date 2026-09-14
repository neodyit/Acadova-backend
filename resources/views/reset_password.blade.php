<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Acadova</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #B45309;
            --primary-hover: #92400E;
            --bg: #F8FAF6;
            --card-bg: #FFFFFF;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --error: #EF4444;
            --success: #10B981;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07);
            padding: 40px 32px;
        }
        .brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #7C2D12 0%, #B45309 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(180, 83, 9, 0.25);
            margin-bottom: 12px;
        }
        .brand h2 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }
        .brand p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(180, 83, 9, 0.12);
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #B45309 0%, #D97706 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 15.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(180, 83, 9, 0.3);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(180, 83, 9, 0.4);
        }
        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
            display: none;
        }
        .alert-error {
            background-color: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #991B1B;
        }
        .alert-success {
            background-color: #ECFDF5;
            border: 1px solid #6EE7B7;
            color: #065F46;
        }
        .open-app-banner {
            background: #FEF3C7;
            border: 1px solid #FCD34D;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 13px;
            color: #92400E;
            margin-bottom: 20px;
            text-align: center;
        }
        .open-app-btn {
            display: inline-block;
            margin-top: 6px;
            color: #B45309;
            font-weight: 700;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">
            <div class="brand-logo">A</div>
            <h2>Reset Password</h2>
            <p>Set a new password for your Acadova account</p>
        </div>

        <div id="alertError" class="alert alert-error"></div>
        <div id="alertSuccess" class="alert alert-success"></div>

        <div class="open-app-banner">
            📱 Have the Acadova Mobile App installed?<br>
            <a id="deepLinkBtn" href="#" class="open-app-btn">Tap to Open Acadova App</a>
        </div>

        <form id="resetForm">
            <input type="hidden" id="token" value="{{ request('token') }}">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" class="form-control" value="{{ request('email') }}" placeholder="Enter your email address" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" id="password" class="form-control" placeholder="Minimum 6 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" id="password_confirmation" class="form-control" placeholder="Re-enter new password" required>
            </div>
            <button type="submit" id="submitBtn" class="btn-submit">Reset Password</button>
        </form>
    </div>

    <script>
        const token = document.getElementById('token').value;
        const email = document.getElementById('email').value;
        if (token && email) {
            document.getElementById('deepLinkBtn').href = `acadova://reset-password?token=${encodeURIComponent(token)}&email=${encodeURIComponent(email)}`;
        }

        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const alertError = document.getElementById('alertError');
            const alertSuccess = document.getElementById('alertSuccess');

            alertError.style.display = 'none';
            alertSuccess.style.display = 'none';

            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password.length < 8) {
                alertError.innerText = 'Password must be at least 8 characters long.';
                alertError.style.display = 'block';
                return;
            }
            if (!/[A-Z]/.test(password)) {
                alertError.innerText = 'Password must contain at least 1 uppercase letter (A-Z).';
                alertError.style.display = 'block';
                return;
            }
            if (!/[a-z]/.test(password)) {
                alertError.innerText = 'Password must contain at least 1 lowercase letter (a-z).';
                alertError.style.display = 'block';
                return;
            }
            if (!/[0-9]/.test(password)) {
                alertError.innerText = 'Password must contain at least 1 number (0-9).';
                alertError.style.display = 'block';
                return;
            }
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                alertError.innerText = 'Password must contain at least 1 special character (@, #, $, etc.).';
                alertError.style.display = 'block';
                return;
            }

            if (password !== passwordConfirmation) {
                alertError.innerText = 'Passwords do not match.';
                alertError.style.display = 'block';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerText = 'Resetting Password...';

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: document.getElementById('token').value,
                        email: document.getElementById('email').value,
                        password: password,
                        password_confirmation: passwordConfirmation
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alertSuccess.innerText = data.message || 'Password reset successfully! Redirecting to login...';
                    alertSuccess.style.display = 'block';
                    document.getElementById('resetForm').style.display = 'none';
                    setTimeout(() => {
                        window.location.href = '/neodyit/login';
                    }, 2500);
                } else {
                    alertError.innerText = data.message || 'Failed to reset password. Token may be invalid or expired.';
                    alertError.style.display = 'block';
                }
            } catch (err) {
                alertError.innerText = 'Network error. Please try again.';
                alertError.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Reset Password';
            }
        });
    </script>
</body>
</html>
