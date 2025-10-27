<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{

    public function allNotifications()
    {
        $notifications = auth()->user()->unReadNotifications;
        return response()->json($notifications, 200);
    }
    
    public function markNotification(Request $request)
    {
        auth()->user()->unreadNotifications->when($request->id, function ($q) use ($request) {
            return $q->where('id', $request->id);
        })->markAsRead();

        // return redirect()->route('user.notifications.index');
    }
}