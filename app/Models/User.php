<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "role_id",
        "name",
        "email",
        "password",
        "avatar",
        "specialty",
        "active",
        "last_seen_at",
    ];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "active" => "boolean",
            "last_seen_at" => "datetime",
        ];
    }

    // ─── Perfil y salud ───────────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class)->orderByDesc("record_date");
    }

    // ─── Nutricionista ↔ Paciente ─────────────────────────────────

    // Pivot NutritionistPatient (cuando el user es nutricionista)
    public function patients(): HasMany
    {
        return $this->hasMany(NutritionistPatient::class, "nutritionist_id");
    }

    // Pivot NutritionistPatient (cuando el user es paciente)
    public function nutritionists(): HasMany
    {
        return $this->hasMany(NutritionistPatient::class, "patient_id");
    }

    // Usuarios pacientes directamente (BelongsToMany para eager loading eficiente)
    public function patientsUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            "nutritionist_patients",
            "nutritionist_id",
            "patient_id",
        )
            ->wherePivot("active", true)
            ->withPivot("active")
            ->withTimestamps();
    }

    // Usuarios nutricionistas directamente
    public function nutritionistsUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            "nutritionist_patients",
            "patient_id",
            "nutritionist_id",
        )
            ->wherePivot("active", true)
            ->withPivot("active")
            ->withTimestamps();
    }

    // ─── Recetas ──────────────────────────────────────────────────

    // Recetas creadas por el usuario (nutricionista o admin)
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, "author_id");
    }

    public function recipeReviews(): HasMany
    {
        return $this->hasMany(RecipeReview::class);
    }

    public function favoriteRecipes(): BelongsToMany
    {
        return $this->belongsToMany(
            Recipe::class,
            "favorites",
        )->withTimestamps();
    }

    public function recipeHistory(): HasMany
    {
        return $this->hasMany(RecipeHistory::class)->orderByDesc("occurred_at");
    }

    // ─── Menús semanales ──────────────────────────────────────────

    // Menús que pertenecen al usuario (paciente)
    public function weeklyMenus(): HasMany
    {
        return $this->hasMany(WeeklyMenu::class);
    }

    // Menús asignados por el nutricionista a sus pacientes
    public function assignedMenus(): HasMany
    {
        return $this->hasMany(WeeklyMenu::class, "nutritionist_id");
    }

    // ─── Listas de compra ─────────────────────────────────────────

    public function shoppingLists(): HasMany
    {
        return $this->hasMany(ShoppingList::class);
    }

    // ─── Recomendaciones ──────────────────────────────────────────

    // Recomendaciones enviadas (como nutricionista)
    public function sentRecommendations(): HasMany
    {
        return $this->hasMany(RecipeRecommendation::class, "nutritionist_id");
    }

    // Recomendaciones recibidas (como paciente)
    public function receivedRecommendations(): HasMany
    {
        return $this->hasMany(RecipeRecommendation::class, "patient_id");
    }

    // ─── Consultas ────────────────────────────────────────────────

    public function consultationsAsPatient(): HasMany
    {
        return $this->hasMany(Consultation::class, "patient_id");
    }

    public function consultationsAsNutritionist(): HasMany
    {
        return $this->hasMany(Consultation::class, "nutritionist_id");
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, "sender_id");
    }

    // ─── Notificaciones del sistema ───────────────────────────────

    // Notificaciones enviadas por el admin (sent_by = este user)
    public function sentSystemNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, "sent_by");
    }

    // Notificaciones recibidas a través del pivot notification_users
    public function receivedNotifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, "notification_users")
            ->using(NotificationUser::class)
            ->withPivot(["read", "read_at"]);
    }

    // ─── Scopes ───────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where("active", true);
    }

    // ─── Helpers de rol ───────────────────────────────────────────

    public function isUser(): bool
    {
        return $this->role?->slug === Role::USER;
    }

    public function isNutritionist(): bool
    {
        return $this->role?->slug === Role::NUTRITIONIST;
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === Role::ADMIN;
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    // ─── Online status ─────────────────────────────────────────────

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function lastSeen(): string
    {
        if (! $this->last_seen_at) {
            return 'Nunca conectado';
        }

        if ($this->isOnline()) {
            return 'En línea';
        }

        return 'Visto ' . $this->last_seen_at->diffForHumans();
    }
}
