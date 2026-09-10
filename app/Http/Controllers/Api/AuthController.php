<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        $token = $user->createToken('acadova_token')->plainTextToken;

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
        $token = $user->createToken('acadova_token')->plainTextToken;

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
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user(),
            ]
        ], 200);
    }

    /**
     * Logout user and revoke tokens.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }
}
