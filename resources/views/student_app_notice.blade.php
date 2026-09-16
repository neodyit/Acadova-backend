<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova - Mobile & Desktop App Required</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #B45309;
            --primary-hover: #92400E;
            --primary-light: #FEF3C7;
            --dark: #0F172A;
            --bg: #F8FAFC;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .notice-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            max-width: 520px;
            width: 100%;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .icon-circle {
            width: 72px;
            height: 72px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 24px auto;
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--dark);
        }

        p {
            font-size: 14.5px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14.5px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 14px rgba(180, 83, 9, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #F1F5F9;
            color: var(--dark);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }
    </style>
</head>
<body>

    <div class="notice-card">
        <div class="icon-circle">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <h1>Use Official Acadova App</h1>
        <p>Student logins and exam attempts are disabled on web browsers to maintain security and anti-cheat integrity. Please use the official <strong>Acadova App</strong> on your Android device or Windows PC.</p>
        
        <div class="cta-buttons">
            <a href="https://play.google.com/store/apps/details?id=com.neodyit.acadova" target="_blank" class="btn btn-primary">
                <i class="fa-brands fa-google-play"></i> Get Android App
            </a>
            <a href="/" class="btn btn-secondary">
                <i class="fa-solid fa-house"></i> Back to Home
            </a>
        </div>
    </div>

</body>
</html>
