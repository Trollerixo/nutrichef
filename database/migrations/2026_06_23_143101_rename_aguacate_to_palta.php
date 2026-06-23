<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ingredients')
            ->where('name', 'Aguacate')
            ->update(['name' => 'Palta']);

        DB::table('recipes')
            ->where('title', 'Ensalada de Quinoa y Aguacate')
            ->update(['title' => 'Ensalada de Quinoa y Palta']);

        DB::table('shopping_list_items')
            ->where('name', 'Aguacate')
            ->update(['name' => 'Palta']);
    }

    public function down(): void
    {
        DB::table('ingredients')
            ->where('name', 'Palta')
            ->update(['name' => 'Aguacate']);

        DB::table('recipes')
            ->where('title', 'Ensalada de Quinoa y Palta')
            ->update(['title' => 'Ensalada de Quinoa y Aguacate']);

        DB::table('shopping_list_items')
            ->where('name', 'Palta')
            ->update(['name' => 'Aguacate']);
    }
};
