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
            if (!Schema::hasColumn('users', 'university_id')) {
                $table->foreignId('university_id')->nullable()->after('department')->constrained('universities')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'college_id')) {
                $table->foreignId('college_id')->nullable()->after('university_id')->constrained('colleges')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('college_id')->constrained('departments')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'course_id')) {
                $table->foreignId('course_id')->nullable()->after('department_id')->constrained('courses')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('course_id')->constrained('branches')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'section_id')) {
                $table->foreignId('section_id')->nullable()->after('branch_id')->constrained('sections')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'subsection_id')) {
                $table->foreignId('subsection_id')->nullable()->after('section_id')->constrained('subsections')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropForeign(['college_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['subsection_id']);

            $table->dropColumn([
                'university_id',
                'college_id',
                'department_id',
                'course_id',
                'branch_id',
                'section_id',
                'subsection_id'
            ]);
        });
    }
};
