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
        if (!Schema::hasColumn('campaigns', 'ends_at')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->timestamp('ends_at')->nullable()->after('is_featured');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('campaigns', 'ends_at')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('ends_at');
            });
        }
    }
};
