<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    // Endpoint to fetch notifications
    public function index()
    {
        // جلب كل الإشعارات للمستخدم الحالي
        $notifications = Notification::where('user_id', Auth::id())->get();

        // تنسيق التاريخ بحيث يظهر بدون وقت
        $notifications->each(function ($notification) {
            $notification->date = Carbon::parse($notification->date)->format('Y-m-d');
        });

        // الرد بالصيغة المطلوبة
        return response()->json([
            'message' => 'Your custom message', // هنا تقدر تعدل الرسالة حسب ما تحتاج
            'notifications' => $notifications
        ], 200);
    }

    // Endpoint to create a new notification
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'date' => 'nullable|date',
        ]);

        $notification = Notification::create([
            'body' => $request->body,
            'date' => $request->date ?? now(),
            'user_id' => Auth::id(), // Associate the notification with the authenticated user
        ]);

        return response()->json($notification, 201);
    }

    // Endpoint to delete a single notification
    public function destroy($id)
    {
        // Find the notification by ID and ensure it belongs to the authenticated user
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found or you do not have permission to delete it.'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully.'
        ], 200);
    }

    // Endpoint to delete all notifications for the authenticated user
    public function destroyAll()
    {
        // Delete all notifications for the authenticated user
        Notification::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'All notifications deleted successfully.'
        ], 200);
    }
}