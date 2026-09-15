<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send push notification to a specific FCM token
     */
    public static function sendPush($fcmToken, $title, $body, array $data = [])
    {
        if (empty($fcmToken)) {
            return false;
        }

        $serverKey = config('services.fcm.key') ?? env('FCM_SERVER_KEY');

        if (empty($serverKey)) {
            Log::info("FCM push token ready: {$fcmToken}, Title: {$title}, Body: {$body}");
            return true; // Token stored & notification logged
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
                'data' => array_merge([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'title' => $title,
                    'body' => $body,
                ], $data),
                'priority' => 'high',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("FCM Push Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to multiple FCM tokens
     */
    public static function sendPushMulticast(array $fcmTokens, $title, $body, array $data = [])
    {
        $tokens = array_filter(array_unique($fcmTokens));
        if (empty($tokens)) return false;

        foreach ($tokens as $token) {
            self::sendPush($token, $title, $body, $data);
        }

        return true;
    }
}
