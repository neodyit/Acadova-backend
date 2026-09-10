<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Get list of quizzes (Active / Upcoming / Completed)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');

        $quizzes = Quiz::withCount('questions')
            ->where('status', $status)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $quizzes,
        ]);
    }

    /**
     * Get details of a single quiz with its questions
     */
    public function show($id)
    {
        $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $quiz,
        ]);
    }

    /**
     * Submit attempt results
     */
    public function submitAttempt(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz not found.',
            ], 404);
        }

        $validated = $request->validate([
            'user_answers' => 'nullable|array',
            'violations_count' => 'nullable|integer',
        ]);

        $userAnswers = $validated['user_answers'] ?? [];
        $score = 0;

        foreach ($quiz->questions as $question) {
            $userAns = $userAnswers[$question->id] ?? null;
            if ($userAns && $userAns === $question->correct_option) {
                $score++;
            }
        }

        $attempt = QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $quiz->questions->count(),
            'user_answers' => $userAnswers,
            'violations_count' => $validated['violations_count'] ?? 0,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz submitted successfully.',
            'data' => [
                'attempt' => $attempt,
                'score' => $score,
                'total_questions' => $quiz->questions->count(),
            ],
        ]);
    }
}
