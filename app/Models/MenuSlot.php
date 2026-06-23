<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuSlot extends Model
{
    protected $fillable = [
        'menu_id',
        'recipe_id',
        'slot_date',
        'meal_type',
    ];

    protected function casts(): array
    {
        return [
            'slot_date' => 'date',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(WeeklyMenu::class, 'menu_id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
