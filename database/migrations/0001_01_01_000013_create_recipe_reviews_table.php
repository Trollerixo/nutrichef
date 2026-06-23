<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1–5
            $table->text('comment')->nullable();
            $table->boolean('flagged')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'recipe_id']); // Un review por usuario por receta
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_reviews');
    }
};
