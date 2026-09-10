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

    /**
     * Create a new User (Student or Faculty) from admin web panel.
     */
    public function storeUser(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:student,faculty,admin',
            'password' => 'required|string|min:6',
            'roll_number' => 'nullable|string',
            'faculty_id' => 'nullable|string',
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'User validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = strtolower($request->role);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'role' => $role,
            'roll_number' => $role === 'student' ? $request->roll_number : null,
            'faculty_id' => $role === 'faculty' ? $request->faculty_id : null,
            'department' => $request->department,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Show single user details with attempt history.
     */
    public function showUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $attempts = QuizAttempt::with('quiz')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'attempts' => $attempts,
            ]
        ]);
    }

    /**
     * Update user details from admin web panel.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|string|in:student,faculty,admin',
            'roll_number' => 'nullable|string',
            'faculty_id' => 'nullable|string',
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = strtolower(trim($request->email));
        if ($request->has('role')) $user->role = strtolower($request->role);
        if ($request->has('roll_number')) $user->roll_number = $request->roll_number;
        if ($request->has('faculty_id')) $user->faculty_id = $request->faculty_id;
        if ($request->has('department')) $user->department = $request->department;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('bio')) $user->bio = $request->bio;
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User details updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Delete user account & attempts.
     */
    public function destroyUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Clean up attempts
        QuizAttempt::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User account deleted successfully'
        ]);
    }
}
