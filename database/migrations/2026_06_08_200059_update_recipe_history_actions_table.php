<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::connection(null)->getConnection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE recipe_history DROP CONSTRAINT IF EXISTS recipe_history_action_check");
            DB::statement("ALTER TABLE recipe_history ADD CONSTRAINT recipe_history_action_check CHECK (action IN ('viewed', 'prepared', 'favorited', 'added_to_menu', 'added_to_shopping_list'))");
        } else {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection(null)->getConnection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE recipe_history DROP CONSTRAINT IF EXISTS recipe_history_action_check");
            DB::statement("ALTER TABLE recipe_history ADD CONSTRAINT recipe_history_action_check CHECK (action IN ('viewed', 'prepared'))");
        } else {
            Schema::table("recipe_history", function (Blueprint $table) {
                $table->enum("action", ["viewed", "prepared"])->change();
            });
        }
    }
};
