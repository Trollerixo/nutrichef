<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\FavoriteRecipeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Nutritionist\ConsultationController as NutritionistConsultationController;
use App\Http\Controllers\Nutritionist\PatientController as NutritionistPatientController;
use App\Http\Controllers\Nutritionist\PlanController as NutritionistPlanController;
use App\Http\Controllers\Nutritionist\RecommendationController as NutritionistRecommendationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeHistoryController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\WeeklyMenuController;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/auth.php';

Route::get("/", function () {
    $featuredRecipe = null;
    $featuredRecipes = collect();

    if (Schema::hasTable("recipes")) {
        $featuredRecipes = Recipe::published()
            ->with(["category"])
            ->topRated()
            ->limit(3)
            ->get();

        $featuredRecipe = Recipe::published()
            ->with(["category"])
            ->featuredThisWeek()
            ->topRated()
            ->first();

        if (!$featuredRecipe) {
            $featuredRecipe = $featuredRecipes->first();
        }
    }

    return view("welcome", compact("featuredRecipe", "featuredRecipes"));
});

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    Route::get("/notificaciones", [
        \App\Http\Controllers\NotificationController::class,
        "index",
    ])->name("notifications.index");
    Route::post("/notificaciones/leer-todas", [
        \App\Http\Controllers\NotificationController::class,
        "markAllAsRead",
    ])->name("notifications.readAll");
    Route::post("/notificaciones/{notification}/leer", [
        \App\Http\Controllers\NotificationController::class,
        "markAsRead",
    ])->name("notifications.read");

    Route::middleware("role:user")->group(function () {
        Route::get("/favoritas", [
            FavoriteRecipeController::class,
            "index",
        ])->name("favorites.index");

        Route::get("/lista-compras", [
            ShoppingListController::class,
            "index",
        ])->name("shopping.index");
        Route::post("/lista-compras", [
            ShoppingListController::class,
            "store",
        ])->name("shopping.store");
        Route::put("/lista-compras/{list}", [
            ShoppingListController::class,
            "update",
        ])->name("shopping.update");
        Route::delete("/lista-compras/{list}", [
            ShoppingListController::class,
            "destroy",
        ])->name("shopping.destroy");
        Route::post("/lista-compras/{list}/items", [
            ShoppingListController::class,
            "addItem",
        ])->name("shopping.items.add");
        Route::patch("/lista-compras/items/{item}/toggle", [
            ShoppingListController::class,
            "toggleItem",
        ])->name("shopping.items.toggle");
        Route::delete("/lista-compras/items/{item}", [
            ShoppingListController::class,
            "destroyItem",
        ])->name("shopping.items.destroy");

        Route::get("/menu-semanal", [
            WeeklyMenuController::class,
            "index",
        ])->name("weekly-menus.index");
        Route::get("/menu-semanal/crear", [
            WeeklyMenuController::class,
            "create",
        ])->name("weekly-menus.create");
        Route::post("/menu-semanal", [WeeklyMenuController::class, "store"])->name(
            "weekly-menus.store",
        );
        Route::get("/menu-semanal/{menu}/editar", [
            WeeklyMenuController::class,
            "edit",
        ])->name("weekly-menus.edit");
        Route::put("/menu-semanal/{menu}", [
            WeeklyMenuController::class,
            "update",
        ])->name("weekly-menus.update");
        Route::delete("/menu-semanal/{menu}", [
            WeeklyMenuController::class,
            "destroy",
        ])->name("weekly-menus.destroy");
        Route::delete("/menu-semanal/slots/{slot}", [
            WeeklyMenuController::class,
            "destroySlot",
        ])->name("weekly-menus.slots.destroy");
        Route::post("/menu-semanal/{menu}/activar", [
            WeeklyMenuController::class,
            "setActive",
        ])->name("weekly-menus.setActive");
        Route::post("/menu-semanal/{menu}/agregar-receta", [
            WeeklyMenuController::class,
            "addRecipe",
        ])->name("weekly-menus.addRecipe");

        Route::get("/historial", [
            RecipeHistoryController::class,
            "index",
        ])->name("history.index");

        Route::get("/mensajes", [MessageController::class, "index"])->name(
            "messages.index",
        );

        Route::get("/recomendaciones", [
            RecommendationController::class,
            "index",
        ])->name("recommendations.index");
        Route::post("/mensajes", [MessageController::class, "store"])->name(
            "messages.store",
        );


        Route::get("/consultas/crear", [
            ConsultationController::class,
            "create",
        ])->name("consultations.create");
        Route::post("/consultas", [ConsultationController::class, "store"])->name(
            "consultations.store",
        );
        Route::get("/consultas/{consultation}", [
            ConsultationController::class,
            "show",
        ])->name("consultations.show");
        Route::get("/consultas/{consultation}/online", [
            ConsultationController::class,
            "onlineStatus",
        ])->name("consultations.online");

        Route::post("/recetas/{recipe}/favorito", [
            RecipeController::class,
            "toggleFavorite",
        ])->name("recipes.toggleFavorite");
        Route::post("/recetas/{recipe}/lista-compras", [
            RecipeController::class,
            "addToShoppingList",
        ])->name("recipes.addToShoppingList");
        Route::post("/recetas/{recipe}/menu", [
            RecipeController::class,
            "addToMenu",
        ])->name("recipes.addToMenu");
        Route::post("/recetas/{recipe}/resena", [
            RecipeController::class,
            "storeReview",
        ])->name("recipes.review");
        Route::delete("/recetas/{recipe}/resena", [
            RecipeController::class,
            "destroyReview",
        ])->name("recipes.review.destroy");
    });

    Route::middleware("role:nutritionist")
        ->prefix("nutricionista")
        ->name("nutritionist.")
        ->group(function () {
            Route::get("pacientes", [
                NutritionistPatientController::class,
                "index",
            ])->name("patients.index");
            Route::get("consultas", [
                NutritionistConsultationController::class,
                "index",
            ])->name("consultations.index");
            Route::get("consultas/{consultation}", [
                NutritionistConsultationController::class,
                "show",
            ])->name("consultations.show");
            Route::get("consultas/{consultation}/online", [
                NutritionistConsultationController::class,
                "onlineStatus",
            ])->name("consultations.online");
            Route::post("consultas/{consultation}/mensajes", [
                NutritionistConsultationController::class,
                "reply",
            ])->name("consultations.reply");
            Route::get("planes", [
                NutritionistPlanController::class,
                "index",
            ])->name("plans.index");
            Route::get("planes/crear", [
                NutritionistPlanController::class,
                "create",
            ])->name("plans.create");
            Route::post("planes", [
                NutritionistPlanController::class,
                "store",
            ])->name("plans.store");
            Route::get("recomendar", [
                NutritionistRecommendationController::class,
                "index",
            ])->name("recommendations.index");
            Route::get("recomendar/crear", [
                NutritionistRecommendationController::class,
                "create",
            ])->name("recommendations.create");
            Route::post("recomendar", [
                NutritionistRecommendationController::class,
                "store",
            ])->name("recommendations.store");
        });
});

