<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminWebController extends Controller
{
    /**
     * Show Admin Login Page
     */
    public function showLogin()
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login Authentication
     */
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => strtolower(trim($credentials['email'])), 'password' => $credentials['password']], $remember)) {
            $user = Auth::user();
            if (strtolower($user->role) === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Access denied. You do not have administrator permissions.'])->withInput();
        }

        return back()->withErrors(['email' => 'Invalid email address or password.'])->withInput();
    }

    /**
     * Process Admin Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Dashboard Overview Page
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_faculty' => User::where('role', 'faculty')->count(),
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::where('status', 'active')->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_attempts' => QuizAttempt::count(),
            'avg_score' => round(QuizAttempt::avg('score') ?? 0, 1),
        ];

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAttempts'));
    }

    /**
     * Quizzes Management Page
     */
    public function quizzes()
    {
        $quizzes = Quiz::withCount('questions')->latest()->get();
        return view('admin.quizzes', compact('quizzes'));
    }

    /**
     * Campaigns & Notices Page
     */
    public function campaigns()
    {
        $campaigns = Campaign::latest()->get();
        return view('admin.campaigns', compact('campaigns'));
    }

    /**
     * Users Directory Page
     */
    public function users(Request $request)
    {
        $query = User::query()->orderBy('created_at', 'desc');
        if ($request->filled('role') && in_array($request->role, ['student', 'faculty', 'admin'])) {
            $query->where('role', $request->role);
        }
        $users = $query->get()->map(function ($u) {
            $u->attempts_count = QuizAttempt::where('user_id', $u->id)->count();
            return $u;
        });

        return view('admin.users', compact('users'));
    }

    /**
     * Media Library Page
     */
    public function media()
    {
        return view('admin.media');
    }

    /**
     * System Settings Page
     */
    public function settings()
    {
        return view('admin.settings');
    }
}
