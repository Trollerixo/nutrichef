<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeReview extends Model
{
    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating',
        'comment',
        'flagged',
    ];

    protected function casts(): array
    {
        return [
            'rating'  => 'integer',
            'flagged' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
