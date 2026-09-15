<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentWebController extends Controller
{
    /**
     * Show Student Login Page
     */
    public function showLogin()
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'student') {
            return redirect()->route('student.dashboard');
        }
        return view('student.auth.login');
    }

    /**
     * Handle Student Login
     */
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => strtolower(trim($credentials['email'])), 'password' => $credentials['password']], true)) {
            $user = Auth::user();
            if (strtolower($user->role) === 'student') {
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'));
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Access denied. This portal is for students only. Admin/Faculty should use their respective portals.'])->withInput();
        }

        return back()->withErrors(['email' => 'Invalid email address or password.'])->withInput();
    }

    /**
     * Process Student Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }

    /**
     * Student Web Dashboard
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user()->load(['branch', 'departmentModel', 'section']);

        // Fetch active quizzes applicable to student
        $activeQuizzes = Quiz::where('status', 'active')
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($quiz) use ($user) {
                if (!empty($quiz->target_academic_type) && !empty($quiz->target_academic_id)) {
                    $type = strtolower($quiz->target_academic_type);
                    $id = (int)$quiz->target_academic_id;

                    if ($type === 'branch' && $user->branch_id !== $id) return false;
                    if ($type === 'department' && $user->department_id !== $id) return false;
                    if ($type === 'course' && $user->course_id !== $id) return false;
                    if ($type === 'section' && $user->section_id !== $id) return false;
                }
                return true;
            });

        // Student's recent attempt history
        $myAttempts = QuizAttempt::with('quiz')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Active campus announcements
        $campaigns = Campaign::where('status', 'active')->latest()->take(3)->get();

        $stats = [
            'quizzes_completed' => $myAttempts->count(),
            'avg_score' => round($myAttempts->avg('score') ?? 0, 1),
            'active_quizzes' => $activeQuizzes->count(),
        ];

        return view('student.dashboard', compact('user', 'activeQuizzes', 'myAttempts', 'campaigns', 'stats'));
    }

    /**
     * Quiz Pre-screen & Instructions
     */
    public function quizPreScreen($id)
    {
        $quiz = Quiz::withCount('questions')->findOrFail($id);
        $user = Auth::user();

        $existingAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();

        return view('student.quiz_prescreen', compact('quiz', 'existingAttempts'));
    }

    /**
     * Timed Exam Player Screen
     */
    public function takeQuiz($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();

        $questions = $quiz->questions->map(function ($q) {
            $options = [];
            if ($q->option_1 !== null) $options[] = $q->option_1;
            if ($q->option_2 !== null) $options[] = $q->option_2;
            if ($q->option_3 !== null) $options[] = $q->option_3;
            if ($q->option_4 !== null) $options[] = $q->option_4;

            return [
                'id' => $q->id,
                'question' => $q->question,
                'type' => $q->type ?? 'single',
                'options' => $options,
                'marks' => $q->marks ?? 1,
            ];
        });

        return view('student.take_quiz', compact('quiz', 'questions'));
    }

    /**
     * Quiz Attempt Score & Performance Breakdown Page
     */
    public function attemptResult($attemptId)
    {
        $attempt = QuizAttempt::with(['quiz.questions'])->where('user_id', Auth::id())->findOrFail($attemptId);
        $quiz = $attempt->quiz;

        return view('student.result', compact('attempt', 'quiz'));
    }
}
