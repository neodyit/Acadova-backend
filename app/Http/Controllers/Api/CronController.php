<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronController extends Controller
{
    /**
     * Cron route endpoint: check and ping users for notifications & reminders
     * Can be invoked via HTTP GET /api/cron/send-notifications or scheduled task
     */
    public function sendNotifications(Request $request)
    {
        $now = Carbon::now();
        $upcomingCutoff = Carbon::now()->addHours(2);
        $endingCutoff = Carbon::now()->addHours(4);

        $students = User::whereRaw("LOWER(role) = 'student'")->get();
        $upcomingCount = 0;
        $pendingCount = 0;

        // 1. Check Upcoming Quizzes (Starting in next 2 hours)
        $upcomingQuizzes = Quiz::whereIn('status', ['upcoming', 'active'])
            ->where(function ($q) use ($now, $upcomingCutoff) {
                $q->whereBetween('starts_at', [$now, $upcomingCutoff])
                  ->orWhereBetween('scheduled_at', [$now, $upcomingCutoff]);
            })
            ->get();

        foreach ($upcomingQuizzes as $quiz) {
            foreach ($students as $student) {
                if (!$quiz->isTargetedToStudent($student)) continue;

                // Check if notification already sent to student for this quiz & type
                $exists = Notification::where('user_id', $student->id)
                    ->where('type', 'quiz_reminder')
                    ->where('action_id', $quiz->id)
                    ->where('created_at', '>=', $now->copy()->subHours(6))
                    ->exists();

                if (!$exists) {
                    $startTimeStr = ($quiz->starts_at ?? $quiz->scheduled_at)
                        ? ($quiz->starts_at ?? $quiz->scheduled_at)->format('h:i A')
                        : 'soon';

                    $title = "Upcoming Quiz: {$quiz->title}";
                    $body = "Your quiz \"{$quiz->title}\" ({$quiz->subject}) starts at {$startTimeStr}. Get ready!";

                    $notif = Notification::create([
                        'user_id' => $student->id,
                        'type' => 'quiz_reminder',
                        'title' => $title,
                        'body' => $body,
                        'action_type' => 'open_quiz',
                        'action_id' => $quiz->id,
                        'metadata' => [
                            'quiz_id' => $quiz->id,
                            'title' => $quiz->title,
                            'subject' => $quiz->subject,
                            'starts_at' => $quiz->starts_at ?? $quiz->scheduled_at,
                        ],
                        'is_read' => false,
                    ]);

                    if (!empty($student->fcm_token)) {
                        FcmService::sendPush($student->fcm_token, $title, $body, [
                            'type' => 'quiz_reminder',
                            'quiz_id' => (string)$quiz->id,
                            'action' => 'open_quiz',
                        ]);
                    }

                    $upcomingCount++;
                }
            }
        }

        // 2. Check Pending Active Quizzes (Ending in next 4 hours where student has NOT submitted)
        $endingQuizzes = Quiz::where('status', 'active')
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [$now, $endingCutoff])
            ->get();

        foreach ($endingQuizzes as $quiz) {
            foreach ($students as $student) {
                if (!$quiz->isTargetedToStudent($student)) continue;

                // Has student already submitted?
                $hasAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('user_id', $student->id)
                    ->exists();

                if ($hasAttempt) continue;

                // Already notified about ending soon?
                $exists = Notification::where('user_id', $student->id)
                    ->where('type', 'pending_reminder')
                    ->where('action_id', $quiz->id)
                    ->where('created_at', '>=', $now->copy()->subHours(6))
                    ->exists();

                if (!$exists) {
                    $endTimeStr = $quiz->ends_at->format('h:i A');
                    $title = "Pending Quiz Reminder: {$quiz->title}";
                    $body = "You haven't submitted \"{$quiz->title}\" yet! Quiz ends at {$endTimeStr}. Tap to attempt now.";

                    $notif = Notification::create([
                        'user_id' => $student->id,
                        'type' => 'pending_reminder',
                        'title' => $title,
                        'body' => $body,
                        'action_type' => 'open_quiz',
                        'action_id' => $quiz->id,
                        'metadata' => [
                            'quiz_id' => $quiz->id,
                            'title' => $quiz->title,
                            'subject' => $quiz->subject,
                            'ends_at' => $quiz->ends_at,
                        ],
                        'is_read' => false,
                    ]);

                    if (!empty($student->fcm_token)) {
                        FcmService::sendPush($student->fcm_token, $title, $body, [
                            'type' => 'pending_reminder',
                            'quiz_id' => (string)$quiz->id,
                            'action' => 'open_quiz',
                        ]);
                    }

                    $pendingCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cron notification check completed successfully.',
            'timestamp' => $now->toDateTimeString(),
            'sent_upcoming_reminders' => $upcomingCount,
            'sent_pending_reminders' => $pendingCount,
        ]);
    }
}
