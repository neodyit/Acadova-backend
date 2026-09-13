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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('violations_count');
            $table->string('location')->nullable()->after('ip_address');
            $table->string('latitude')->nullable()->after('location');
            $table->string('longitude')->nullable()->after('latitude');
            $table->enum('submission_type', ['manual', 'auto'])->default('manual')->after('longitude');
            $table->string('auto_submit_reason')->nullable()->after('submission_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'location',
                'latitude',
                'longitude',
                'submission_type',
                'auto_submit_reason',
            ]);
        });
    }
};
