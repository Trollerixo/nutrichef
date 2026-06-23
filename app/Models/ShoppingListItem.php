<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
    protected $fillable = [
        "list_id",
        "name", // Nombre visible: del catálogo o texto libre
        "ingredient_id", // nullable — null si el ítem es personalizado
        "quantity",
        "checked",
    ];

    protected function casts(): array
    {
        return [
            "checked" => "boolean",
        ];
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class, "list_id");
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
