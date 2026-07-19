<?php

namespace App\Http\Controllers\Nutritionist;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeRecommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = auth()->user()->sentRecommendations()
            ->with(['patient', 'recipe'])
            ->latest('sent_at')
            ->get();

        return view('nutritionist.recommendations.index', compact('recommendations'));
    }

    public function create()
    {
        $patients = auth()->user()->patientsUsers()->orderBy('name')->get();
        $recipes = Recipe::published()->orderBy('title')->get();

        return view('nutritionist.recommendations.create', compact('patients', 'recipes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|integer',
            'recipe_id' => 'required|exists:recipes,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $patient = auth()->user()->patientsUsers()->findOrFail($data['patient_id']);

        $rec = RecipeRecommendation::create([
            'nutritionist_id' => auth()->id(),
            'patient_id' => $patient->id,
            'recipe_id' => $data['recipe_id'],
            'message' => $data['message'] ?? null,
            'sent_at' => now(),
        ]);

        // Send notification to the patient
        $recipe = \App\Models\Recipe::find($data['recipe_id']);
        $notification = \App\Models\Notification::create([
            'sent_by' => auth()->id(),
            'title' => 'Nueva recomendación',
            'message' => 'Tu nutricionista ' . auth()->user()->name . ' te recomendó la receta: ' . ($recipe?->title ?? 'Receta'),
            'target' => 'recommendations',
            'sent_at' => now(),
        ]);
        $notification->notificationUsers()->create([
            'user_id' => $patient->id,
            'read' => false,
        ]);

        return redirect()->route('nutritionist.recommendations.index')->with('success', 'Recomendación enviada correctamente.');
    }
}
