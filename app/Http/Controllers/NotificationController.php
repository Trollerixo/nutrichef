<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->receivedNotifications()
            ->with('sender')
            ->withPivot(['read', 'read_at'])
            ->latest('sent_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        NotificationUser::where('notification_id', $notification->id)
            ->where('user_id', $request->user()->id)
            ->where('read', false)
            ->update(['read' => true, 'read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        NotificationUser::where('user_id', $request->user()->id)
            ->where('read', false)
            ->update(['read' => true, 'read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    public function unreadCount(Request $request)
    {
        $count = NotificationUser::where('user_id', $request->user()->id)
            ->where('read', false)
            ->count();

        if ($request->wantsJson()) {
            return response()->json(['count' => $count]);
        }

        return $count;
    }
}
