<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("recipe_history", function (Blueprint $table) {
            $table
                ->enum("action", [
                    "viewed",
                    "prepared",
                    "favorited",
                    "added_to_menu",
                    "added_to_shopping_list",
                ])
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("recipe_history", function (Blueprint $table) {
            $table->enum("action", ["viewed", "prepared"])->change();
        });
    }
};
