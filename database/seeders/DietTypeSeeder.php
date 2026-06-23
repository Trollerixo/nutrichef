<?php

namespace Database\Seeders;

use App\Models\DietType;
use Illuminate\Database\Seeder;

class DietTypeSeeder extends Seeder
{
    public function run(): void
    {
        $diets = [
            ['name' => 'Omnívoro',      'slug' => 'omnivoro'],
            ['name' => 'Vegetariano',   'slug' => 'vegetariano'],
            ['name' => 'Vegano',        'slug' => 'vegano'],
            ['name' => 'Pescetariano',  'slug' => 'pescetariano'],
            ['name' => 'Mediterránea',  'slug' => 'mediterranea'],
            ['name' => 'Keto',          'slug' => 'keto'],
        ];

        foreach ($diets as $diet) {
            DietType::firstOrCreate(['slug' => $diet['slug']], $diet);
        }
    }
}
