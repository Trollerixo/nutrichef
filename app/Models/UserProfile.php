<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'goal_id',
        'diet_type_id',
        'allergies',
        'preferences',
        'notifications_enabled',
    ];

    protected function casts(): array
    {
        return [
            'allergies'              => 'array',
            'preferences'            => 'array',
            'notifications_enabled'  => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function dietType(): BelongsTo
    {
        return $this->belongsTo(DietType::class);
    }
}
