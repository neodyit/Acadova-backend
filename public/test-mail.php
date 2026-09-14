<?php
/**
 * Standalone SMTP Mail Tester Script for Hostinger / Laravel
 * Access in browser: https://acadova.neodyit.com/test-mail.php
 */

// Load Laravel .env environment variables manually if Laravel framework is not loaded
$envFile = __DIR__ . '/.env';
$envVars = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \"'\t\n\r\0\x0B");
            $envVars[$name] = $value;
        }
    }
}

$mailer = $envVars['MAIL_MAILER'] ?? getenv('MAIL_MAILER') ?? 'smtp';
$host = $envVars['MAIL_HOST'] ?? getenv('MAIL_HOST') ?? 'smtp.hostinger.com';
$port = $envVars['MAIL_PORT'] ?? getenv('MAIL_PORT') ?? '465';
$username = $envVars['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME') ?? 'noreply@acadova.neodyit.com';
$password = $envVars['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD') ?? '';
$fromAddress = $envVars['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?? $username;
$fromName = $envVars['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'Acadova Test';
$encryption = $envVars['MAIL_ENCRYPTION'] ?? getenv('MAIL_ENCRYPTION') ?? ($port == 465 ? 'ssl' : 'tls');

$message = '';
$statusClass = '';
$debugOutput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $toEmail = trim($_POST['target_email'] ?? '');
    
    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid target email address.";
        $statusClass = "error";
    } else {
        $subject = "Acadova SMTP Test Email - " . date('Y-m-d H:i:s');
        $bodyText = "Hello,\n\nThis is a test email sent from Acadova standalone SMTP tester.\nTimestamp: " . date('Y-m-d H:i:s') . "\nSMTP Host: $host:$port ($encryption)\nSender: $fromAddress\n\nIf you received this email, Hostinger SMTP configuration is working correctly!";
        
        // 1. Try Laravel Framework Mail facade if available
        if (file_exists(__DIR__ . '/vendor/autoload.php') && file_exists(__DIR__ . '/bootstrap/app.php')) {
            try {
                require __DIR__ . '/vendor/autoload.php';
                $app = require_once __DIR__ . '/bootstrap/app.php';
                $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                $kernel->bootstrap();

                \Illuminate\Support\Facades\Mail::raw($bodyText, function ($m) use ($toEmail, $subject, $fromAddress, $fromName) {
                    $m->to($toEmail)->subject($subject)->from($fromAddress, $fromName);
                });

                $message = "SUCCESS! Test email dispatched via Laravel Mailer to <strong>$toEmail</strong>!";
                $statusClass = "success";
            } catch (\Throwable $e) {
                $debugOutput .= "Laravel Mailer Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\nAttempting Native Socket Connection...\n";
                // Fallback to Native Socket Test below
            }
        }
        
        // 2. Native Socket Connection Test if Laravel Mailer failed or not bootstrapped
        if ($statusClass !== 'success') {
            ob_start();
            $socketHost = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
            echo "Connecting to $socketHost:$port...\n";
            $fp = @fsockopen($socketHost, (int)$port, $errno, $errstr, 15);
            
            if (!$fp) {
                echo "Socket Connection Failed! Error [$errno]: $errstr\n";
                $message = "FAILED! Cannot connect to SMTP server $host on port $port. Error: $errstr ($errno)";
                $statusClass = "error";
            } else {
                echo "Connected!\nServer Response: " . fgets($fp, 512);
                
                fputs($fp, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
                echo "EHLO Sent. Response:\n";
                while ($line = fgets($fp, 512)) {
                    echo $line;
                    if (substr($line, 3, 1) == " ") break;
                }
                
                if ($encryption === 'tls') {
                    fputs($fp, "STARTTLS\r\n");
                    echo "STARTTLS Sent. Response: " . fgets($fp, 512);
                    stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                    fputs($fp, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
                    while ($line = fgets($fp, 512)) {
                        echo $line;
                        if (substr($line, 3, 1) == " ") break;
                    }
                }
                
                fputs($fp, "AUTH LOGIN\r\n");
                echo "AUTH LOGIN Sent. Response: " . fgets($fp, 512);
                
                fputs($fp, base64_encode($username) . "\r\n");
                echo "User Sent. Response: " . fgets($fp, 512);
                
                fputs($fp, base64_encode($password) . "\r\n");
                $authResponse = fgets($fp, 512);
                echo "Pass Sent. Response: " . $authResponse;
                
                if (strpos($authResponse, '235') !== 0) {
                    $message = "AUTHENTICATION FAILED! SMTP server rejected username or password.";
                    $statusClass = "error";
                } else {
                    fputs($fp, "MAIL FROM: <$fromAddress>\r\n");
                    echo "MAIL FROM Sent. Response: " . fgets($fp, 512);
                    
                    fputs($fp, "RCPT TO: <$toEmail>\r\n");
                    echo "RCPT TO Sent. Response: " . fgets($fp, 512);
                    
                    fputs($fp, "DATA\r\n");
                    echo "DATA Sent. Response: " . fgets($fp, 512);
                    
                    $headers = "From: $fromName <$fromAddress>\r\n";
                    $headers .= "To: $toEmail\r\n";
                    $headers .= "Subject: $subject\r\n";
                    $headers .= "Date: " . date('r') . "\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
                    
                    fputs($fp, $headers . $bodyText . "\r\n.\r\n");
                    $sendResponse = fgets($fp, 512);
                    echo "Payload Sent. Response: " . $sendResponse;
                    
                    fputs($fp, "QUIT\r\n");
                    
                    if (strpos($sendResponse, '250') === 0) {
                        $message = "SUCCESS! Test mail delivered to <strong>$toEmail</strong> via Socket SMTP!";
                        $statusClass = "success";
                    } else {
                        $message = "SMTP DISPATCH FAILED: " . $sendResponse;
                        $statusClass = "error";
                    }
                }
                fclose($fp);
            }
            $debugOutput .= ob_get_clean();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acadova SMTP Mail Tester</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #F8FAF6; color: #1E293B; margin: 0; padding: 30px; }
        .card { max-width: 680px; margin: 0 auto; background: white; border-radius: 16px; border: 1px solid #E2E8F0; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h2 { color: #B45309; margin-top: 0; }
        .config-box { background: #FEF3C7; border: 1px solid #FCD34D; border-radius: 10px; padding: 14px; font-size: 13px; color: #92400E; margin-bottom: 20px; }
        .config-box table { width: 100%; border-collapse: collapse; }
        .config-box td { padding: 4px 6px; font-family: monospace; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; }
        input[type="email"] { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #CBD5E1; font-size: 15px; box-sizing: border-box; }
        button { background: #B45309; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-size: 15px; font-weight: bold; cursor: pointer; }
        button:hover { background: #92400E; }
        .alert { padding: 14px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .alert.success { background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; }
        .alert.error { background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; }
        pre { background: #1E293B; color: #38BDF8; padding: 16px; border-radius: 10px; overflow-x: auto; font-size: 12.5px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>📧 Acadova SMTP Test Tool</h2>
        <p>Use this script to verify your Hostinger SMTP credentials & email dispatch capability.</p>

        <div class="config-box">
            <strong>Current Loaded SMTP Config (.env):</strong>
            <table>
                <tr><td>MAIL_MAILER:</td><td><?= htmlspecialchars($mailer) ?></td></tr>
                <tr><td>MAIL_HOST:</td><td><?= htmlspecialchars($host) ?></td></tr>
                <tr><td>MAIL_PORT:</td><td><?= htmlspecialchars($port) ?></td></tr>
                <tr><td>MAIL_ENCRYPTION:</td><td><?= htmlspecialchars($encryption) ?></td></tr>
                <tr><td>MAIL_USERNAME:</td><td><?= htmlspecialchars($username) ?></td></tr>
                <tr><td>MAIL_PASSWORD:</td><td><?= $password ? '•••••••• (' . strlen($password) . ' chars)' : '<strong style="color:red">NOT SET / EMPTY</strong>' ?></td></tr>
                <tr><td>MAIL_FROM_ADDRESS:</td><td><?= htmlspecialchars($fromAddress) ?></td></tr>
            </table>
        </div>

        <?php if ($message): ?>
            <div class="alert <?= $statusClass ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Send Test Email To:</label>
                <input type="email" name="target_email" placeholder="enter your personal email (e.g. name@gmail.com)" required value="<?= htmlspecialchars($_POST['target_email'] ?? '') ?>">
            </div>
            <button type="submit">🚀 Send Test Email Now</button>
        </form>

        <?php if ($debugOutput): ?>
            <h4 style="margin-top: 25px;">Detailed SMTP Debug Transcript:</h4>
            <pre><?= htmlspecialchars($debugOutput) ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>
