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
        if (Schema::hasTable('faculty_subject_allocations')) {
            if (!Schema::hasColumn('faculty_subject_allocations', 'branch_id')) {
                Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                    $table->foreignId('branch_id')->nullable()->after('faculty_id')->constrained('branches')->onDelete('cascade');
                });
            }
            if (!Schema::hasColumn('faculty_subject_allocations', 'branch_name')) {
                Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                    $table->string('branch_name')->nullable()->after('branch_id');
                });
            }
            if (!Schema::hasColumn('faculty_subject_allocations', 'semester')) {
                Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                    $table->string('semester')->nullable()->after('section_name');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('faculty_subject_allocations')) {
            Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                if (Schema::hasColumn('faculty_subject_allocations', 'branch_id')) {
                    $table->dropForeign(['branch_id']);
                    $table->dropColumn('branch_id');
                }
                if (Schema::hasColumn('faculty_subject_allocations', 'branch_name')) {
                    $table->dropColumn('branch_name');
                }
                if (Schema::hasColumn('faculty_subject_allocations', 'semester')) {
                    $table->dropColumn('semester');
                }
            });
        }
    }
};
