<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('weekly_menus')->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->date('slot_date');
            $table->enum('meal_type', ['desayuno', 'almuerzo', 'cena']);
            $table->timestamps();
            // Una receta por turno: no dos desayunos el mismo día en el mismo menú
            $table->unique(['menu_id', 'slot_date', 'meal_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_slots');
    }
};
