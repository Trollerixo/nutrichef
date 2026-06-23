<?php

namespace Database\Seeders;

use App\Models\Goal;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            ['name' => 'Bajar de peso',   'slug' => 'bajar_peso'],
            ['name' => 'Mantenimiento',   'slug' => 'mantenimiento'],
            ['name' => 'Ganar músculo',   'slug' => 'ganar_musculo'],
            ['name' => 'Mejorar salud',   'slug' => 'mejorar_salud'],
        ];

        foreach ($goals as $goal) {
            Goal::firstOrCreate(['slug' => $goal['slug']], $goal);
        }
    }
}
