<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthRecord extends Model
{
    protected $fillable = [
        'user_id',
        'weight_kg',
        'height_cm',
        'imc',
        'body_fat_pct',
        'record_date',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg'    => 'float',
            'height_cm'    => 'float',
            'imc'          => 'float',
            'body_fat_pct' => 'float',
            'record_date'  => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // IMC se puede calcular en lugar de guardarlo,
    // pero guardarlo facilita reportes históricos sin recalcular.
    // Si prefieres calcularlo al vuelo:
    // public function getImcAttribute(): float
    // {
    //     $heightM = $this->height_cm / 100;
    //     return round($this->weight_kg / ($heightM * $heightM), 2);
    // }
}
