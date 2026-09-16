<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizReattemptLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacultyReattemptController extends Controller
{
    /**
     * Check if a quiz attempt is eligible for a faculty-granted reattempt.
     */
    public function checkEligibility(Request $request, $attemptId): JsonResponse
    {
        $attempt = QuizAttempt::with(['quiz', 'user'])->find($attemptId);

        if (!$attempt) {
            return response()->json([
                'status' => false,
                'eligible' => false,
                'reason' => 'Quiz attempt record not found.',
            ], 404);
        }

        $quiz = $attempt->quiz;
        if (!$quiz) {
            return response()->json([
                'status' => false,
                'eligible' => false,
                'reason' => 'Associated quiz record not found.',
            ], 404);
        }

        // 1. Check if submission is within 3 hours
        $submittedAt = $attempt->created_at;
        if ($submittedAt && $submittedAt->lt(now()->subHours(3))) {
            return response()->json([
                'status' => true,
                'eligible' => false,
                'reason' => 'Reattempt window expired (Submission is older than 3 hours).',
                'submitted_at' => $submittedAt->toIso8601String(),
            ]);
        }

        // 2. Check if quiz is still active and open
        if (strtolower($quiz->status) !== 'active' && strtolower($quiz->status) !== 'scheduled') {
            return response()->json([
                'status' => true,
                'eligible' => false,
                'reason' => 'Quiz is currently inactive.',
            ]);
        }

        if ($quiz->ends_at && $quiz->ends_at->lt(now())) {
            return response()->json([
                'status' => true,
                'eligible' => false,
                'reason' => 'Quiz deadline has ended (Quiz closed at ' . $quiz->ends_at->format('M d, Y h:i A') . ').',
                'ends_at' => $quiz->ends_at->toIso8601String(),
            ]);
        }

        // 3. Check if faculty has already granted 1 reattempt for this student + quiz
        $alreadyGranted = QuizReattemptLog::where('student_id', $attempt->user_id)
            ->where('quiz_id', $attempt->quiz_id)
            ->exists();

        if ($alreadyGranted) {
            return response()->json([
                'status' => true,
                'eligible' => false,
                'reason' => 'Reattempt limit reached (Maximum 1 faculty reattempt per quiz allowed).',
            ]);
        }

        return response()->json([
            'status' => true,
            'eligible' => true,
            'message' => 'Student is eligible for a faculty-granted reattempt.',
            'student_name' => $attempt->user?->name,
            'quiz_title' => $quiz->title,
        ]);
    }

    /**
     * Grant a faculty reattempt to a student, creating an audit log and resetting original attempt.
     */
    public function grantReattempt(Request $request, $attemptId): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $faculty = $request->user();

        // Ensure user has faculty or admin role
        if (!in_array(strtolower($faculty->role), ['faculty', 'admin'])) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Only faculty and administrators can grant reattempts.',
            ], 403);
        }

        $attempt = QuizAttempt::with(['quiz', 'user'])->find($attemptId);

        if (!$attempt) {
            return response()->json([
                'status' => false,
                'message' => 'Quiz attempt record not found.',
            ], 404);
        }

        $quiz = $attempt->quiz;
        if (!$quiz) {
            return response()->json([
                'status' => false,
                'message' => 'Associated quiz record not found.',
            ], 404);
        }

        // 1. Validate submission age <= 3 hours
        $submittedAt = $attempt->created_at;
        if ($submittedAt && $submittedAt->lt(now()->subHours(3))) {
            return response()->json([
                'status' => false,
                'message' => 'Reattempt cannot be granted. Submission is older than 3 hours (submitted on ' . $submittedAt->format('M d, h:i A') . ').',
            ], 422);
        }

        // 2. Validate quiz is active & not expired
        if (strtolower($quiz->status) !== 'active' && strtolower($quiz->status) !== 'scheduled') {
            return response()->json([
                'status' => false,
                'message' => 'Reattempt cannot be granted. Quiz status is inactive.',
            ], 422);
        }

        if ($quiz->ends_at && $quiz->ends_at->lt(now())) {
            return response()->json([
                'status' => false,
                'message' => 'Reattempt cannot be granted. Quiz ended on ' . $quiz->ends_at->format('M d, Y h:i A') . '.',
            ], 422);
        }

        // 3. Validate max 1 reattempt limit
        $alreadyGranted = QuizReattemptLog::where('student_id', $attempt->user_id)
            ->where('quiz_id', $attempt->quiz_id)
            ->exists();

        if ($alreadyGranted) {
            return response()->json([
                'status' => false,
                'message' => 'Reattempt limit reached. This student has already been granted a faculty reattempt for this quiz.',
            ], 422);
        }

        // Execute audit log creation and attempt reset atomically
        DB::transaction(function () use ($faculty, $attempt, $quiz, $validated) {
            // Log audit record
            QuizReattemptLog::create([
                'faculty_id' => $faculty->id,
                'student_id' => $attempt->user_id,
                'quiz_id' => $attempt->quiz_id,
                'original_attempt_id' => $attempt->id,
                'reason' => trim($validated['reason']),
                'granted_at' => now(),
            ]);

            // Reset attempt so student can retake
            $attempt->delete();
        });

        Log::info("FACULTY REATTEMPT GRANTED: Faculty #{$faculty->id} granted reattempt to Student #{$attempt->user_id} for Quiz #{$quiz->id}. Reason: {$validated['reason']}");

        return response()->json([
            'status' => true,
            'message' => 'Reattempt granted successfully! Original attempt has been reset and student can now retake this quiz.',
        ]);
    }
}
