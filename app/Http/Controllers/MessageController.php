<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $all = $request->user()
            ->consultationsAsPatient()
            ->with(['nutritionist', 'messages.sender'])
            ->latest('updated_at')
            ->get();

        $consultations = $all->unique('nutritionist_id');

        $nutritionists = $request->user()
            ->nutritionistsUsers()
            ->orderBy('name')
            ->get();

        return view('messages.index', compact('consultations', 'nutritionists'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'body' => 'required|string|max:1000',
        ]);

        $consultation = Consultation::where('id', $data['consultation_id'])
            ->where('patient_id', $request->user()->id)
            ->firstOrFail();

        $message = $consultation->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
            'sent_at' => now(),
            'read' => false,
        ]);

        // Send notification to nutritionist if not active in this chat
        $recipientId = $consultation->nutritionist_id;
        $isRecipientActive = \Illuminate\Support\Facades\Cache::get("user-active-chat:{$recipientId}") === $consultation->id;
        if (!$isRecipientActive) {
            $notification = \App\Models\Notification::create([
                'sent_by' => auth()->id(),
                'title' => 'Nuevo mensaje de chat',
                'message' => auth()->user()->name . ': ' . \Illuminate\Support\Str::limit($message->body, 100),
                'target' => 'messages',
                'sent_at' => now(),
            ]);
            $notification->notificationUsers()->create([
                'user_id' => $recipientId,
                'read' => false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'sent_at' => $message->sent_at->format('H:i'),
                    'is_mine' => true,
                ],
            ]);
        }

        return back()->with('success', 'Mensaje enviado.');
    }
}
