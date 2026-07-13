<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecipeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Paginate unique recipe_ids from the user's history based on the latest occurred_at
        $paginatedIds = $user->recipeHistory()
            ->select('recipe_id')
            ->selectRaw('MAX(occurred_at) as last_seen')
            ->groupBy('recipe_id')
            ->orderByDesc('last_seen')
            ->paginate(15);

        // 2. Fetch the full detailed history entries only for the recipes on the current page
        $recipeIds = $paginatedIds->pluck('recipe_id')->filter()->all();

        $historyDetails = $user->recipeHistory()
            ->whereIn('recipe_id', $recipeIds)
            ->with(['recipe.category', 'recipe.nutrition'])
            ->latest('occurred_at')
            ->get()
            ->groupBy('recipe_id');

        // 3. Map the paginated collection to match the structure expected by the view
        $paginatedIds->getCollection()->transform(function ($item) use ($historyDetails) {
            $entries = $historyDetails->get($item->recipe_id) ?: collect();
            $first = $entries->first();
            $actions = $entries->pluck('action')->countBy();

            return (object) [
                'recipe' => $first ? $first->recipe : null,
                'actions' => $actions,
                'entries' => $entries,
                'last_seen' => \Carbon\Carbon::parse($item->last_seen),
                'count' => $entries->count(),
            ];
        });

        $history = $paginatedIds;

        return view("history.index", compact("history"));
    }
}
