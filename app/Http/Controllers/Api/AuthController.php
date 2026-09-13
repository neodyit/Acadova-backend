<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSessionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Register a new Student or Faculty user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:student,faculty',
            'password' => 'required|string|min:6|confirmed',
            'roll_number' => 'nullable|required_if:role,student|string|unique:users',
            'faculty_id' => 'nullable|required_if:role,faculty|string',
            'department' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'role' => $request->role,
            'roll_number' => $request->role === 'student' ? $request->roll_number : null,
            'faculty_id' => $request->role === 'faculty' ? $request->faculty_id : null,
            'department' => $request->role === 'faculty' ? $request->department : null,
            'password' => Hash::make($request->password),
        ]);

        $tokenResult = $user->createToken('acadova_token');
        $token = $tokenResult->plainTextToken;

        // Record session log in DB
        UserSessionLog::create([
            'user_id' => $user->id,
            'token_id' => $tokenResult->accessToken->id,
            'login_method' => 'register',
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'device_info' => $request->header('X-Device-Info', $request->header('User-Agent')),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ], 201);
    }

    /**
     * Login user and return Bearer token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt(['email' => strtolower(trim($request->email)), 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials',
            ], 401);
        }

        $user = User::where('email', strtolower(trim($request->email)))->firstOrFail();
        $tokenResult = $user->createToken('acadova_token');
        $token = $tokenResult->plainTextToken;

        // Record login session in DB
        UserSessionLog::create([
            'user_id' => $user->id,
            'token_id' => $tokenResult->accessToken->id,
            'login_method' => 'password',
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'device_info' => $request->header('X-Device-Info', $request->header('User-Agent')),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signed in successfully',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ], 200);
    }

    /**
     * Get authenticated user profile.
     */
    /**
     * Get authenticated user profile with loaded academic structure relationships.
     */
    public function me(Request $request)
    {
        $user = $request->user()->load(['university', 'college', 'departmentModel', 'course', 'branch', 'section', 'subsection']);
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ]
        ], 200);
    }

    /**
     * Update authenticated user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|min:2|max:255',
            'phone' => 'nullable|string|max:30',
            'roll_number' => 'nullable|string|unique:users,roll_number,' . $user->id,
            'faculty_id' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string',
            'university_id' => 'nullable|exists:universities,id',
            'college_id' => 'nullable|exists:colleges,id',
            'department_id' => 'nullable|exists:departments,id',
            'course_id' => 'nullable|exists:courses,id',
            'branch_id' => 'nullable|exists:branches,id',
            'section_id' => 'nullable|exists:sections,id',
            'subsection_id' => 'nullable|exists:subsections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422);
        }

        $fieldsToUpdate = array_filter(
            $request->only([
                'name', 'phone', 'roll_number', 'faculty_id', 'department', 'bio', 'avatar',
                'university_id', 'college_id', 'department_id', 'course_id', 'branch_id', 'section_id', 'subsection_id'
            ]),
            function ($value, $key) use ($request) {
                // Allow null values for fields explicitly sent in request (e.g. academic IDs), but do not overwrite avatar if key wasn't sent
                return $request->has($key);
            },
            ARRAY_FILTER_USE_BOTH
        );

        $user->update($fieldsToUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user->fresh(['university', 'college', 'departmentModel', 'course', 'branch', 'section', 'subsection']),
            ]
        ], 200);
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password does not match',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ], 200);
    }

    /**
     * Handle Google Sign-In / Authentication and sync with MySQL.
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'name' => 'required|string',
            'google_id' => 'nullable|string',
            'avatar' => 'nullable|string',
            'role' => 'nullable|string|in:student,faculty',
            'roll_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $googleId = $request->google_id;
        $name = $request->name;
        $avatar = $request->avatar;
        $role = $request->role ?? 'student';

        // Check if user exists by google_id or email
        $user = null;
        if ($googleId) {
            $user = User::where('google_id', $googleId)->first();
        }
        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        $isNewUser = false;
        if ($user) {
            // Update existing user with details submitted during profile completion
            $updates = [];
            if ($googleId && !$user->google_id) {
                $updates['google_id'] = $googleId;
            }
            if ($avatar && !$user->avatar) {
                $updates['avatar'] = $avatar;
            }
            if ($request->filled('role')) {
                $updates['role'] = $role;
            }
            if ($request->filled('roll_number')) {
                $updates['roll_number'] = $request->roll_number;
            }
            if ($request->filled('faculty_id')) {
                $updates['faculty_id'] = $request->faculty_id;
            }
            if ($request->filled('department')) {
                $updates['department'] = $request->department;
            }
            if ($request->filled('name')) {
                $updates['name'] = $name;
            }
            if (!empty($updates)) {
                $user->update($updates);
                $user->refresh();
            }
        } else {
            $isNewUser = true;
            // Register new user authenticated via Google
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'role' => $role,
                'roll_number' => $request->roll_number,
                'faculty_id' => $request->faculty_id,
                'department' => $request->department,
                'password' => null,
            ]);
        }

        $tokenResult = $user->createToken('acadova_token');
        $token = $tokenResult->plainTextToken;

        // Record Google login session in DB
        UserSessionLog::create([
            'user_id' => $user->id,
            'token_id' => $tokenResult->accessToken->id,
            'login_method' => 'google',
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'device_info' => $request->header('X-Device-Info', $request->header('User-Agent')),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signed in via Google successfully',
            'data' => [
                'token' => $token,
                'is_new' => $isNewUser,
                'user' => $user->fresh(),
            ]
        ], 200);
    }

    /**
     * Logout user and revoke tokens.
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if ($token) {
            // Update session log for this token to logged_out status
            UserSessionLog::where('user_id', $user->id)
                ->where('token_id', $token->id)
                ->where('status', 'active')
                ->update([
                    'logout_at' => now(),
                    'status' => 'logged_out',
                ]);

            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
