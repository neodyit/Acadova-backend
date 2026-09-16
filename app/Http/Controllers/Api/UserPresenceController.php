<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEngagementLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserPresenceController extends Controller
{
    /**
     * Update user heartbeat, last active timestamp, and app version telemetry.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_version' => 'nullable|string|max:50',
            'device_platform' => 'nullable|string|max:30',
            'device_model' => 'nullable|string|max:100',
            'os_version' => 'nullable|string|max:50',
        ]);

        $user = $request->user();

        if ($user) {
            $updateData = [
                'last_active_at' => now(),
            ];

            if (!empty($validated['app_version'])) {
                $updateData['current_app_version'] = $validated['app_version'];
            }
            if (!empty($validated['device_platform'])) {
                $updateData['device_platform'] = strtolower($validated['device_platform']);
            }
            if (!empty($validated['device_model'])) {
                $updateData['device_model'] = $validated['device_model'];
            }
            if (!empty($validated['os_version'])) {
                $updateData['os_version'] = $validated['os_version'];
            }

            // Perform single direct update for ultra low overhead
            User::where('id', $user->id)->update($updateData);
        }

        return response()->json([
            'status' => true,
            'message' => 'Heartbeat received',
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * Batch log screen views and engagement events from the mobile client.
     */
    public function logEngagement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'nullable|string|max:64',
            'events' => 'required|array',
            'events.*.screen_name' => 'required|string|max:100',
            'events.*.action' => 'nullable|string|max:100',
            'events.*.duration_seconds' => 'nullable|integer|min:0',
            'events.*.metadata' => 'nullable|array',
            'events.*.timestamp' => 'nullable|string',
        ]);

        $user = $request->user();
        $sessionId = $validated['session_id'] ?? null;
        $events = $validated['events'];

        $records = [];
        $now = now();

        foreach ($events as $event) {
            $records[] = [
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'screen_name' => substr($event['screen_name'], 0, 100),
                'action' => isset($event['action']) ? substr($event['action'], 0, 100) : null,
                'duration_seconds' => $event['duration_seconds'] ?? 0,
                'metadata' => isset($event['metadata']) ? json_encode($event['metadata']) : null,
                'created_at' => isset($event['timestamp']) ? date('Y-m-d H:i:s', strtotime($event['timestamp'])) : $now,
            ];
        }

        if (!empty($records)) {
            UserEngagementLog::insert($records);
        }

        // Also refresh last active timestamp
        User::where('id', $user->id)->update(['last_active_at' => $now]);

        return response()->json([
            'status' => true,
            'message' => 'Engagement events logged successfully',
            'count' => count($records),
        ]);
    }

    /**
     * Admin/Faculty API to retrieve online users, version metrics, and engagement analytics.
     */
    public function presenceStats(Request $request): JsonResponse
    {
        // Check if user is authorized (role: admin/faculty)
        $user = $request->user();
        if ($user && $user->role === 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $onlineCount = User::online(3)->count();
        $activeTodayCount = User::where('last_active_at', '>=', now()->startOfDay())->count();

        // Version distribution
        $versionBreakdown = User::select('current_app_version', DB::raw('count(*) as count'))
            ->whereNotNull('current_app_version')
            ->groupBy('current_app_version')
            ->orderByDesc('count')
            ->get();

        // Platform breakdown
        $platformBreakdown = User::select('device_platform', DB::raw('count(*) as count'))
            ->whereNotNull('device_platform')
            ->groupBy('device_platform')
            ->orderByDesc('count')
            ->get();

        // Recent online users list
        $onlineUsers = User::online(5)
            ->select('id', 'name', 'email', 'role', 'roll_number', 'current_app_version', 'device_platform', 'device_model', 'last_active_at')
            ->orderByDesc('last_active_at')
            ->limit(50)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'online_users_count' => $onlineCount,
                'active_today_count' => $activeTodayCount,
                'version_breakdown' => $versionBreakdown,
                'platform_breakdown' => $platformBreakdown,
                'recent_online_users' => $onlineUsers,
            ],
        ]);
    }
}
