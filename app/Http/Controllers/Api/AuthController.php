<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSessionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\AppSetting;

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
     * Get public application settings (ad configurations, feature flags)
     */
    public function getAppSettings()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'ads_enabled' => \App\Models\AppSetting::get('ads_enabled', 'true') === 'true',
                'ads_target_audience' => \App\Models\AppSetting::get('ads_target_audience', 'all'),
                'google_auth_android' => \App\Models\AppSetting::get('google_auth_android', 'true') === 'true',
                'google_auth_windows' => \App\Models\AppSetting::get('google_auth_windows', 'true') === 'true',
                'latest_app_version' => \App\Models\AppSetting::get('latest_app_version', '0.0.6'),
                'min_required_version' => \App\Models\AppSetting::get('min_required_version', '0.0.6'),
                'update_url' => \App\Models\AppSetting::get('update_url', 'https://acadova.neodyit.com/download'),
                'force_update' => \App\Models\AppSetting::get('force_update', 'false') === 'true',
                'release_notes' => \App\Models\AppSetting::get('release_notes', 'Performance improvements & bug fixes.'),
            ]
        ])->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    /**
     * Get authenticated user profile with loaded academic structure relationships.
     */
    public function me(Request $request)
    {
        $user = $request->user()->load(['university', 'college', 'departmentModel', 'course', 'branch', 'section', 'subsection']);
        $adConfig = [
            'ads_enabled' => \App\Models\AppSetting::get('ads_enabled', 'true') === 'true',
            'ads_target_audience' => \App\Models\AppSetting::get('ads_target_audience', 'all'),
            'user_show_ads' => (bool)($user->show_ads ?? true),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'ad_config' => $adConfig,
            ]
        ], 200)
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    /**
     * Update authenticated user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|min:2|max:255',
            'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
            'roll_number' => 'nullable|string|unique:users,roll_number,' . $user->id,
            'faculty_id' => 'nullable|string|unique:users,faculty_id,' . $user->id,
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
            'semester' => 'nullable|string|max:255',
        ], [
            'phone.unique' => 'This phone number is already linked with another account.',
            'roll_number.unique' => 'This roll number is already linked with another account.',
            'faculty_id.unique' => 'This Faculty ID is already linked with another account.',
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
                'university_id', 'college_id', 'department_id', 'course_id', 'branch_id', 'section_id', 'subsection_id', 'semester'
            ]),
            function ($value, $key) use ($request) {
                // Do not overwrite avatar with null if key wasn't sent or was null
                if ($key === 'avatar' && $value === null) {
                    return false;
                }
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
            'new_password' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
                'confirmed',
            ],
        ], [
            'new_password.min' => 'Password must be at least 6 characters long.',
            'new_password.regex' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?? 'Validation errors occurred',
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
        if ($user) {
            $user->update(['fcm_token' => null]);
        }
        $token = $user ? $user->currentAccessToken() : null;

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

    /**
     * Send Password Reset Link with Rate Limiting (Hostinger 100 emails / 2h max)
     */
    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));

        // 1. Explicit DB User Existence Check
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found registered with this email address. Please check your email and try again.',
            ], 404);
        }

        // 2. Cooldown Rate Limiting (120 seconds / 2 minutes per email)
        $cacheKey = 'password_reset_cooldown_' . md5($email);
        if (Cache::has($cacheKey)) {
            $secondsRemaining = Cache::get($cacheKey) - time();
            if ($secondsRemaining > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Please wait $secondsRemaining seconds before requesting another password reset link.",
                    'statusCode' => 429,
                    'retry_after' => $secondsRemaining,
                    'cooldown_seconds' => $secondsRemaining,
                ], 429);
            }
        }

        // 3. Generate secure random reset token (64 hex characters)
        $token = Str::random(64);

        // Store in DB password_reset_tokens
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Construct Universal Link & Web Reset Link
        $baseUrl = config('app.url', 'https://acadova.neodyit.com');
        $resetUrl = "$baseUrl/reset-password?token=$token&email=" . urlencode($email);
        $deepLink = "acadova://reset-password?token=$token&email=" . urlencode($email);

        // Force Hostinger / dynamic .env SMTP configuration explicitly
        config([
            'mail.default' => env('MAIL_MAILER', 'smtp'),
            'mail.mailers.smtp.host' => env('MAIL_HOST', 'smtp.hostinger.com'),
            'mail.mailers.smtp.port' => (int) env('MAIL_PORT', 465),
            'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'mail.mailers.smtp.username' => env('MAIL_USERNAME', 'acadova@neodyit.com'),
            'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS', 'acadova@neodyit.com'),
            'mail.from.name' => env('MAIL_FROM_NAME', 'Acadova'),
        ]);

        // 4. Send Email via Native Symfony SMTP Transport (100% reliable Hostinger SSL/TLS dispatch)
        try {
            $host = env('MAIL_HOST', 'smtp.hostinger.com');
            $port = (int) env('MAIL_PORT', 465);
            $username = env('MAIL_USERNAME', 'acadova@neodyit.com');
            $password = env('MAIL_PASSWORD', '');
            $fromAddress = env('MAIL_FROM_ADDRESS', 'acadova@neodyit.com');
            $fromName = env('MAIL_FROM_NAME', 'Acadova');

            $dsn = "smtps://{$username}:" . urlencode($password) . "@{$host}:{$port}";
            $transport = \Symfony\Component\Mailer\Transport::fromDsn($dsn);
            $mailer = new \Symfony\Component\Mailer\Mailer($transport);

            $htmlContent = view('emails.password_reset', [
                'userName' => $user->name,
                'resetUrl' => $resetUrl,
                'deepLink' => $deepLink,
            ])->render();

            $emailMessage = (new \Symfony\Component\Mime\Email())
                ->from(new \Symfony\Component\Mime\Address($fromAddress, $fromName))
                ->to($email)
                ->subject('Reset Your Acadova Password')
                ->html($htmlContent);

            $mailer->send($emailMessage);

            // Store 120 seconds (2 minutes) cooldown in Cache
            Cache::put($cacheKey, time() + 120, 120);

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email address.',
                'cooldown_seconds' => 120,
            ]);
        } catch (\Throwable $e) {
            \Log::error('SMTP Password Reset Email Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Email dispatch failed: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/',
                'confirmed',
            ],
        ], [
            'password.min' => 'Password must be at least 6 characters long.',
            'password.regex' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first() ?? 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $token = $request->token;

        $record = \DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired password reset token.',
            ], 400);
        }

        // Token lifetime: 60 minutes
        if (now()->diffInMinutes($record->created_at) > 60) {
            \DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Password reset token has expired. Please request a new link.',
            ], 400);
        }

        if (!Hash::check($token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password reset token.',
            ], 400);
        }

        // Update User Password
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User account not found.',
            ], 444);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete used token
        \DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Revoke all existing tokens for security after password change
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully! You can now log in with your new password.',
        ]);
    }
}
