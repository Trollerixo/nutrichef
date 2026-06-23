<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeNutrition extends Model
{
    protected $table = 'recipe_nutrition';

    // recipe_id es la PK (no auto-increment), por ser relación 1:1
    protected $primaryKey = 'recipe_id';
    public $incrementing  = false;

    protected $fillable = [
        'recipe_id',
        'proteins_g',
        'carbs_g',
        'fats_g',
        'fiber_g',
    ];

    protected function casts(): array
    {
        return [
            'proteins_g' => 'float',
            'carbs_g'    => 'float',
            'fats_g'     => 'float',
            'fiber_g'    => 'float',
        ];
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
