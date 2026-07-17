<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    protected $fillable = [
        "author_id",
        "category_id",
        "title",
        "slug",
        "description",
        "image",
        "prep_time_min",
        "calories",
        "rating_avg",
        "rating_count",
        "featured_date",
        "published",
    ];

    protected function casts(): array
    {
        return [
            "published" => "boolean",
            "prep_time_min" => "integer",
            "calories" => "integer",
            "rating_avg" => "float",
            "rating_count" => "integer",
            "featured_date" => "date",
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "author_id");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, "recipe_ingredients")
            ->using(RecipeIngredient::class)
            ->withPivot(["quantity", "notes"]);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy("step_number");
    }

    public function nutrition(): HasOne
    {
        return $this->hasOne(RecipeNutrition::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(RecipeReview::class)->latest();
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "favorites")->withTimestamps();
    }

    public function history(): HasMany
    {
        return $this->hasMany(RecipeHistory::class)->orderByDesc("occurred_at");
    }

    public function menuSlots(): HasMany
    {
        return $this->hasMany(MenuSlot::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(RecipeRecommendation::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where("published", true);
    }

    public function scopeFeaturedToday($query)
    {
        return $query->published()->whereDate("featured_date", today());
    }

    public function scopeFeaturedThisWeek($query)
    {
        return $query
            ->published()
            ->whereBetween("featured_date", [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
    }

    public function scopeTopRated($query)
    {
        return $query
            ->orderByDesc("rating_avg")
            ->orderByDesc("rating_count")
            ->orderByDesc("featured_date");
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return "https://picsum.photos/seed/" . ($this->slug ?? 'recipe') . "/1200/800";
        }

        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image;
        }

        // Fallback si el archivo físico no existe en el almacenamiento (típico en la nube con discos efímeros)
        if (!Storage::disk('public')->exists($this->image)) {
            return "https://picsum.photos/seed/" . ($this->slug ?? 'recipe') . "/1200/800";
        }

        return Storage::disk('public')->url($this->image);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function recalculateRating(): void
    {
        $this->rating_count = $this->reviews()->count();
        $this->rating_avg = $this->reviews()->avg('rating') ?? 0.0;
        $this->saveQuietly();
    }
}
