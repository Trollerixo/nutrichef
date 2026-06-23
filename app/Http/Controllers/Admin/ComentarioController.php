<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecipeReview;

class ComentarioController extends Controller
{
    public function index()
    {
        $comentarios = RecipeReview::with(['user', 'recipe'])
            ->latest()
            ->paginate(20);

        return view('admin.comentarios.index', compact('comentarios'));
    }

    public function destroy(RecipeReview $review)
    {
        $review->delete();

        return back()->with('success', 'Comentario eliminado.');
    }

    public function flag(RecipeReview $review)
    {
        $review->update(['flagged' => ! $review->flagged]);

        return back()->with('success', $review->flagged ? 'Comentario marcado como spam.' : 'Marca de spam retirada.');
    }
}
