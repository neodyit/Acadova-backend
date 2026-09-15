<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AppSetting;

class AdminController extends Controller
{
    /**
     * Get current AdMob Feature Flag Settings for Admin Panel.
     */
    public function getAdSettings()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'ads_enabled' => AppSetting::get('ads_enabled', 'true') === 'true',
                'ads_target_audience' => AppSetting::get('ads_target_audience', 'all'),
            ]
        ]);
    }

    /**
     * Update AdMob Feature Flag Settings from Admin Panel.
     */
    public function updateAdSettings(Request $request)
    {
        $request->validate([
            'ads_enabled' => 'required|boolean',
            'ads_target_audience' => 'required|string|in:all,selected_users,none',
        ]);

        AppSetting::set('ads_enabled', $request->ads_enabled ? 'true' : 'false');
        AppSetting::set('ads_target_audience', $request->ads_target_audience);

        return response()->json([
            'success' => true,
            'message' => 'AdMob feature flag settings updated successfully!',
            'data' => [
                'ads_enabled' => $request->ads_enabled,
                'ads_target_audience' => $request->ads_target_audience,
            ]
        ]);
    }

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
                'show_ads' => (bool)($user->show_ads ?? true),
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
        $attempts = QuizAttempt::with(['user.branch', 'user.section', 'quiz'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($att) {
                $u = $att->user;
                $q = $att->quiz;

                $branch = 'N/A';
                if ($u) {
                    if ($u->branch) {
                        $branch = $u->branch->code ?? $u->branch->name ?? 'N/A';
                    } else if (!empty($u->department)) {
                        $branch = $u->department;
                    }
                }

                $sec = 'N/A';
                if ($u && $u->section) {
                    $sec = $u->section->name ?? 'N/A';
                }

                return [
                    'id' => $att->id,
                    'user_id' => $att->user_id,
                    'student_name' => $u ? $u->name : 'Unknown Student',
                    'student_email' => $u ? $u->email : 'N/A',
                    'roll_number' => $u ? ($u->roll_number ?? 'N/A') : 'N/A',
                    'branch' => $branch,
                    'section' => $sec,
                    'semester' => $u ? ($u->semester ?? 'N/A') : 'N/A',
                    'quiz_id' => $att->quiz_id,
                    'quiz_title' => $q ? $q->title : 'Quiz #' . $att->quiz_id,
                    'subject' => $q ? ($q->subject ?? 'General') : 'General',
                    'score' => $att->score,
                    'total_questions' => $att->total_questions,
                    'percentage' => $att->total_questions > 0 ? round(($att->score / $att->total_questions) * 100) : 0,
                    'violations_count' => $att->violations_count ?? 0,
                    'ip_address' => $att->ip_address ?? 'N/A',
                    'location' => $att->location ?? 'N/A',
                    'latitude' => $att->latitude,
                    'longitude' => $att->longitude,
                    'submission_type' => $att->submission_type ?? 'manual',
                    'auto_submit_reason' => $att->auto_submit_reason ?? '-',
                    'created_at' => $att->created_at ? $att->created_at->format('M d, Y H:i') : 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $attempts,
        ]);
    }

    /**
     * Delete/reset a student quiz attempt to allow reattempt.
     */
    public function deleteAttempt($id)
    {
        $attempt = QuizAttempt::find($id);
        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz attempt record not found.',
            ], 404);
        }

        $attempt->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz attempt deleted successfully. Student can now reattempt this quiz.',
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
            'show_ads' => 'sometimes|boolean',
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
        if ($request->has('show_ads')) $user->show_ads = (bool)$request->show_ads;
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

        // Clean up attempts & session logs
        QuizAttempt::where('user_id', $user->id)->delete();
        \App\Models\UserSessionLog::where('user_id', $user->id)->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User account deleted successfully'
        ]);
    }

    /**
     * Get user session logs for admin panel.
     */
    public function sessions(Request $request)
    {
        $query = \App\Models\UserSessionLog::with('user')->orderBy('created_at', 'desc');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $sessions = $query->limit(100)->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'user_id' => $log->user_id,
                'user_name' => $log->user ? $log->user->name : 'Unknown',
                'user_email' => $log->user ? $log->user->email : 'N/A',
                'user_role' => $log->user ? strtoupper($log->user->role) : 'N/A',
                'login_method' => $log->login_method,
                'login_at' => $log->login_at ? $log->login_at->format('M d, Y H:i:s') : null,
                'logout_at' => $log->logout_at ? $log->logout_at->format('M d, Y H:i:s') : null,
                'ip_address' => $log->ip_address ?? 'N/A',
                'user_agent' => $log->user_agent ?? 'N/A',
                'device_info' => $log->device_info ?? 'N/A',
                'status' => $log->status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }

    /**
     * Get allocations for all faculty or single faculty
     */
    public function getFacultyAllocations(Request $request, $facultyId = null)
    {
        $id = $facultyId ?: $request->query('faculty_id');
        $query = \App\Models\FacultySubjectAllocation::with(['faculty', 'branchModel', 'subjectModel', 'sectionModel']);

        if ($id) {
            $query->where('faculty_id', $id);
        }

        $allocations = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $allocations,
        ]);
    }

    /**
     * Store a new subject & section allocation for a faculty member
     */
    public function storeFacultyAllocation(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:users,id',
            'branch_id' => 'nullable|exists:branches,id',
            'branch_name' => 'nullable|string|max:255',
            'subject_id' => 'nullable|exists:subjects,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_name' => 'nullable|string|max:255',
            'section_name' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:255',
        ]);

        $branchName = $validated['branch_name'] ?? null;
        if (!empty($validated['branch_id'])) {
            $br = \App\Models\Branch::find($validated['branch_id']);
            if ($br) $branchName = $br->name;
        }

        $subjectName = $validated['subject_name'] ?? null;
        if (!empty($validated['subject_id'])) {
            $sub = \App\Models\Subject::find($validated['subject_id']);
            if ($sub) $subjectName = $sub->name;
        }

        $sectionName = $validated['section_name'] ?? null;
        if (!empty($validated['section_id'])) {
            $sec = \App\Models\Section::find($validated['section_id']);
            if ($sec) $sectionName = $sec->name;
        }

        $allocation = \App\Models\FacultySubjectAllocation::create([
            'faculty_id' => $validated['faculty_id'],
            'branch_id' => $validated['branch_id'] ?? null,
            'branch_name' => $branchName ?? 'All Branches',
            'subject_id' => $validated['subject_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'subject_name' => $subjectName ?? 'General',
            'section_name' => $sectionName ?? 'All Sections',
            'semester' => $validated['semester'] ?? 'Semester 1',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Faculty allocation created successfully.',
            'data' => $allocation->load(['faculty', 'branchModel', 'subjectModel', 'sectionModel']),
        ], 201);
    }

    /**
     * Delete a faculty allocation
     */
    public function deleteFacultyAllocation($id)
    {
        $allocation = \App\Models\FacultySubjectAllocation::find($id);
        if (!$allocation) {
            return response()->json(['success' => false, 'message' => 'Allocation not found.'], 404);
        }

        $allocation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Faculty allocation removed successfully.',
        ]);
    }

    /**
     * Get allocations for logged in faculty user
     */
    public function getMyFacultyAllocations(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $allocations = \App\Models\FacultySubjectAllocation::where('faculty_id', $user->id)
            ->with(['branchModel', 'subjectModel', 'sectionModel'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $allocations,
        ]);
    }
}
