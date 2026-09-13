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
        if (!Schema::hasTable('faculty_subject_allocations')) {
            Schema::create('faculty_subject_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
                $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
                $table->string('subject_name')->nullable();
                $table->string('section_name')->nullable();
                $table->string('semester')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('faculty_subject_allocations')) {
            if (!Schema::hasColumn('faculty_subject_allocations', 'branch_id')) {
                Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                    $table->foreignId('branch_id')->nullable()->after('faculty_id')->constrained('branches')->onDelete('cascade');
                    $table->string('branch_name')->nullable()->after('branch_id');
                });
            }

            if (!Schema::hasColumn('faculty_subject_allocations', 'semester')) {
                Schema::table('faculty_subject_allocations', function (Blueprint $table) {
                    $table->string('semester')->nullable()->after('section_name');
                });
            }
        }

        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'section')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->string('section')->nullable()->after('subject');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_subject_allocations');
        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'section')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('section');
            });
        }
    }
};
