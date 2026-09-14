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
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'department_ids')) {
                $table->json('department_ids')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('quizzes', 'course_ids')) {
                $table->json('course_ids')->nullable()->after('department_ids');
            }
            if (!Schema::hasColumn('quizzes', 'branch_ids')) {
                $table->json('branch_ids')->nullable()->after('course_ids');
            }
            if (!Schema::hasColumn('quizzes', 'section_ids')) {
                $table->json('section_ids')->nullable()->after('branch_ids');
            }
            if (!Schema::hasColumn('quizzes', 'subject_ids')) {
                $table->json('subject_ids')->nullable()->after('section_ids');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'department_ids',
                'course_ids',
                'branch_ids',
                'section_ids',
                'subject_ids',
            ]);
        });
    }
};
