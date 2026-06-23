<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyMenu extends Model
{
    protected $fillable = [
        'user_id',
        'nutritionist_id',
        'title',
        'notes',
        'status',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Nutricionista que creó o asignó el menú (nullable)
    public function nutritionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutritionist_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(MenuSlot::class, 'menu_id')
            ->orderBy('slot_date')
            ->orderBy('meal_type');
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
