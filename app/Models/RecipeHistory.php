<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeHistory extends Model
{
    protected $table = 'recipe_history';

    // La tabla solo tiene occurred_at, sin created_at/updated_at estándar
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'action',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
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
