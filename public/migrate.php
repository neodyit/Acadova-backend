<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

define('LARAVEL_START', microtime(true));

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    die("Composer autoloader not found.\n");
}

// Bootstrap Laravel application
$bootstrapPath = file_exists(__DIR__.'/bootstrap/app.php') 
    ? __DIR__.'/bootstrap/app.php' 
    : (file_exists(__DIR__.'/../bootstrap/app.php') ? __DIR__.'/../bootstrap/app.php' : null);

if (!$bootstrapPath) {
    die("Laravel bootstrap app.php not found.\n");
}

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
try {
    Artisan::call('config:clear');
} catch (\Throwable $e) {}

try {
    Artisan::call('cache:clear');
} catch (\Throwable $e) {}

try {
    DB::purge('mysql');
    DB::reconnect('mysql');
} catch (\Throwable $e) {}

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
    if (Schema::hasTable('faculty_subject_allocations')) {
        if (!Schema::hasColumn('faculty_subject_allocations', 'branch_id')) {
            Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('faculty_id')->constrained('branches')->onDelete('cascade');
                $table->string('branch_name')->nullable()->after('branch_id');
            });
            echo " -> Added 'branch_id' and 'branch_name' to faculty_subject_allocations.\n";
        }
        if (!Schema::hasColumn('faculty_subject_allocations', 'semester')) {
            Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                $table->string('semester')->nullable()->after('section_name');
            });
            echo " -> Added 'semester' to faculty_subject_allocations.\n";
        }
    }

    if (Schema::hasTable('quizzes')) {
        if (!Schema::hasColumn('quizzes', 'section')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->string('section')->nullable()->after('subject');
            });
            echo " -> Added 'section' to quizzes table.\n";
        }
        if (!Schema::hasColumn('quizzes', 'department_ids')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->json('department_ids')->nullable()->after('subject');
                $table->json('course_ids')->nullable()->after('department_ids');
                $table->json('branch_ids')->nullable()->after('course_ids');
                $table->json('section_ids')->nullable()->after('branch_ids');
                $table->json('subject_ids')->nullable()->after('section_ids');
            });
            echo " -> Added department_ids, course_ids, branch_ids, section_ids, subject_ids to quizzes table.\n";
        }
        if (!Schema::hasColumn('quizzes', 'is_results_published')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->boolean('is_results_published')->default(false)->after('status');
            });
            echo " -> Added 'is_results_published' to quizzes table.\n";
        }
    }

    if (Schema::hasTable('users')) {
        if (!Schema::hasColumn('users', 'semester')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('semester')->nullable()->after('subsection_id');
            });
            echo " -> Added 'semester' to users table.\n";
        }
        if (!Schema::hasColumn('users', 'show_ads')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('show_ads')->default(true)->after('role');
            });
            echo " -> Added 'show_ads' to users table.\n";
        }
    }

    if (!Schema::hasTable('app_settings')) {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        DB::table('app_settings')->insert([
            ['key' => 'ads_enabled', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ads_target_audience', 'value' => 'all', 'created_at' => now(), 'updated_at' => now()],
        ]);
        echo " -> Created 'app_settings' table.\n";
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
} catch (\Throwable $e) {
    echo "\n[ERROR] Migration/Seeding failed:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
