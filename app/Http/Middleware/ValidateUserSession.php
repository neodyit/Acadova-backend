<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserSession
{
    /**
     * Handle an incoming request.
     * Ensure the user token belongs to a user that actually exists in the database.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            // If Sanctum fails to authenticate or user was deleted from DB
            if ($request->user('sanctum') === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired or user no longer exists. Please log in again.',
                ], 401);
            }
        }

        return $next($request);
    }
}
