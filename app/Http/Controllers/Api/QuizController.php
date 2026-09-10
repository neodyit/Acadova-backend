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
     * Update an existing question
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Question not found.'], 404);
        }

        $validated = $request->validate([
            'question' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:single,multiple',
            'options' => 'sometimes|required|array|min:2',
            'correct_option' => 'sometimes|required',
        ]);

        $question->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Question updated successfully.',
            'data' => $question,
        ]);
    }

    /**
     * Import questions from CSV file
     */
    public function importQuestionsCsv(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Quiz not found.'], 404);
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return response()->json(['success' => false, 'message' => 'Unable to read CSV file.'], 400);
        }

        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            return response()->json(['success' => false, 'message' => 'CSV file is empty.'], 400);
        }

        // Clean headers: lowercase and trimmed
        $header = array_map(function($h) {
            return strtolower(trim($h));
        }, $header);

        $importedCount = 0;
        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            if (count($row) < 4) continue;

            $data = array_combine(array_slice($header, 0, count($row)), $row);

            $qText = $data['question'] ?? null;
            if (!$qText) continue;

            $type = isset($data['type']) && strtolower(trim($data['type'])) === 'multiple' ? 'multiple' : 'single';
            
            // Extract options
            $options = [];
            for ($i = 1; $i <= 6; $i++) {
                $key = "option{$i}";
                if (!empty($data[$key])) {
                    $options[] = trim($data[$key]);
                }
            }

            if (empty($options) && isset($data['options'])) {
                $options = array_map('trim', explode('|', $data['options']));
            }

            if (count($options) < 2) continue;

            $rawCorrect = $data['correct_option'] ?? ($data['correct'] ?? '');
            $correctOption = null;

            if ($type === 'multiple') {
                $correctParts = array_map('trim', explode('|', $rawCorrect));
                $correctOption = [];
                foreach ($correctParts as $part) {
                    if (is_numeric($part) && isset($options[(int)$part - 1])) {
                        $correctOption[] = $options[(int)$part - 1];
                    } else if (in_array($part, $options)) {
                        $correctOption[] = $part;
                    }
                }
                if (empty($correctOption)) {
                    $correctOption = [$options[0]];
                }
            } else {
                if (is_numeric($rawCorrect) && isset($options[(int)$rawCorrect - 1])) {
                    $correctOption = $options[(int)$rawCorrect - 1];
                } else if (in_array($rawCorrect, $options)) {
                    $correctOption = $rawCorrect;
                } else {
                    $correctOption = $options[0];
                }
            }

            Question::create([
                'quiz_id' => $quiz->id,
                'question' => $qText,
                'type' => $type,
                'options' => $options,
                'correct_option' => $correctOption,
            ]);

            $importedCount++;
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$importedCount} questions from CSV.",
            'imported_count' => $importedCount,
        ]);
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
    /**
     * Get attempts history for current user or all attempts
     */
    public function getAttempts(Request $request)
    {
        $userId = $request->user() ? $request->user()->id : null;

        $query = QuizAttempt::with('quiz');
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attempts = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $attempts,
        ]);
    }
}
