<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    // Slugs de referencia para usar en código sin magic strings
    const USER         = 'user';
    const NUTRITIONIST = 'nutritionist';
    const ADMIN        = 'admin';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
