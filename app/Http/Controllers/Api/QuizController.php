<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Get list of quizzes (Active / Upcoming / Completed)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Quiz::withCount('questions');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $quizzes = $query->latest()->get();

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
     * Create a new Quiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:active,upcoming,completed',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.options' => 'required_with:questions|array|min:2',
            'questions.*.correct_option' => 'required_with:questions|string',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'subject' => $validated['subject'] ?? 'General',
            'description' => $validated['description'] ?? '',
            'duration_minutes' => $validated['duration_minutes'],
            'status' => $validated['status'],
        ]);

        if (!empty($validated['questions'])) {
            foreach ($validated['questions'] as $q) {
                Question::create([
                    'quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'options' => $q['options'],
                    'correct_option' => $q['correct_option'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Quiz created successfully.',
            'data' => $quiz->load('questions'),
        ], 201);
    }

    /**
     * Add question to an existing quiz
     */
    public function addQuestion(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found.'], 404);
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|string',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_option' => $validated['correct_option'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question added successfully.',
            'data' => $question,
        ], 201);
    }

    /**
     * Delete a question
     */
    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Question not found.'], 404);
        }

        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully.',
        ]);
    }

    /**
     * Delete a quiz
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found.'], 404);
        }

        $quiz->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz deleted successfully.',
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
            'user_id' => $request->user() ? $request->user()->id : 1,
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
