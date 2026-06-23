<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecipeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $raw = $request
            ->user()
            ->recipeHistory()
            ->with(["recipe.category", "recipe.nutrition"])
            ->latest("occurred_at")
            ->get();

        $grouped = $raw->groupBy(fn ($e) => $e->recipe_id ?? "deleted_" . $e->id)
            ->map(function ($entries) {
                $first = $entries->first();
                $actions = $entries->pluck("action")->countBy();

                return (object) [
                    "recipe" => $first->recipe,
                    "actions" => $actions,
                    "entries" => $entries,
                    "last_seen" => $entries->first()->occurred_at,
                    "count" => $entries->count(),
                ];
            })
            ->sortByDesc("last_seen")
            ->values();

        $page = $request->input("page", 1);
        $perPage = 15;
        $total = $grouped->count();
        $history = new \Illuminate\Pagination\LengthAwarePaginator(
            $grouped->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ["path" => $request->url(), "query" => $request->query()],
        );

        return view("history.index", compact("history"));
    }
}
