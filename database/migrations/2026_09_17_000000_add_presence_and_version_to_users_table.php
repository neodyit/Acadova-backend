<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_active_at')->nullable()->index()->after('remember_token');
            $table->string('current_app_version', 50)->nullable()->index()->after('last_active_at');
            $table->string('device_platform', 30)->nullable()->index()->after('current_app_version'); // android, ios, web
            $table->string('device_model', 100)->nullable()->after('device_platform');
            $table->string('os_version', 50)->nullable()->after('device_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_active_at',
                'current_app_version',
                'device_platform',
                'device_model',
                'os_version'
            ]);
        });
    }
};
