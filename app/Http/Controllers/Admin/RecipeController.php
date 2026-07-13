<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecipeRequest;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeStep;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('category', 'author')->orderByDesc('created_at')->paginate(15);

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.recipes.create', compact('categories'));
    }

    public function store(RecipeRequest $request)
    {
        $data = $request->validated();

        $data['author_id'] = auth()->id();
        $data['published'] = $request->boolean('published');
        $data['image'] = $request->hasFile('image')
            ? $request->file('image')->store('recipes', 'public')
            : null;

        DB::transaction(function () use ($data) {
            $recipe = Recipe::create($data);
            $this->syncRecipeIngredientsAndSteps(
                $recipe,
                $data['ingredients'] ?? [],
                $data['steps'] ?? [],
            );
            $this->syncRecipeNutrition($recipe, $data['nutrition'] ?? []);
        });

        return redirect()->route('admin.recetas.index')->with('success', 'Receta creada correctamente.');
    }

    public function edit(Recipe $recipe)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.recipes.edit', compact('recipe', 'categories'));
    }

    public function update(RecipeRequest $request, Recipe $recipe)
    {
        $data = $request->validated();

        $data['published'] = $request->boolean('published');
        if ($request->hasFile('image')) {
            $this->deleteStoredImage($recipe->image);
            $data['image'] = $request->file('image')->store('recipes', 'public');
        } else {
            unset($data['image']);
        }

        DB::transaction(function () use ($recipe, $data) {
            $recipe->update($data);
            $this->syncRecipeIngredientsAndSteps(
                $recipe,
                $data['ingredients'] ?? [],
                $data['steps'] ?? [],
            );
            $this->syncRecipeNutrition($recipe, $data['nutrition'] ?? []);
        });

        return redirect()->route('admin.recetas.index')->with('success', 'Receta actualizada correctamente.');
    }

    public function destroy(Recipe $recipe)
    {
        $this->deleteStoredImage($recipe->image);
        $recipe->delete();

        return redirect()->route('admin.recetas.index')->with('success', 'Receta actualizada correctamente.');
    }

    private function syncRecipeNutrition(Recipe $recipe, array $nutritionData): void
    {
        $hasData = collect($nutritionData)->contains(fn($val) => !is_null($val) && $val !== '');

        if ($hasData) {
            $recipe->nutrition()->updateOrCreate(
                [],
                [
                    'proteins_g' => $nutritionData['proteins_g'] ?? null,
                    'carbs_g'    => $nutritionData['carbs_g'] ?? null,
                    'fats_g'     => $nutritionData['fats_g'] ?? null,
                    'fiber_g'    => $nutritionData['fiber_g'] ?? null,
                ]
            );
        } else {
            $recipe->nutrition()->delete();
        }
    }

    private function syncRecipeIngredientsAndSteps(Recipe $recipe, array $ingredients, array $steps): void
    {
        $pivotData = [];

        foreach ($ingredients as $ingredientData) {
            $name = trim($ingredientData['name'] ?? '');
            $quantity = trim($ingredientData['quantity'] ?? '');
            $notes = trim($ingredientData['notes'] ?? '');

            if ($name === '') {
                continue;
            }

            $ingredient = Ingredient::firstOrCreate([
                'name' => $name,
            ]);

            $pivotData[$ingredient->id] = [
                'quantity' => $quantity,
                'notes' => $notes ?: null,
            ];
        }

        $recipe->ingredients()->sync($pivotData);
        $recipe->steps()->delete();

        foreach (array_values(array_filter($steps, fn ($step) => trim($step['instruction'] ?? '') !== '')) as $index => $stepData) {
            $recipe->steps()->create([
                'step_number' => $index + 1,
                'instruction' => trim($stepData['instruction']),
            ]);
        }
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
