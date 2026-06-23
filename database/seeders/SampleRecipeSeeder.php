<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $admin =
            User::whereHas("role", function ($q) {
                $q->where("slug", "admin");
            })->first() ?? User::factory()->create();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $recipesData = [
            [
                "title" => "Ensalada de Quinoa y Palta",
                "description" =>
                    "Una ensalada fresca, nutritiva y llena de color.",
                "prep_time_min" => 20,
                "calories" => 350,
                "category" => "Ensaladas",
                "nutrition" => [
                    "protein" => 12,
                    "carbs" => 45,
                    "fats" => 15,
                    "fiber" => 8,
                ],
                "steps" => [
                    "Lavar la quinoa bajo agua fría.",
                    "Cocer la quinoa en agua hirviendo durante 15 minutos.",
                    "Cortar el palta, tomate y pepino en cubos.",
                    "Mezclar todo en un bol con aceite de oliva y limón.",
                ],
                "ingredients" => [
                    ["name" => "Quinoa", "quantity" => "1 taza"],
                    ["name" => "Palta", "quantity" => "1 pieza"],
                    ["name" => "Tomate cherry", "quantity" => "100g"],
                    ["name" => "Aceite de oliva", "quantity" => "2 cucharadas"],
                ],
            ],
            [
                "title" => "Salmón al Horno con Limón",
                "description" =>
                    "Salmón jugoso con un toque cítrico y hierbas.",
                "prep_time_min" => 25,
                "calories" => 450,
                "category" => "Platos Fuertes",
                "nutrition" => [
                    "protein" => 35,
                    "carbs" => 5,
                    "fats" => 28,
                    "fiber" => 0,
                ],
                "steps" => [
                    "Precalentar el horno a 200°C.",
                    "Colocar el salmón en una bandeja.",
                    "Sazonar con sal, pimienta y rodajas de limón.",
                    "Hornear durante 15-18 minutos.",
                ],
                "ingredients" => [
                    ["name" => "Salmón", "quantity" => "200g"],
                    ["name" => "Limón", "quantity" => "1 pieza"],
                    ["name" => "Eneldo", "quantity" => "al gusto"],
                ],
            ],
            [
                "title" => "Tacos de Pollo Saludables",
                "description" =>
                    "Tacos ligeros con tortilla de maíz y verduras.",
                "prep_time_min" => 30,
                "calories" => 400,
                "category" => "Almuerzos",
                "nutrition" => [
                    "protein" => 28,
                    "carbs" => 35,
                    "fats" => 12,
                    "fiber" => 5,
                ],
                "steps" => [
                    "Saltear el pollo cortado en tiras.",
                    "Picar cebolla y cilantro finamente.",
                    "Calentar las tortillas de maíz.",
                    "Armar los tacos y servir con salsa verde.",
                ],
                "ingredients" => [
                    ["name" => "Pechuga de pollo", "quantity" => "150g"],
                    ["name" => "Tortillas de maíz", "quantity" => "3 piezas"],
                    ["name" => "Cilantro", "quantity" => "1 manojo"],
                ],
            ],
            [
                "title" => "Batido Proteico de Plátano y Avena",
                "description" =>
                    "Ideal para después de entrenar o desayuno rápido.",
                "prep_time_min" => 5,
                "calories" => 300,
                "category" => "Desayunos",
                "nutrition" => [
                    "protein" => 20,
                    "carbs" => 40,
                    "fats" => 6,
                    "fiber" => 7,
                ],
                "steps" => [
                    "Poner el plátano y la avena en la licuadora.",
                    "Añadir leche (o bebida vegetal) y proteína en polvo.",
                    "Licuar hasta obtener una mezcla homogénea.",
                    "Servir frío.",
                ],
                "ingredients" => [
                    ["name" => "Plátano", "quantity" => "1 pieza"],
                    ["name" => "Avena", "quantity" => "30g"],
                    ["name" => "Leche desnatada", "quantity" => "250ml"],
                ],
            ],
            [
                "title" => "Pasta Integral con Pesto de Albahaca",
                "description" =>
                    "Un clásico italiano en su versión más saludable.",
                "prep_time_min" => 15,
                "calories" => 550,
                "category" => "Pastas",
                "nutrition" => [
                    "protein" => 15,
                    "carbs" => 70,
                    "fats" => 22,
                    "fiber" => 10,
                ],
                "steps" => [
                    "Hervir agua con sal y cocer la pasta integral.",
                    "Preparar el pesto triturando albahaca, piñones y aceite.",
                    "Mezclar la pasta con el pesto fresco.",
                    "Añadir un poco de queso parmesano rallado.",
                ],
                "ingredients" => [
                    ["name" => "Pasta integral", "quantity" => "80g"],
                    ["name" => "Albahaca fresca", "quantity" => "1 taza"],
                    ["name" => "Piñones", "quantity" => "20g"],
                ],
            ],
            [
                "title" => "Crema de Calabacín Ligeras",
                "description" => "Cena suave y reconfortante para días fríos.",
                "prep_time_min" => 40,
                "calories" => 150,
                "category" => "Sopas",
                "nutrition" => [
                    "protein" => 4,
                    "carbs" => 15,
                    "fats" => 8,
                    "fiber" => 4,
                ],
                "steps" => [
                    "Trocear el calabacín, cebolla y patata.",
                    "Rehogar las verduras en una olla.",
                    "Cubrir con agua y cocer 20 minutos.",
                    "Triturar todo hasta que quede fino.",
                ],
                "ingredients" => [
                    ["name" => "Calabacín", "quantity" => "2 piezas"],
                    ["name" => "Cebolla", "quantity" => "1 pieza"],
                    ["name" => "Patata", "quantity" => "1 pequeña"],
                ],
            ],
            [
                "title" => "Hummus Clásico con Crudités",
                "description" => "El snack perfecto para picar entre horas.",
                "prep_time_min" => 10,
                "calories" => 250,
                "category" => "Snacks",
                "nutrition" => [
                    "protein" => 8,
                    "carbs" => 20,
                    "fats" => 14,
                    "fiber" => 6,
                ],
                "steps" => [
                    "Escurrir los garbanzos cocidos.",
                    "Triturar con tahini, limón, ajo y comino.",
                    "Cortar zanahorias y pepinos en bastones.",
                    "Servir el hummus en un bol con pimentón.",
                ],
                "ingredients" => [
                    ["name" => "Garbanzos cocidos", "quantity" => "200g"],
                    ["name" => "Tahini", "quantity" => "1 cucharada"],
                    ["name" => "Zanahoria", "quantity" => "2 piezas"],
                ],
            ],
            [
                "title" => "Tortilla de Espinacas y Champiñones",
                "description" =>
                    "Cena rápida rica en hierro y baja en calorías.",
                "prep_time_min" => 12,
                "calories" => 280,
                "category" => "Cenas",
                "nutrition" => [
                    "protein" => 18,
                    "carbs" => 6,
                    "fats" => 20,
                    "fiber" => 3,
                ],
                "steps" => [
                    "Saltear los champiñones y las espinacas.",
                    "Batir dos huevos en un bol.",
                    "Mezclar las verduras con el huevo.",
                    "Cuajar la tortilla en la sartén por ambos lados.",
                ],
                "ingredients" => [
                    ["name" => "Huevo", "quantity" => "2 piezas"],
                    ["name" => "Espinacas frescas", "quantity" => "100g"],
                    ["name" => "Champiñones", "quantity" => "50g"],
                ],
            ],
            [
                "title" => "Berenjenas Rellenas de Soja Texturizada",
                "description" => "Opción vegetariana potente y deliciosa.",
                "prep_time_min" => 50,
                "calories" => 320,
                "category" => "Vegetariano",
                "nutrition" => [
                    "protein" => 22,
                    "carbs" => 25,
                    "fats" => 10,
                    "fiber" => 12,
                ],
                "steps" => [
                    "Asar las berenjenas abiertas por la mitad.",
                    "Hidratar la soja texturizada.",
                    "Hacer un sofrito con la pulpa de berenjena y soja.",
                    "Rellenar y gratinar con un poco de queso.",
                ],
                "ingredients" => [
                    ["name" => "Berenjena", "quantity" => "1 pieza"],
                    ["name" => "Soja texturizada", "quantity" => "60g"],
                    ["name" => "Tomate frito natural", "quantity" => "100ml"],
                ],
            ],
            [
                "title" => "Macedonia de Frutas de Temporada",
                "description" => "Postre natural sin azúcares añadidos.",
                "prep_time_min" => 15,
                "calories" => 120,
                "category" => "Postres",
                "nutrition" => [
                    "protein" => 2,
                    "carbs" => 28,
                    "fats" => 0,
                    "fiber" => 5,
                ],
                "steps" => [
                    "Pelar y trocear naranja, manzana y pera.",
                    "Añadir unas fresas cortadas.",
                    "Mezclar con un chorrito de zumo de naranja.",
                    "Dejar reposar 10 minutos antes de servir.",
                ],
                "ingredients" => [
                    ["name" => "Naranja", "quantity" => "1 pieza"],
                    ["name" => "Manzana", "quantity" => "1 pieza"],
                    ["name" => "Fresas", "quantity" => "5 piezas"],
                ],
            ],
        ];

        $recipeMeta = [
            "Ensalada de Quinoa y Palta" => [
                "image" => "https://picsum.photos/seed/quinoa-palta/1200/800",
                "rating_avg" => 4.6,
                "rating_count" => 86,
            ],
            "Salmón al Horno con Limón" => [
                "image" => "https://picsum.photos/seed/salmon-limon/1200/800",
                "rating_avg" => 4.9,
                "rating_count" => 124,
            ],
            "Tacos de Pollo Saludables" => [
                "image" => "https://picsum.photos/seed/tacos-pollo/1200/800",
                "rating_avg" => 4.7,
                "rating_count" => 93,
            ],
            "Batido Proteico de Plátano y Avena" => [
                "image" => "https://picsum.photos/seed/batido-proteico/1200/800",
                "rating_avg" => 4.3,
                "rating_count" => 71,
            ],
            "Pasta Integral con Pesto de Albahaca" => [
                "image" => "https://picsum.photos/seed/pasta-pesto/1200/800",
                "rating_avg" => 4.5,
                "rating_count" => 79,
            ],
            "Crema de Calabacín Ligeras" => [
                "image" => "https://picsum.photos/seed/crema-calabacin/1200/800",
                "rating_avg" => 4.2,
                "rating_count" => 58,
            ],
            "Hummus Clásico con Crudités" => [
                "image" => "https://picsum.photos/seed/hummus-crudites/1200/800",
                "rating_avg" => 4.4,
                "rating_count" => 66,
            ],
            "Tortilla de Espinacas y Champiñones" => [
                "image" => "https://picsum.photos/seed/tortilla-espinacas/1200/800",
                "rating_avg" => 4.1,
                "rating_count" => 49,
            ],
            "Berenjenas Rellenas de Soja Texturizada" => [
                "image" => "https://picsum.photos/seed/berenjenas-soja/1200/800",
                "rating_avg" => 4.0,
                "rating_count" => 41,
            ],
            "Macedonia de Frutas de Temporada" => [
                "image" => "https://picsum.photos/seed/macedonia-frutas/1200/800",
                "rating_avg" => 4.8,
                "rating_count" => 101,
            ],
        ];

        foreach ($recipesData as $data) {
            $category =
                Category::where("name", $data["category"])->first() ??
                $categories->random();
            $slug = Str::slug($data["title"]);
            $meta = $recipeMeta[$data["title"]] ?? [
                "image" => null,
                "rating_avg" => 4.5,
                "rating_count" => 50,
            ];

            $recipe = Recipe::updateOrCreate(
                ["slug" => $slug],
                [
                    "author_id" => $admin->id,
                    "category_id" => $category->id,
                    "title" => $data["title"],
                    "description" => $data["description"],
                    "image" => $meta["image"],
                    "prep_time_min" => $data["prep_time_min"],
                    "calories" => $data["calories"],
                    "published" => true,
                    "rating_avg" => $meta["rating_avg"],
                    "rating_count" => $meta["rating_count"],
                    "featured_date" => now(),
                ],
            );

            $recipe->nutrition()->updateOrCreate(
                ["recipe_id" => $recipe->id],
                [
                    "proteins_g" => $data["nutrition"]["protein"],
                    "carbs_g" => $data["nutrition"]["carbs"],
                    "fats_g" => $data["nutrition"]["fats"],
                    "fiber_g" => $data["nutrition"]["fiber"],
                ],
            );

            $recipe->steps()->delete();
            foreach ($data["steps"] as $index => $desc) {
                $recipe->steps()->create([
                    "step_number" => $index + 1,
                    "instruction" => $desc,
                ]);
            }

            $ingredientIds = [];
            foreach ($data["ingredients"] as $ingData) {
                $ingredient = Ingredient::firstOrCreate(
                    ["name" => $ingData["name"]],
                    ["default_unit" => "unidad"],
                );
                $ingredientIds[$ingredient->id] = [
                    "quantity" => $ingData["quantity"],
                    "notes" => "",
                ];
            }
            $recipe->ingredients()->sync($ingredientIds);
        }
    }
}
