<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Notification as NotificationModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\NotificationResource;
use App\Traits\TranslatableTrait;

class NotificationController extends Controller
{
    use TranslatableTrait;

    public function sendNotification(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string',
                'title' => 'required|string',
                'body' => 'required|string',
                'icon' => 'required|url',
            ]);

            $deviceToken = $request->input('device_token');

            $notification = Notification::create()
                ->withTitle($request->input('title'))
                ->withBody($request->input('body'));

            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification)
                ->withData([
                    'icon' => $request->input('icon'),
                ]);

            $firebase = (new Factory)
                ->withServiceAccount(env('FIREBASE_CREDENTIALS'))
                ->createMessaging();

            $firebase->send($message);

            if (Auth::guard('sanctum')->check()) {
                $user = Auth::guard('sanctum')->user();
                try {
                    NotificationModel::create([
                        'id' => Str::uuid(),
                        'type' => 'App\Notifications\FirebaseNotification',
                        'notifiable_type' => get_class($user),
                        'notifiable_id' => $user->id,
                        'data' => [
                            'title' => $request->input('title'),
                            'body' => $request->input('body'),
                            'icon' => $request->input('icon'),
                        ],
                        'read_at' => null,
                    ]);
                    Log::info('Notification saved successfully', ['user_id' => $user->id]);
                } catch (\Illuminate\Database\QueryException $e) {
                    Log::error('Database error while saving notification', ['error' => $e->getMessage()]);
                    return response()->json([
                        'error' => $this->translate('failed_to_save_notification'),
                        'details' => $e->getMessage(),
                    ], 500);
                }
            } else {
                Log::info('User not authenticated, notification not saved');
            }

            return response()->json([
                'message' => $this->translate('notification_sent') . (Auth::guard('sanctum')->check() ? ' ' . $this->translate('notification_saved') : ''),
            ]);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            return response()->json([
                'error' => $this->translate('device_token_invalid'),
                'details' => $e->getMessage(),
            ], 404);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            return response()->json([
                'error' => $this->translate('failed_to_send_notification'),
                'details' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $this->translate('unexpected_error'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNotifications()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => $this->translate('unauthorized')], 401);
        }

        $notifications = NotificationModel::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'message' => $this->translate('notification_retrieved'),
            'notification_count' => $this->translate('notification_count', ['count' => $notifications->count()]),
            'notifications' => NotificationResource::collection($notifications)
        ]);
    }

    public function deleteNotification($id)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => $this->translate('unauthorized')], 401);
        }

        $notification = NotificationModel::where('id', $id)
            ->where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['error' => $this->translate('notification_not_found')], 404);
        }

        $notification->delete();

        return response()->json(['message' => $this->translate('notification_deleted')]);
    }

    public function deleteAllNotifications()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => $this->translate('unauthorized')], 401);
        }

        NotificationModel::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->delete();

        return response()->json(['message' => $this->translate('all_notifications_deleted')]);
    }
}