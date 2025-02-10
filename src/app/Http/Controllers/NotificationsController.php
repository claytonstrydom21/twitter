<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->with('sender')
            ->latest()
            ->paginate(20);

        return view('notifications', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');

        $notification = Notification::findOrFail($notificationId);

        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
