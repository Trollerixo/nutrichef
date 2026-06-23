<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Desayunos',        'slug' => 'desayunos'],
            ['name' => 'Almuerzos',        'slug' => 'almuerzos'],
            ['name' => 'Cenas',            'slug' => 'cenas'],
            ['name' => 'Ensaladas',        'slug' => 'ensaladas'],
            ['name' => 'Sopas y Caldos',   'slug' => 'sopas-caldos'],
            ['name' => 'Snacks',           'slug' => 'snacks'],
            ['name' => 'Postres',          'slug' => 'postres'],
            ['name' => 'Bebidas',          'slug' => 'bebidas'],
            ['name' => 'Pastas y Arroces', 'slug' => 'pastas-arroces'],
            ['name' => 'Carnes',           'slug' => 'carnes'],
            ['name' => 'Pescados',         'slug' => 'pescados'],
            ['name' => 'Vegetariano',      'slug' => 'vegetariano'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
