<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $lists = $request
            ->user()
            ->shoppingLists()
            ->with(["items.ingredient"])
            ->get();

        return view("shopping-lists.index", compact("lists"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
        ]);

        $request
            ->user()
            ->shoppingLists()
            ->create([
                "title" => $request->title,
            ]);

        return back()->with(
            "success",
            "Lista de compras creada correctamente.",
        );
    }

    public function update(Request $request, ShoppingList $list)
    {
        abort_if($list->user_id !== auth()->id(), 403);

        $request->validate([
            "title" => "required|string|max:255",
        ]);

        $list->update(["title" => $request->title]);

        return back()->with("success", "Lista de compras actualizada.");
    }

    public function destroy(ShoppingList $list)
    {
        abort_if($list->user_id !== auth()->id(), 403);

        $list->delete();

        return back()->with("success", "Lista de compras eliminada.");
    }

    public function addItem(Request $request, ShoppingList $list)
    {
        abort_if($list->user_id !== auth()->id(), 403);

        $request->validate([
            "name" => "required|string|max:255",
            "quantity" => "nullable|string|max:100",
        ]);

        $list->items()->create([
            "name" => $request->name,
            "quantity" => $request->quantity,
            "checked" => false,
        ]);

        return back()->with("success", "Ítem agregado a la lista.");
    }

    public function toggleItem(Request $request, ShoppingListItem $item)
    {
        abort_if($item->list?->user_id !== auth()->id(), 403);

        $item->update(["checked" => !$item->checked]);

        if ($request->expectsJson()) {
            return response()->json([
                "success" => true,
                "checked" => $item->checked,
                "message" => "Estado del ítem actualizado.",
            ]);
        }

        return back()->with("success", "Estado del ítem actualizado.");
    }

    public function destroyItem(ShoppingListItem $item)
    {
        abort_if($item->list?->user_id !== auth()->id(), 403);

        $item->delete();

        return back()->with("success", "Ítem eliminado correctamente.");
    }
}
