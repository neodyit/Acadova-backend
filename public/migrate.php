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

// Support on-the-fly database credential override via URL GET parameters for easy setup
if (!empty($_GET['db_name'])) {
    config(['database.connections.mysql.database' => $_GET['db_name']]);
}
if (!empty($_GET['db_user'])) {
    config(['database.connections.mysql.username' => $_GET['db_user']]);
}
if (!empty($_GET['db_pass'])) {
    config(['database.connections.mysql.password' => $_GET['db_pass']]);
}
if (!empty($_GET['db_host'])) {
    config(['database.connections.mysql.host' => $_GET['db_host']]);
}

// Purge existing connection so MySQL reconnects with updated overrides
if (!empty($_GET['db_name']) || !empty($_GET['db_user']) || !empty($_GET['db_pass']) || !empty($_GET['db_host'])) {
    \Illuminate\Support\Facades\DB::purge('mysql');
    \Illuminate\Support\Facades\DB::reconnect('mysql');
}

echo "========================================\n";
echo "  Acadova Live Migration Runner \n";
echo "========================================\n\n";
echo "Active DB Host: " . config('database.connections.mysql.host') . "\n";
echo "Active DB Name: " . config('database.connections.mysql.database') . "\n";
echo "Active DB User: " . config('database.connections.mysql.username') . "\n\n";

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
