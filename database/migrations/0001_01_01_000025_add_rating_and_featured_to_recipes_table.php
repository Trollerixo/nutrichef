<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Promedio cacheado de reseñas para evitar AVG() en cada listado
            $table->float('rating_avg')->nullable()->after('calories');
            // Contador cacheado para recalcular el promedio al añadir/eliminar reseñas
            $table->unsignedInteger('rating_count')->default(0)->after('rating_avg');
            // Fecha en que esta receta es la "Receta del día" (null = no destacada)
            $table->date('featured_date')->nullable()->after('rating_count');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'rating_count', 'featured_date']);
        });
    }
};
