<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteRecipeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $recipes = $user->favoriteRecipes()
            ->with(['category', 'nutrition'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('favorites.index', compact('recipes'));
    }
}
