<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('weekly_menus', 'active')) {
            Schema::table('weekly_menus', function (Blueprint $table) {
                $table->boolean('active')->default(false)->after('status');
            });
        }

        Schema::table('menu_slots', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['recipe_id']);
            $table->dropUnique(['menu_id', 'slot_date', 'meal_type']);
            $table->index('menu_id');
            $table->index('recipe_id');
            $table->foreign('menu_id')->references('id')->on('weekly_menus')->cascadeOnDelete();
            $table->foreign('recipe_id')->references('id')->on('recipes')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE menu_slots MODIFY COLUMN meal_type ENUM('desayuno', 'almuerzo', 'cena', 'postre', 'piqueo') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('menu_slots', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['recipe_id']);
            $table->dropIndex(['menu_id']);
            $table->dropIndex(['recipe_id']);
            $table->unique(['menu_id', 'slot_date', 'meal_type']);
            $table->foreign('menu_id')->references('id')->on('weekly_menus')->cascadeOnDelete();
            $table->foreign('recipe_id')->references('id')->on('recipes')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE menu_slots MODIFY COLUMN meal_type ENUM('desayuno', 'almuerzo', 'cena') NOT NULL");

        Schema::table('weekly_menus', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
