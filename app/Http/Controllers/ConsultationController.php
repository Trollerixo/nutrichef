<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create()
    {
        $nutritionists = auth()->user()
            ->nutritionistsUsers()
            ->active()
            ->orderBy('name')
            ->get();

        return view('consultations.create', compact('nutritionists'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nutritionist_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
        ]);

        $nutritionist = auth()->user()
            ->nutritionistsUsers()
            ->findOrFail($data['nutritionist_id']);

        $consultation = Consultation::where('patient_id', auth()->id())
            ->where('nutritionist_id', $nutritionist->id)
            ->latest('updated_at')
            ->first();

        if ($consultation) {
            $consultation->messages()->create([
                'sender_id' => auth()->id(),
                'body' => $data['body'],
                'sent_at' => now(),
                'read' => false,
            ]);

            return redirect()->route('consultations.show', $consultation)
                ->with('success', 'Mensaje enviado.');
        }

        $consultation = Consultation::create([
            'patient_id' => auth()->id(),
            'nutritionist_id' => $nutritionist->id,
            'subject' => $data['subject'],
            'status' => 'open',
        ]);

        $consultation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $data['body'],
            'sent_at' => now(),
            'read' => false,
        ]);

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consulta creada y mensaje enviado.');
    }

    public function show(Consultation $consultation)
    {
        abort_if($consultation->patient_id !== auth()->id(), 403);

        $consultation->load(['nutritionist', 'messages.sender']);

        \Illuminate\Support\Facades\Cache::put("user-active-chat:" . auth()->id(), $consultation->id, now()->addSeconds(45));

        return view('consultations.show', compact('consultation'));
    }

    public function onlineStatus(Consultation $consultation)
    {
        abort_if($consultation->patient_id !== auth()->id(), 403);

        $nutritionist = $consultation->nutritionist;

        \Illuminate\Support\Facades\Cache::put("user-active-chat:" . auth()->id(), $consultation->id, now()->addSeconds(45));

        return response()->json([
            'online' => $nutritionist?->isOnline() ?? false,
            'last_seen' => $nutritionist?->lastSeen() ?? 'N/A',
        ]);
    }
}
