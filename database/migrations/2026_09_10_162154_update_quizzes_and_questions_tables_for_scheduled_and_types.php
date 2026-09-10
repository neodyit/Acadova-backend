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
            $table->dateTime('scheduled_at')->nullable()->after('status');
            $table->string('instructor')->nullable()->after('scheduled_at');
            $table->integer('passing_marks')->nullable()->after('instructor');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->enum('type', ['single', 'multiple'])->default('single')->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['scheduled_at', 'instructor', 'passing_marks']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
