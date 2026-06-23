<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class NotificationUser extends Pivot
{
    protected $table = 'notification_users';

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = [
        'notification_id',
        'user_id',
        'read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read'    => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
