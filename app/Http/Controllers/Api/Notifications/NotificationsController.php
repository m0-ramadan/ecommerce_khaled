<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    use ApiTrait;

    public function index(Request $request)
    {
        $user = $request->user('api');
        $notifications = NotificationResource::collection($user->unreadNotifications);
        return $this->apiResponse(data: $notifications);
    }

    public function read(Request $request, $notification = null)
    {
        $user = $request->user('api');
        $notifications = $user->unreadNotifications->when($notification, function ($query) use ($notification) {
            return $query->where('id', $notification);
        })->markAsRead();

        return $this->apiResponse(message: true);
    }
}
