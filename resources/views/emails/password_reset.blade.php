<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Acadova Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #F8FAF6;
            color: #2D3436;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            background-color: #F8FAF6;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .card {
            max-width: 540px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #7C2D12 0%, #B45309 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .header h1 {
            color: #FFFFFF;
            font-size: 26px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header p {
            color: #FDE68A;
            font-size: 13px;
            margin: 6px 0 0 0;
            font-weight: 500;
        }
        .body {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 12px;
        }
        .text {
            font-size: 14.5px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .btn-container {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #B45309 0%, #D97706 100%);
            color: #FFFFFF !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(180, 83, 9, 0.35);
        }
        .badge-box {
            background-color: #FEF3C7;
            border: 1px solid #FCD34D;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            color: #92400E;
            margin-top: 20px;
        }
        .footer {
            background-color: #F1F5F9;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #64748B;
            border-top: 1px solid #E2E8F0;
        }
        .footer a {
            color: #B45309;
            text-decoration: none;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>Acadova</h1>
                <p>Academic Assessment & Evaluation Platform</p>
            </div>
            <div class="body">
                <div class="greeting">Hello {{ $userName }},</div>
                <div class="text">
                    We received a request to reset your Acadova portal password. Click the button below to securely update your password in the app or web portal.
                </div>
                
                <div class="btn-container">
                    <a href="{{ $resetUrl }}" class="btn">Reset Password Now</a>
                </div>

                <div class="badge-box">
                    💡 <strong>Tip:</strong> If you are opening this mail on your mobile device with the Acadova App installed, tapping the button will open the app directly to set your new password.
                </div>

                <div style="margin-top: 24px; font-size: 13px; color: #64748B;">
                    This password reset link will expire in 60 minutes. If you did not request a password reset, please ignore this email or contact support.
                </div>
            </div>
            <div class="footer">
                If the button above does not work, copy and paste this link into your browser:<br>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
                <br><br>
                &copy; {{ date('Y') }} Acadova. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
