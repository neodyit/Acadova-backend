<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Get aggregate statistics for web admin dashboard.
     */
    public function stats()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalFaculty = User::where('role', 'faculty')->count();
        $totalQuizzes = Quiz::count();
        $activeQuizzes = Quiz::where('status', 'active')->count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalAttempts = QuizAttempt::count();
        
        $avgScore = QuizAttempt::avg('score') ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_students' => $totalStudents,
                'total_faculty' => $totalFaculty,
                'total_quizzes' => $totalQuizzes,
                'active_quizzes' => $activeQuizzes,
                'active_campaigns' => $activeCampaigns,
                'total_attempts' => $totalAttempts,
                'avg_score' => round($avgScore, 1),
            ]
        ]);
    }

    /**
     * Get list of all registered users for admin web panel.
     */
    public function users(Request $request)
    {
        $role = $request->query('role');
        $query = User::query()->orderBy('created_at', 'desc');

        if ($role && in_array($role, ['student', 'faculty'])) {
            $query->where('role', $role);
        }

        $users = $query->get()->map(function ($user) {
            $attemptsCount = QuizAttempt::where('user_id', $user->id)->count();
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => strtoupper($user->role),
                'roll_number' => $user->roll_number,
                'faculty_id' => $user->faculty_id,
                'department' => $user->department,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'attempts_count' => $attemptsCount,
                'created_at' => $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Get recent quiz attempts log for admin dashboard.
     */
    public function attempts()
    {
        $attempts = QuizAttempt::with(['user', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($att) {
                return [
                    'id' => $att->id,
                    'student_name' => $att->user ? $att->user->name : 'Unknown Student',
                    'student_email' => $att->user ? $att->user->email : 'N/A',
                    'quiz_title' => $att->quiz ? $att->quiz->title : 'Quiz #' . $att->quiz_id,
                    'score' => $att->score,
                    'total_questions' => $att->total_questions,
                    'created_at' => $att->created_at ? $att->created_at->format('M d, Y H:i') : 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $attempts,
        ]);
    }
}
