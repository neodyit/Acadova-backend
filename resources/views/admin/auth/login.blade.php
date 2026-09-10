<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Acadova Control Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C5CE7;
            --primary-hover: #5B4BC4;
            --dark: #2D3436;
            --bg: #0F172A;
            --border: #334155;
            --card-bg: #1E293B;
            --text-muted: #94A3B8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: radial-gradient(circle at top right, #1E1B4B, #0F172A);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #F8FAFC;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            padding: 44px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), #a29bfe);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
        }

        .brand-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.5px;
        }

        .brand-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #E2E8F0;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border-radius: 14px;
            background: #0F172A;
            border: 1px solid var(--border);
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.25);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #FCA5A5;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.35);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-icon" style="background: white; overflow: hidden; padding: 6px; box-shadow: 0 8px 22px rgba(108, 92, 231, 0.3); border: 1px solid var(--border);">
                <img src="{{ asset('logo.png') }}" alt="Acadova Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1>Acadova Admin</h1>
            <p style="color: #00B894; font-size: 11px; font-weight: 700; letter-spacing: 1px; margin-top: 4px; text-transform: uppercase;">Powered by Neody IT</p>
        </div>

        @if($errors->any())
            <div class="alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Admin Email</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" class="form-control" placeholder="mayank@neodyit.in" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 28px;">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Secure Sign In</span>
            </button>
        </form>
    </div>

</body>
</html>
