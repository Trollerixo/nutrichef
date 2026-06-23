<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shopping_list_items', function (Blueprint $table) {
            // Nombre visible del ítem: del catálogo o texto libre del usuario.
            // Siempre requerido. Permite mostrar "Leche descremada" aunque no esté
            // en el catálogo de ingredients.
            $table->string('name')->after('list_id');

            // ingredient_id se vuelve nullable para soportar ítems personalizados.
            // Si el ingrediente del catálogo es borrado, el ítem conserva su nombre.
            $table->dropForeign(['ingredient_id']);
            $table->unsignedBigInteger('ingredient_id')->nullable()->change();
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shopping_list_items', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->unsignedBigInteger('ingredient_id')->nullable(false)->change();
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients')
                ->cascadeOnDelete();
            $table->dropColumn('name');
        });
    }
};
