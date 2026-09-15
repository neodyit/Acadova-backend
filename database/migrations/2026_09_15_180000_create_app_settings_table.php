<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            // Seed default ad configuration settings
            DB::table('app_settings')->insert([
                ['key' => 'ads_enabled', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'ads_target_audience', 'value' => 'all', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (!Schema::hasColumn('users', 'show_ads')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('show_ads')->default(true)->after('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');

        if (Schema::hasColumn('users', 'show_ads')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('show_ads');
            });
        }
    }
};
