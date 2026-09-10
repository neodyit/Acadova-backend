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
            'instructor' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:active,upcoming,completed',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'subject' => $validated['subject'] ?? 'General',
            'instructor' => $validated['instructor'] ?? 'Faculty',
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'description' => $validated['description'] ?? '',
            'duration_minutes' => $validated['duration_minutes'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz created successfully.',
            'data' => $quiz->load('questions'),
        ], 201);
    }

    /**
     * Update an existing Quiz
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found.'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'instructor' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:active,upcoming,completed',
        ]);

        $quiz->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quiz updated successfully.',
            'data' => $quiz->load('questions'),
        ]);
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
            'type' => 'required|in:single,multiple',
            'options' => 'required|array|min:2',
            'correct_option' => 'required',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'type' => $validated['type'],
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

            if ($question->type === 'multiple') {
                // Multiple choice checking: compare array elements
                $correctAnsArr = is_array($question->correct_option) ? $question->correct_option : [$question->correct_option];
                $userAnsArr = is_array($userAns) ? $userAns : ($userAns ? [$userAns] : []);
                
                sort($correctAnsArr);
                sort($userAnsArr);

                if ($correctAnsArr === $userAnsArr) {
                    $score++;
                }
            } else {
                // Single choice checking
                if ($userAns && (string)$userAns === (string)$question->correct_option) {
                    $score++;
                }
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
