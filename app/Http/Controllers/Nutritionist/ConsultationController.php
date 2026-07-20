<?php

namespace App\Http\Controllers\Nutritionist;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $all = auth()->user()->consultationsAsNutritionist()
            ->with(['patient', 'messages.sender'])
            ->latest('updated_at')
            ->get();

        $consultations = $all->unique('patient_id');

        return view('nutritionist.consultations.index', compact('consultations'));
    }

    public function show(Consultation $consultation)
    {
        abort_if($consultation->nutritionist_id !== auth()->id(), 403);

        $consultation->load(['patient', 'messages.sender']);

        \Illuminate\Support\Facades\Cache::put("user-active-chat:" . auth()->id(), $consultation->id, now()->addSeconds(45));

        return view('nutritionist.consultations.show', compact('consultation'));
    }

    public function onlineStatus(Consultation $consultation)
    {
        abort_if($consultation->nutritionist_id !== auth()->id(), 403);

        $patient = $consultation->patient;

        \Illuminate\Support\Facades\Cache::put("user-active-chat:" . auth()->id(), $consultation->id, now()->addSeconds(45));

        $messages = $consultation->messages()->with('sender')->get()->map(fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'sender_id' => $m->sender_id,
            'sent_at' => $m->sent_at?->format('H:i'),
            'is_mine' => $m->sender_id === auth()->id(),
        ]);

        return response()->json([
            'online' => $patient?->isOnline() ?? false,
            'last_seen' => $patient?->lastSeen() ?? 'N/A',
            'status' => $consultation->status,
            'messages' => $messages,
        ]);
    }

    public function close(Consultation $consultation)
    {
        abort_if($consultation->nutritionist_id !== auth()->id(), 403);

        $consultation->update(['status' => 'closed']);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'closed']);
        }

        return back()->with('success', 'Consulta finalizada correctamente.');
    }

    public function reply(Request $request, Consultation $consultation)
    {
        abort_if($consultation->nutritionist_id !== auth()->id(), 403);

        $data = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = $consultation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $data['body'],
            'sent_at' => now(),
            'read' => false,
        ]);

        // Send notification to patient if not active in this chat
        $recipientId = $consultation->patient_id;
        $isRecipientActive = \Illuminate\Support\Facades\Cache::get("user-active-chat:{$recipientId}") === $consultation->id;
        if (!$isRecipientActive) {
            $notification = \App\Models\Notification::create([
                'sent_by' => auth()->id(),
                'title' => 'Nuevo mensaje de tu nutricionista',
                'message' => auth()->user()->name . ': ' . \Illuminate\Support\Str::limit($message->body, 100),
                'target' => 'messages',
                'sent_at' => now(),
            ]);
            $notification->notificationUsers()->create([
                'user_id' => $recipientId,
                'read' => false,
            ]);
        }

        if ($consultation->status === 'open') {
            $consultation->update(['status' => 'in_progress']);
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

        return back()->with('success', 'Respuesta enviada correctamente.');
    }
}
