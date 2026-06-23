<?php

namespace App\Http\Controllers\Nutritionist;

use App\Http\Controllers\Controller;
use App\Models\MenuSlot;
use App\Models\Recipe;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $menus = auth()->user()->assignedMenus()->with(['user', 'slots.recipe'])->get();

        return view('nutritionist.plans.index', compact('menus'));
    }

    public function create()
    {
        $patients = auth()->user()->patientsUsers()->orderBy('name')->get();
        $recipes = Recipe::published()->orderBy('title')->get();

        return view('nutritionist.plans.create', compact('patients', 'recipes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,published',
            'slots' => 'required|array|min:1',
            'slots.*.slot_date' => 'required|date',
            'slots.*.meal_type' => 'required|in:desayuno,almuerzo,cena',
            'slots.*.recipe_id' => 'required|exists:recipes,id',
        ]);

        $patient = auth()->user()->patientsUsers()->findOrFail($data['patient_id']);

        $menu = WeeklyMenu::create([
            'user_id' => $patient->id,
            'nutritionist_id' => auth()->id(),
            'title' => $data['title'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
        ]);

        foreach ($data['slots'] as $slot) {
            MenuSlot::create([
                'menu_id' => $menu->id,
                'recipe_id' => $slot['recipe_id'],
                'slot_date' => $slot['slot_date'],
                'meal_type' => $slot['meal_type'],
            ]);
        }

        return redirect()->route('nutritionist.plans.index')->with('success', 'Plan semanal creado correctamente.');
    }
}
