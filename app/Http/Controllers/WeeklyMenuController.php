<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeeklyMenuRequest;
use App\Models\Recipe;
use App\Models\MenuSlot;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WeeklyMenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = $request->user()->weeklyMenus()->with(['slots.recipe'])->orderByDesc('created_at')->get();

        return view('weekly-menus.index', compact('menus'));
    }

    public function create(Request $request)
    {
        $recipes = Recipe::published()->orderBy('title')->get();

        return view('weekly-menus.create', compact('recipes'));
    }

    public function store(WeeklyMenuRequest $request)
    {
        $data = $request->validated();

        $today = now()->startOfDay();
        $maxDate = (clone $today)->addDays(6);

        foreach ($data['slots'] as $i => $slot) {
            $date = $slot['slot_date'];
            if ($date < $today->format('Y-m-d') || $date > $maxDate->format('Y-m-d')) {
                throw ValidationException::withMessages([
                    "slots.{$i}.slot_date" => "La fecha debe estar entre hoy y los próximos 7 días.",
                ]);
            }
        }

        // Deactivate all other menus for this user
        $request->user()->weeklyMenus()->update(['active' => false]);

        $menu = WeeklyMenu::create([
            'user_id' => $request->user()->id,
            'nutritionist_id' => null,
            'title' => $data['title'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
            'active' => true,
        ]);

        $this->syncSlots($menu, $data['slots']);

        return redirect()->route('weekly-menus.index')->with('success', 'Menú semanal creado y establecido como activo.');
    }

    public function edit(WeeklyMenu $menu)
    {
        abort_if($menu->user_id !== auth()->id(), 403);

        $recipes = Recipe::published()->orderBy('title')->get();

        return view('weekly-menus.edit', compact('menu', 'recipes'));
    }

    public function update(WeeklyMenuRequest $request, WeeklyMenu $menu)
    {
        abort_if($menu->user_id !== auth()->id(), 403);

        $data = $request->validated();

        $menu->update([
            'title' => $data['title'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
        ]);

        $menu->slots()->delete();
        $this->syncSlots($menu, $data['slots']);

        return redirect()->route('weekly-menus.index')->with('success', 'Menú semanal actualizado correctamente.');
    }

    public function destroy(WeeklyMenu $menu)
    {
        abort_if($menu->user_id !== auth()->id(), 403);

        $menu->delete();

        return redirect()->route('weekly-menus.index')->with('success', 'Menú semanal eliminado correctamente.');
    }

    public function destroySlot(MenuSlot $slot)
    {
        abort_if($slot->menu->user_id !== auth()->id(), 403);

        $slot->delete();

        return redirect()->route('weekly-menus.index')->with('success', 'Receta eliminada del menú semanal.');
    }

    public function setActive(Request $request, WeeklyMenu $menu)
    {
        abort_if($menu->user_id !== auth()->id(), 403);

        $request->user()->weeklyMenus()->update(['active' => false]);
        $menu->update(['active' => true]);

        return back()->with('success', 'Menú establecido como activo.');
    }

    public function addRecipe(Request $request, WeeklyMenu $menu)
    {
        abort_if($menu->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'slot_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(6)->format('Y-m-d')],
            'meal_type' => ['required', 'in:desayuno,almuerzo,cena,postre,piqueo'],
            'recipe_id' => ['required', 'exists:recipes,id'],
        ]);

        MenuSlot::create([
            'menu_id' => $menu->id,
            'recipe_id' => $data['recipe_id'],
            'slot_date' => $data['slot_date'],
            'meal_type' => $data['meal_type'],
        ]);

        return back()->with('success', 'Receta agregada al menú semanal.');
    }

    private function syncSlots(WeeklyMenu $menu, array $slots): void
    {
        foreach ($slots as $slot) {
            MenuSlot::create([
                'menu_id' => $menu->id,
                'recipe_id' => $slot['recipe_id'],
                'slot_date' => $slot['slot_date'],
                'meal_type' => $slot['meal_type'],
            ]);
        }
    }
}
