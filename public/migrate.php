<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel application
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle request console environment for Artisan commands
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "========================================\n";
echo "  Acadova Live Migration Runner \n";
echo "========================================\n\n";

try {
    echo "[1/2] Running pending migrations...\n";
    $exitCode = Artisan::call('migrate', [
        '--force' => true,
    ]);
    echo Artisan::output();
    echo "Migration Exit Code: " . $exitCode . "\n\n";

    echo "[2/2] Running database seeders...\n";
    $seedExitCode = Artisan::call('db:seed', [
        '--force' => true,
    ]);
    echo Artisan::output();
    echo "Seeding Exit Code: " . $seedExitCode . "\n\n";

    echo "========================================\n";
    echo "  SUCCESS: Database is ready!\n";
    echo "========================================\n";
} catch (Exception $e) {
    echo "\n[ERROR] Migration/Seeding failed:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
