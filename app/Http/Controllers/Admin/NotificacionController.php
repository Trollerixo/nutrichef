<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function index()
    {
        $recientes = Notification::with('sender')
            ->latest('sent_at')
            ->limit(10)
            ->get();

        return view('admin.notificaciones.index', compact('recientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $notification = Notification::create([
            'sent_by' => Auth::id(),
            'title'   => $data['title'],
            'message' => $data['message'],
            'target'  => 'all',
            'sent_at' => now(),
        ]);

        // Crear entradas en notification_users para todos los usuarios activos
        $userIds = User::where('active', true)->pluck('id');
        $pivotRows = $userIds->map(fn ($id) => ['user_id' => $id])->all();
        $notification->notificationUsers()->createMany($pivotRows);

        return back()->with('success', 'Notificación enviada a ' . count($pivotRows) . ' usuario(s).');
    }
}
