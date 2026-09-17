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
        $user = auth('sanctum')->user() ?? $request->user();

        $query = Quiz::withCount('questions');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Faculty role filter: only show quizzes created by this faculty member
        if ($user && strtolower($user->role) === 'faculty') {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhere('instructor', $user->name);
                if (!empty($user->full_name)) {
                    $q->orWhere('instructor', $user->full_name);
                }
            });
        }

        $quizzes = $query->latest()->get();
        if ($user && strtolower($user->role) === 'student') {
            $quizzes = $quizzes->filter(function ($quiz) use ($user) {
                return $quiz->isTargetedToStudent($user);
            })->values();
        }

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

        $user = auth('sanctum')->user() ?? request()->user();
        if ($user && strtolower($user->role) === 'student') {
            if (!$quiz->isTargetedToStudent($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quiz not available for your department/batch scope.',
                ], 403);
            }
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
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:active,upcoming,completed',
            'department_ids' => 'nullable|array',
            'course_ids' => 'nullable|array',
            'branch_ids' => 'nullable|array',
            'section_ids' => 'nullable|array',
            'subject_ids' => 'nullable|array',
            'target_groups' => 'nullable|array',
        ]);

        $user = auth('sanctum')->user() ?? $request->user();
        $userId = $user ? $user->id : null;

        if ($user && strtolower($user->role) === 'faculty') {
            $hasTargetGroups = !empty($validated['target_groups']) && is_array($validated['target_groups']) && count($validated['target_groups']) > 0;
            $hasExplicitDepts = !empty($validated['department_ids']) && is_array($validated['department_ids']) && !in_array('all', $validated['department_ids']) && count($validated['department_ids']) > 0;

            if (!$hasTargetGroups && !$hasExplicitDepts) {
                $allocations = \App\Models\FacultySubjectAllocation::where('faculty_id', $user->id)->get();
                if ($allocations->isEmpty()) {
                    $validated['department_ids'] = [];
                    $validated['course_ids'] = [];
                    $validated['branch_ids'] = [];
                    $validated['section_ids'] = [];
                    $validated['subject_ids'] = [];
                    $validated['target_groups'] = null;
                } else {
                    $builtGroups = [];
                    foreach ($allocations as $alloc) {
                        $group = [
                            'branch_id' => $alloc->branch_name ?? $alloc->branch_code ?? $alloc->branch_id ?? 'all',
                            'section_id' => (string)($alloc->section_name ?? $alloc->section_id ?? 'all'),
                        ];
                        if ($alloc->branch_id) $group['branch_db_id'] = $alloc->branch_id;
                        if ($alloc->department_id) $group['department_id'] = $alloc->department_id;
                        if ($alloc->course_id) $group['course_id'] = $alloc->course_id;
                        if ($alloc->semester) $group['semester'] = (string)$alloc->semester;
                        $builtGroups[] = $group;
                    }
                    $validated['target_groups'] = $builtGroups;
                }
            }
        }

        $quiz = Quiz::create([
            'user_id' => $userId,
            'created_by' => $userId,
            'title' => $validated['title'],
            'subject' => $validated['subject'] ?? 'General',
            'instructor' => $validated['instructor'] ?? ($user ? ($user->name ?? $user->full_name ?? 'Faculty') : 'Faculty'),
            'scheduled_at' => $validated['scheduled_at'] ?? $validated['starts_at'] ?? null,
            'starts_at' => $validated['starts_at'] ?? $validated['scheduled_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'description' => $validated['description'] ?? '',
            'duration_minutes' => $validated['duration_minutes'],
            'passing_marks' => $validated['passing_marks'] ?? null,
            'status' => $validated['status'],
            'department_ids' => $validated['department_ids'] ?? null,
            'course_ids' => $validated['course_ids'] ?? null,
            'branch_ids' => $validated['branch_ids'] ?? null,
            'section_ids' => $validated['section_ids'] ?? null,
            'subject_ids' => $validated['subject_ids'] ?? null,
            'target_groups' => $validated['target_groups'] ?? null,
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
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_marks' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:active,upcoming,completed',
            'department_ids' => 'nullable|array',
            'course_ids' => 'nullable|array',
            'branch_ids' => 'nullable|array',
            'section_ids' => 'nullable|array',
            'subject_ids' => 'nullable|array',
            'target_groups' => 'nullable|array',
        ]);

        $user = auth('sanctum')->user() ?? $request->user();

        if ($user && strtolower($user->role) === 'faculty') {
            $hasTargetGroups = !empty($validated['target_groups']) && is_array($validated['target_groups']) && count($validated['target_groups']) > 0;
            $hasExplicitDepts = !empty($validated['department_ids']) && is_array($validated['department_ids']) && !in_array('all', $validated['department_ids']) && count($validated['department_ids']) > 0;

            if (!$hasTargetGroups && !$hasExplicitDepts) {
                $allocations = \App\Models\FacultySubjectAllocation::where('faculty_id', $user->id)->get();
                if ($allocations->isEmpty()) {
                    $validated['department_ids'] = [];
                    $validated['course_ids'] = [];
                    $validated['branch_ids'] = [];
                    $validated['section_ids'] = [];
                    $validated['subject_ids'] = [];
                    $validated['target_groups'] = null;
                } else {
                    $builtGroups = [];
                    foreach ($allocations as $alloc) {
                        $group = [
                            'branch_id' => $alloc->branch_name ?? $alloc->branch_code ?? $alloc->branch_id ?? 'all',
                            'section_id' => (string)($alloc->section_name ?? $alloc->section_id ?? 'all'),
                        ];
                        if ($alloc->branch_id) $group['branch_db_id'] = $alloc->branch_id;
                        if ($alloc->department_id) $group['department_id'] = $alloc->department_id;
                        if ($alloc->course_id) $group['course_id'] = $alloc->course_id;
                        if ($alloc->semester) $group['semester'] = (string)$alloc->semester;
                        $builtGroups[] = $group;
                    }
                    $validated['target_groups'] = $builtGroups;
                }
            }
        }

        if (isset($validated['starts_at']) && !isset($validated['scheduled_at'])) {
            $validated['scheduled_at'] = $validated['starts_at'];
        }

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
            'difficulty' => 'nullable|in:easy,medium,hard',
            'options' => 'required|array|min:2',
            'correct_option' => 'required',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question' => $validated['question'],
            'type' => $validated['type'],
            'difficulty' => $validated['difficulty'] ?? 'easy',
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
            'difficulty' => 'nullable|in:easy,medium,hard',
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
            
            // Extract difficulty level (easy, medium, hard)
            $rawDifficulty = isset($data['difficulty']) ? strtolower(trim($data['difficulty'])) : 'easy';
            $difficulty = in_array($rawDifficulty, ['easy', 'medium', 'hard']) ? $rawDifficulty : 'easy';

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
                'difficulty' => $difficulty,
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
            'ip_address' => 'nullable|string',
            'location' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'submission_type' => 'nullable|in:manual,auto',
            'auto_submit_reason' => 'nullable|string',
        ]);

        $userAnswers = $validated['user_answers'] ?? [];
        $score = 0;

        foreach ($quiz->questions as $question) {
            $userAns = $userAnswers[$question->id] ?? null;

            if ($question->type === 'multiple') {
                $correctAnsArr = [];
                if (is_array($question->correct_option)) {
                    $correctAnsArr = $question->correct_option;
                } else if (is_string($question->correct_option)) {
                    $decoded = json_decode($question->correct_option, true);
                    $correctAnsArr = is_array($decoded) ? $decoded : [$question->correct_option];
                } else {
                    $correctAnsArr = [$question->correct_option];
                }

                $userAnsArr = is_array($userAns) ? $userAns : ($userAns ? [$userAns] : []);
                
                sort($correctAnsArr);
                sort($userAnsArr);

                if ($correctAnsArr === $userAnsArr) {
                    $score++;
                }
            } else {
                if ($userAns !== null && (string)$userAns === (string)$question->correct_option) {
                    $score++;
                }
            }
        }

        $ipAddress = $request->ip_address ?? $request->ip();

        $attempt = QuizAttempt::create([
            'user_id' => $request->user() ? $request->user()->id : 1,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $quiz->questions->count(),
            'user_answers' => $userAnswers,
            'violations_count' => $validated['violations_count'] ?? 0,
            'ip_address' => $ipAddress,
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'submission_type' => $validated['submission_type'] ?? 'manual',
            'auto_submit_reason' => $validated['auto_submit_reason'] ?? null,
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

    /**
     * Get statistics summary for Faculty Dashboard
     */
    public function getFacultyStats(Request $request)
    {
        $user = auth('sanctum')->user() ?? $request->user();

        $quizQuery = Quiz::query();
        if ($user && strtolower($user->role) === 'faculty') {
            $quizQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhere('instructor', $user->name);
                if (!empty($user->full_name)) {
                    $q->orWhere('instructor', $user->full_name);
                }
            });
        }

        $facultyQuizIds = (clone $quizQuery)->pluck('id');

        $totalQuizzes = (clone $quizQuery)->count();
        $activeQuizzes = (clone $quizQuery)->where('status', 'active')->count();
        $completedQuizzes = (clone $quizQuery)->where('status', 'completed')->count();

        $attemptQuery = QuizAttempt::whereIn('quiz_id', $facultyQuizIds);
        $totalSubmissions = (clone $attemptQuery)->count();

        $attempts = $attemptQuery->get();
        $totalScore = 0;
        $totalQuestions = 0;
        foreach ($attempts as $attempt) {
            $totalScore += $attempt->score;
            $totalQuestions += ($attempt->total_questions > 0 ? $attempt->total_questions : 1);
        }

        $avgAccuracy = $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_quizzes' => $totalQuizzes,
                'active_quizzes' => $activeQuizzes,
                'completed_quizzes' => $completedQuizzes,
                'total_submissions' => $totalSubmissions,
                'avg_accuracy' => $avgAccuracy,
            ]
        ]);
    }

    /**
     * Get all student submissions for Faculty Dashboard
     */
    public function getFacultySubmissions(Request $request)
    {
        $user = auth('sanctum')->user() ?? $request->user();

        $query = QuizAttempt::with(['user.branch', 'user.section', 'user.course', 'quiz']);

        if ($user && strtolower($user->role) === 'faculty') {
            $facultyQuizIds = Quiz::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhere('instructor', $user->name);
                if (!empty($user->full_name)) {
                    $q->orWhere('instructor', $user->full_name);
                }
            })->pluck('id');

            $query->whereIn('quiz_id', $facultyQuizIds);
        }

        $submissions = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $submissions,
        ]);
    }
}
