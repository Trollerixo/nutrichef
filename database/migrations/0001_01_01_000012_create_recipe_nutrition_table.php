<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // recipe_id es la PK (relación 1:1 con recipes)
        Schema::create('recipe_nutrition', function (Blueprint $table) {
            $table->foreignId('recipe_id')->primary()->constrained()->cascadeOnDelete();
            $table->float('proteins_g')->nullable();
            $table->float('carbs_g')->nullable();
            $table->float('fats_g')->nullable();
            $table->float('fiber_g')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_nutrition');
    }
};
