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

    public function recentNotifications(Request $request)
    {
        $unreadCount = $request->user()->receivedNotifications()->wherePivot('read', false)->count();
        $items = $request->user()->receivedNotifications()
            ->withPivot(['read', 'read_at'])
            ->wherePivot('read', false)
            ->latest('sent_at')
            ->limit(5)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => \Illuminate\Support\Str::limit($n->message, 80),
                'time' => $n->sent_at?->diffForHumans(),
                'target' => $n->target,
            ]);

        return response()->json([
            'unread' => $unreadCount,
            'items' => $items,
        ]);
    }
}