// ─── Panel de administración ──────────────────────────────────────────────────
Route::middleware(["auth", "verified", "role:admin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        // Nutricionistas
        Route::get("nutricionistas", [
            Admin\NutricionistaController::class,
            "index",
        ])->name("nutricionistas.index");
        Route::get("nutricionistas/crear", [
            Admin\NutricionistaController::class,
            "create",
        ])->name("nutricionistas.create");
        Route::post("nutricionistas", [
            Admin\NutricionistaController::class,
            "store",
        ])->name("nutricionistas.store");
        Route::get("nutricionistas/{user}/editar", [
            Admin\NutricionistaController::class,
            "edit",
        ])->name("nutricionistas.edit");
        Route::put("nutricionistas/{user}", [
            Admin\NutricionistaController::class,
            "update",
        ])->name("nutricionistas.update");
        Route::patch("nutricionistas/{user}/toggle", [
            Admin\NutricionistaController::class,
            "toggle",
        ])->name("nutricionistas.toggle");

        // Moderación de comentarios
        Route::get("comentarios", [
            Admin\ComentarioController::class,
            "index",
        ])->name("comentarios.index");
        Route::delete("comentarios/{review}", [
            Admin\ComentarioController::class,
            "destroy",
        ])->name("comentarios.destroy");
        Route::patch("comentarios/{review}/flag", [
            Admin\ComentarioController::class,
            "flag",
        ])->name("comentarios.flag");

        // Reportes / Estadísticas
        Route::get("reportes", [Admin\ReporteController::class, "index"])->name(
            "reportes.index",
        );
        Route::get("reportes/export/{format}", [
            Admin\ReporteController::class,
            "export",
        ])->name("reportes.export");

        // Notificaciones
        Route::get("notificaciones", [
            Admin\NotificacionController::class,
            "index",
        ])->name("notificaciones.index");
        Route::post("notificaciones", [
            Admin\NotificacionController::class,
            "store",
        ])->name("notificaciones.store");

        // Sistema
        Route::get("sistema", [Admin\SistemaController::class, "index"])->name(
            "sistema.index",
        );

        // Admin recetas, categorías y usuarios
        Route::get("recetas", [Admin\RecipeController::class, "index"])->name(
            "recetas.index",
        );
        Route::get("recetas/crear", [
            Admin\RecipeController::class,
            "create",
        ])->name("recetas.create");
        Route::post("recetas", [Admin\RecipeController::class, "store"])->name(
            "recetas.store",
        );
        Route::get("recetas/{recipe}/editar", [
            Admin\RecipeController::class,
            "edit",
        ])->name("recetas.edit");
        Route::put("recetas/{recipe}", [
            Admin\RecipeController::class,
            "update",
        ])->name("recetas.update");
        Route::delete("recetas/{recipe}", [
            Admin\RecipeController::class,
            "destroy",
        ])->name("recetas.destroy");

        Route::get("categorias", [
            Admin\CategoryController::class,
            "index",
        ])->name("categorias.index");
        Route::get("categorias/crear", [
            Admin\CategoryController::class,
            "create",
        ])->name("categorias.create");
        Route::post("categorias", [
            Admin\CategoryController::class,
            "store",
        ])->name("categorias.store");
        Route::get("categorias/{category}/editar", [
            Admin\CategoryController::class,
            "edit",
        ])->name("categorias.edit");
        Route::put("categorias/{category}", [
            Admin\CategoryController::class,
            "update",
        ])->name("categorias.update");
        Route::delete("categorias/{category}", [
            Admin\CategoryController::class,
            "destroy",
        ])->name("categorias.destroy");

        Route::get("usuarios", [Admin\UserController::class, "index"])->name(
            "usuarios.index",
        );
        Route::get("usuarios/crear", [
            Admin\UserController::class,
            "create",
        ])->name("usuarios.create");
        Route::post("usuarios", [Admin\UserController::class, "store"])->name(
            "usuarios.store",
        );
        Route::get("usuarios/{user}/editar", [
            Admin\UserController::class,
            "edit",
        ])->name("usuarios.edit");
        Route::put("usuarios/{user}", [
            Admin\UserController::class,
            "update",
        ])->name("usuarios.update");
        Route::delete("usuarios/{user}", [
            Admin\UserController::class,
            "destroy",
        ])->name("usuarios.destroy");
    });

    Route::get("/recetas", [RecipeController::class, "index"])->name(
        "recipes.index",
    );
    Route::get("/recetas/{recipe}", [RecipeController::class, "show"])->name(
        "recipes.show",
    );
