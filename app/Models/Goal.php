<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = ['name', 'slug'];

    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}
