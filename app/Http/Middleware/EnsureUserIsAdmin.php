<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated access. Please log in as admin.',
                ], 401);
            }
            return redirect()->route('admin.login');
        }

        $user = Auth::user();
        if (strtolower($user->role) !== 'admin') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden. Admin privileges required.',
                ], 403);
            }
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'You do not have administrative access permissions.']);
        }

        return $next($request);
    }
}
