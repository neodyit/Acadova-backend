<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} elseif (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
}

// Bootstrap Laravel application
$bootstrapPath = file_exists(__DIR__.'/../bootstrap/app.php') 
    ? __DIR__.'/../bootstrap/app.php' 
    : __DIR__.'/bootstrap/app.php';

/** @var Application $app */
$app = require_once $bootstrapPath;

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

// Clear cached config and route caches to ensure .env changes are loaded immediately
Artisan::call('config:clear');
Artisan::call('cache:clear');
\Illuminate\Support\Facades\DB::purge('mysql');
\Illuminate\Support\Facades\DB::reconnect('mysql');

echo "========================================\n";
echo "  Acadova Live Migration Runner \n";
echo "========================================\n\n";
echo "Active DB Host: " . config('database.connections.mysql.host') . "\n";
echo "Active DB Name: " . config('database.connections.mysql.database') . "\n";
echo "Active DB User: " . config('database.connections.mysql.username') . "\n\n";

try {
    echo "[1/3] Running pending migrations...\n";
    $exitCode = Artisan::call('migrate', [
        '--force' => true,
    ]);
    echo Artisan::output();
    echo "Migration Exit Code: " . $exitCode . "\n\n";

    echo "[2/3] Checking & ensuring dynamic table column updates...\n";
    if (\Illuminate\Support\Facades\Schema::hasTable('faculty_subject_allocations')) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('faculty_subject_allocations', 'branch_id')) {
            \Illuminate\Support\Facades\Schema::table('faculty_subject_allocations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('faculty_id')->constrained('branches')->onDelete('cascade');
                $table->string('branch_name')->nullable()->after('branch_id');
            });
            echo " -> Added 'branch_id' and 'branch_name' to faculty_subject_allocations.\n";
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('faculty_subject_allocations', 'semester')) {
            \Illuminate\Support\Facades\Schema::table('faculty_subject_allocations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('semester')->nullable()->after('section_name');
            });
            echo " -> Added 'semester' to faculty_subject_allocations.\n";
        }
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('quizzes')) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('quizzes', 'section')) {
            \Illuminate\Support\Facades\Schema::table('quizzes', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('section')->nullable()->after('subject');
            });
            echo " -> Added 'section' to quizzes table.\n";
        }
        if (!\Illuminate\Support\Facades\Schema::hasColumn('quizzes', 'department_ids')) {
            \Illuminate\Support\Facades\Schema::table('quizzes', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->json('department_ids')->nullable()->after('subject');
                $table->json('course_ids')->nullable()->after('department_ids');
                $table->json('branch_ids')->nullable()->after('course_ids');
                $table->json('section_ids')->nullable()->after('branch_ids');
                $table->json('subject_ids')->nullable()->after('section_ids');
            });
            echo " -> Added department_ids, course_ids, branch_ids, section_ids, subject_ids to quizzes table.\n";
        }
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('users') && !\Illuminate\Support\Facades\Schema::hasColumn('users', 'semester')) {
        \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('semester')->nullable()->after('subsection_id');
        });
        echo " -> Added 'semester' to users table.\n";
    }
    echo "Dynamic column checks complete.\n\n";

    $runSeed = isset($_GET['seed']) ? filter_var($_GET['seed'], FILTER_VALIDATE_BOOLEAN) : false;
    if ($runSeed) {
        echo "[3/3] Running database seeders...\n";
        $seedExitCode = Artisan::call('db:seed', [
            '--force' => true,
        ]);
        echo Artisan::output();
        echo "Seeding Exit Code: " . $seedExitCode . "\n\n";
    } else {
        echo "[3/3] Skipping database seeders (pass ?seed=1 to run seeders).\n\n";
    }

    echo "========================================\n";
    echo "  SUCCESS: Database is ready!\n";
    echo "========================================\n";
} catch (Exception $e) {
    echo "\n[ERROR] Migration/Seeding failed:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
