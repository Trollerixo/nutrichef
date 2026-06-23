<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuSlot;
use App\Models\Recipe;
use App\Models\WeeklyMenu;
use App\Support\IngredientTextParser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $myIngredientsRaw = $request->input("my_ingredients", "");
        $myIngredients = IngredientTextParser::parse($myIngredientsRaw);

        $query = Recipe::published()->with([
            "category",
            "nutrition",
            "ingredients",
        ]);

        $isFiltered = $request->anyFilled([
            "search",
            "category",
            "max_calories",
            "max_time",
            "my_ingredients",
        ]);

        $query
            ->when($request->filled("search"), function ($query) use (
                $request,
            ) {
                $query->where("title", "like", "%" . $request->search . "%");
            })
            ->when($request->filled("category"), function ($query) use (
                $request,
            ) {
                $query->whereHas("category", function ($query) use ($request) {
                    $query->where("slug", $request->category);
                });
            })
            ->when($request->filled("max_calories"), function ($query) use (
                $request,
            ) {
                $query->where("calories", "<=", $request->max_calories);
            })
            ->when($request->filled("max_time"), function ($query) use (
                $request,
            ) {
                $query->where("prep_time_min", "<=", $request->max_time);
            });

        $recipesCollection = $query->get();

        if (!empty($myIngredients)) {
            $normalizedPantry = array_map("strtolower", $myIngredients);

            $recipesCollection = $recipesCollection
                ->map(function ($recipe) use ($normalizedPantry) {
                    $recipeIngredients = $recipe->ingredients
                        ->pluck("name")
                        ->map("strtolower")
                        ->toArray();
                    $ownedCount = 0;

                    foreach ($recipeIngredients as $ri) {
                        foreach ($normalizedPantry as $pi) {
                            if (
                                $pi !== "" &&
                                (str_contains($ri, $pi) ||
                                    str_contains($pi, $ri))
                            ) {
                                $ownedCount++;
                                break;
                            }
                        }
                    }

                    $recipe->owned_count = $ownedCount;
                    $recipe->total_count = count($recipeIngredients);
                    // Si no tiene nada, score altísimo para mandarlo al final
                    $recipe->missing_to_buy =
                        $ownedCount > 0
                            ? $recipe->total_count - $ownedCount
                            : 1000;
                    return $recipe;
                })
                ->sort(function ($a, $b) {
                    // Primero por los que tienen menos por comprar
                    if ($a->missing_to_buy !== $b->missing_to_buy) {
                        return $a->missing_to_buy <=> $b->missing_to_buy;
                    }
                    // Si empatan, prioriza la mejor calificación
                    if ($a->rating_avg !== $b->rating_avg) {
                        return $b->rating_avg <=> $a->rating_avg;
                    }

                    return $b->rating_count <=> $a->rating_count;
                });
        } else {
            $recipesCollection = $recipesCollection
                ->sortByDesc("rating_avg")
                ->sortByDesc("rating_count")
                ->sortByDesc("featured_date");
        }

        $perPage = 10;
        $page = $request->input("page", 1);
        $total = $recipesCollection->count();
        $recipes = new LengthAwarePaginator(
            $recipesCollection->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ["path" => $request->url(), "query" => $request->query()],
        );

        $categories = Category::orderBy("name")->get();
        $ingredients = Ingredient::orderBy("name")->get(["id", "name"]);

        return view(
            "recipes.index",
            compact(
                "recipes",
                "categories",
                "ingredients",
                "myIngredients",
                "myIngredientsRaw",
                "isFiltered",
            ),
        );
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(["category", "nutrition", "ingredients", "steps", "reviews.user"]);
        $myIngredientsRaw = request("my_ingredients", "");
        $myIngredients = IngredientTextParser::parse($myIngredientsRaw);
        $userReview = auth()->check() ? $recipe->reviews->where('user_id', auth()->id())->first() : null;

        if (auth()->check()) {
            auth()
                ->user()
                ->recipeHistory()
                ->create([
                    "recipe_id" => $recipe->id,
                    "action" => "viewed",
                    "occurred_at" => now(),
                ]);
        }

        $activeMenu = auth()->check()
            ? auth()->user()->weeklyMenus()->active()->with('slots')->first()
            : null;

        return view(
            "recipes.show",
            compact("recipe", "myIngredients", "myIngredientsRaw", "userReview", "activeMenu"),
        );
    }

    public function storeReview(Request $request, Recipe $recipe)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        abort_if($recipe->reviews()->where('user_id', auth()->id())->exists(), 422, 'Ya publicaste una reseña. Elimínala primero para publicar una nueva.');

        $recipe->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        $recipe->recalculateRating();

        return back()->with('success', 'Reseña publicada.');
    }

    public function destroyReview(Request $request, Recipe $recipe)
    {
        $recipe->reviews()->where('user_id', auth()->id())->delete();

        $recipe->recalculateRating();

        return back()->with('success', 'Reseña eliminada. Puedes publicar una nueva.');
    }

    public function toggleFavorite(Request $request, Recipe $recipe)
    {
        $user = $request->user();
        $isFavorite = $user
            ->favoriteRecipes()
            ->where("recipe_id", $recipe->id)
            ->exists();

        if ($isFavorite) {
            $user->favoriteRecipes()->detach($recipe);
            $message = "Receta eliminada de favoritas.";
        } else {
            $user->favoriteRecipes()->attach($recipe);
            $message = "Receta agregada a favoritas.";
            $user->recipeHistory()->create([
                "recipe_id" => $recipe->id,
                "action" => "favorited",
                "occurred_at" => now(),
            ]);
        }

        return back()->with("success", $message);
    }

    public function addToShoppingList(Request $request, Recipe $recipe)
    {
        $list = $request
            ->user()
            ->shoppingLists()
            ->firstOrCreate([
                "title" => "Lista de compras",
            ]);

        $myIngredientsRaw = $request->input("my_ingredients", "");
        $myIngredients = IngredientTextParser::parse($myIngredientsRaw);

        foreach ($recipe->ingredients as $ingredient) {
            $isChecked = false;
            $ingName = strtolower($ingredient->name);

            foreach ($myIngredients as $mine) {
                $mineNormalized = strtolower($mine);
                if (
                    $mineNormalized !== "" &&
                    (str_contains($ingName, $mineNormalized) ||
                        str_contains($mineNormalized, $ingName))
                ) {
                    $isChecked = true;
                    break;
                }
            }

            $list->items()->updateOrCreate(
                ["ingredient_id" => $ingredient->id],
                [
                    "name" => $ingredient->name,
                    "quantity" => $ingredient->pivot->quantity,
                    "checked" => $isChecked,
                ],
            );
        }

        $request
            ->user()
            ->recipeHistory()
            ->create([
                "recipe_id" => $recipe->id,
                "action" => "added_to_shopping_list",
                "occurred_at" => now(),
            ]);

        return back()->with(
            "success",
            "Ingredientes agregados. Los que ya tenías se marcaron como comprados.",
        );
    }

    public function addToMenu(Request $request, Recipe $recipe)
    {
        $menu = $request->user()->weeklyMenus()->active()->first();

        if (!$menu) {
            $menu = WeeklyMenu::create([
                'user_id' => $request->user()->id,
                'nutritionist_id' => null,
                'title' => 'Mi menú semanal',
                'status' => 'published',
                'active' => true,
            ]);
        }

        $data = $request->validate([
            'slot_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(7)->format('Y-m-d')],
            'meal_type' => ['required', Rule::in(['desayuno', 'almuerzo', 'cena', 'postre', 'piqueo'])],
        ]);

        MenuSlot::create([
            'menu_id' => $menu->id,
            'recipe_id' => $recipe->id,
            'slot_date' => $data['slot_date'],
            'meal_type' => $data['meal_type'],
        ]);

        $request
            ->user()
            ->recipeHistory()
            ->create([
                "recipe_id" => $recipe->id,
                "action" => "added_to_menu",
                "occurred_at" => now(),
            ]);

        return back()->with(
            "success",
            "Receta agregada al menú semanal activo.",
        );
    }
}
