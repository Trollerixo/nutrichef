<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Orden estricto: los catálogos sin FK van primero,
        // luego los que dependen de ellos, y los usuarios al final.
        $this->call([
            // ── Catálogos base (sin FK entre sí) ──
            RoleSeeder::class,
            GoalSeeder::class,
            DietTypeSeeder::class,
            CategorySeeder::class,
            IngredientSeeder::class,
            // ── Usuarios y perfiles (dependen de catálogos) ──
            UserSeeder::class,
            SampleRecipeSeeder::class,
        ]);
    }
}
