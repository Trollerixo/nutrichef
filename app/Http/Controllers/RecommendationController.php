<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        $recommendations = $request->user()
            ->receivedRecommendations()
            ->with(['nutritionist', 'recipe'])
            ->latest('sent_at')
            ->get();

        return view('recommendations.index', compact('recommendations'));
    }
}
