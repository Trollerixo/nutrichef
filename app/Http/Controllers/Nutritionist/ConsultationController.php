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

        return view('nutritionist.consultations.show', compact('consultation'));
    }

    public function onlineStatus(Consultation $consultation)
    {
        abort_if($consultation->nutritionist_id !== auth()->id(), 403);

        $patient = $consultation->patient;

        return response()->json([
            'online' => $patient?->isOnline() ?? false,
            'last_seen' => $patient?->lastSeen() ?? 'N/A',
        ]);
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
