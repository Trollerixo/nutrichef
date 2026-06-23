<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // Proteínas animales
            ['name' => 'Pechuga de pollo', 'default_unit' => 'g'],
            ['name' => 'Carne molida',     'default_unit' => 'g'],
            ['name' => 'Salmón',           'default_unit' => 'g'],
            ['name' => 'Atún en lata',     'default_unit' => 'g'],
            ['name' => 'Huevo',            'default_unit' => 'unidad'],
            ['name' => 'Pavo',             'default_unit' => 'g'],
            // Proteínas vegetales
            ['name' => 'Tofu',             'default_unit' => 'g'],
            ['name' => 'Lentejas',         'default_unit' => 'g'],
            ['name' => 'Garbanzos',        'default_unit' => 'g'],
            ['name' => 'Frijoles negros',  'default_unit' => 'g'],
            // Lácteos
            ['name' => 'Leche',            'default_unit' => 'ml'],
            ['name' => 'Yogur natural',    'default_unit' => 'g'],
            ['name' => 'Queso fresco',     'default_unit' => 'g'],
            ['name' => 'Queso rallado',    'default_unit' => 'g'],
            // Verduras
            ['name' => 'Tomate',           'default_unit' => 'unidad'],
            ['name' => 'Lechuga',          'default_unit' => 'unidad'],
            ['name' => 'Espinacas',        'default_unit' => 'g'],
            ['name' => 'Zanahoria',        'default_unit' => 'unidad'],
            ['name' => 'Cebolla',          'default_unit' => 'unidad'],
            ['name' => 'Ajo',              'default_unit' => 'diente'],
            ['name' => 'Pimiento rojo',    'default_unit' => 'unidad'],
            ['name' => 'Pimiento verde',   'default_unit' => 'unidad'],
            ['name' => 'Brócoli',          'default_unit' => 'g'],
            ['name' => 'Calabacín',        'default_unit' => 'unidad'],
            ['name' => 'Pepino',           'default_unit' => 'unidad'],
            ['name' => 'Palta',         'default_unit' => 'unidad'],
            // Carbohidratos
            ['name' => 'Arroz blanco',     'default_unit' => 'g'],
            ['name' => 'Arroz integral',   'default_unit' => 'g'],
            ['name' => 'Pasta',            'default_unit' => 'g'],
            ['name' => 'Pan integral',     'default_unit' => 'rebanada'],
            ['name' => 'Avena',            'default_unit' => 'g'],
            ['name' => 'Quinoa',           'default_unit' => 'g'],
            ['name' => 'Papa',             'default_unit' => 'unidad'],
            // Frutas
            ['name' => 'Manzana',          'default_unit' => 'unidad'],
            ['name' => 'Banana',           'default_unit' => 'unidad'],
            ['name' => 'Naranja',          'default_unit' => 'unidad'],
            ['name' => 'Fresa',            'default_unit' => 'g'],
            ['name' => 'Mango',            'default_unit' => 'unidad'],
            ['name' => 'Arándanos',        'default_unit' => 'g'],
            // Condimentos y aceites
            ['name' => 'Aceite de oliva',  'default_unit' => 'ml'],
            ['name' => 'Sal',              'default_unit' => 'g'],
            ['name' => 'Pimienta negra',   'default_unit' => 'g'],
            ['name' => 'Limón',            'default_unit' => 'unidad'],
            ['name' => 'Vinagre',          'default_unit' => 'ml'],
            ['name' => 'Mostaza',          'default_unit' => 'g'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::firstOrCreate(
                ['name' => $ingredient['name']],
                $ingredient
            );
        }
    }
}
