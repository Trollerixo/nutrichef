<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Notificaciones personalizadas enviadas por el admin a usuarios del sistema.
 *
 * Usa la tabla "system_notifications" para coexistir sin conflicto con el canal
 * database de Laravel (que usa su propia tabla "notifications" polimórfica).
 * Ambos sistemas pueden usarse simultáneamente sin ningún problema.
 */
class Notification extends Model
{
    protected $table = "system_notifications";

    protected $fillable = ["sent_by", "title", "message", "target", "sent_at"];

    protected function casts(): array
    {
        return [
            "sent_at" => "datetime",
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────────

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "sent_by");
    }

    public function notificationUsers(): HasMany
    {
        return $this->hasMany(NotificationUser::class);
    }

    // Usuarios destinatarios a través del pivot
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "notification_users")
            ->using(NotificationUser::class)
            ->withPivot(["read", "read_at"]);
    }
}
