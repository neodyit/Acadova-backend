<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user notifications with unread count
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $notification = Notification::where('user_id', $user->id)->find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllAsRead(Request $request)
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Delete a single notification
     */
    public function destroy(Request $request, $id)
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $notification = Notification::where('user_id', $user->id)->find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }

    /**
     * Save / update FCM device token for current user
     */
    public function saveFcmToken(Request $request)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user() ?? auth('sanctum')->user() ?? auth('web')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user->fcm_token = $validated['fcm_token'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'FCM token saved successfully.',
            'fcm_token' => $user->fcm_token,
        ]);
    }

    /**
     * Clear / remove FCM token when user logs out
     */
    public function removeFcmToken(Request $request)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user() ?? auth('sanctum')->user() ?? auth('web')->user();
        if ($user) {
            $user->update(['fcm_token' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'FCM token removed successfully.',
        ]);
    }

    /**
     * Admin Endpoint: Send notification to a specific user, role, or broadcast to all users
     */
    public function sendNotification(Request $request)
    {
        $admin = $request->user() ?? \Illuminate\Support\Facades\Auth::user() ?? auth('sanctum')->user() ?? auth('web')->user();
        if (!$admin || strtolower($admin->role) !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden. Admin authorization required.'], 403);
        }

        $validated = $request->validate([
            'target' => 'required|string|in:all,student,faculty,specific',
            'user_id' => 'required_if:target,specific|nullable|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
            'type' => 'nullable|string|max:50',
        ]);

        $query = \App\Models\User::query();

        if ($validated['target'] === 'student') {
            $query->where('role', 'student');
        } elseif ($validated['target'] === 'faculty') {
            $query->where('role', 'faculty');
        } elseif ($validated['target'] === 'specific') {
            $query->where('id', $validated['user_id']);
        }

        $recipients = $query->get();
        if ($recipients->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No target users found for this notification.'], 404);
        }

        $sentCount = 0;
        $fcmTokens = [];

        foreach ($recipients as $recipient) {
            // 1. Create in-app database notification
            Notification::create([
                'user_id' => $recipient->id,
                'title' => $validated['title'],
                'message' => $validated['body'],
                'type' => $validated['type'] ?? 'announcement',
                'is_read' => false,
            ]);
            $sentCount++;

            if (!empty($recipient->fcm_token)) {
                $fcmTokens[] = $recipient->fcm_token;
            }
        }

        // 2. Dispatch FCM Push Notifications if FCM tokens are present
        if (!empty($fcmTokens)) {
            \App\Services\FcmService::sendPushMulticast(
                $fcmTokens,
                $validated['title'],
                $validated['body'],
                ['type' => $validated['type'] ?? 'announcement']
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Notification successfully dispatched to {$sentCount} user(s).",
            'recipients_count' => $sentCount,
        ]);
    }
}
