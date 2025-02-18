<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->where('is_read', false)
            ->with('sender')
            ->latest()
            ->paginate(20);

        $user->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifications', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();

        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = Notification::findOrFail($notificationId);

            if ($notification->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $notification->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'type' => 'single',
                'notification_id' => $notificationId
            ]);
        } else {
            $unreadNotifications = $user->notifications()->where('is_read', false);
            $unreadCount = $unreadNotifications->count();

            $unreadNotifications->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'type' => 'bulk',
                'marked_count' => $unreadCount
            ]);
        }
    }

    public function getUnreadCount()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();

            return response()->json([
                'unread_count' => $unreadCount
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
