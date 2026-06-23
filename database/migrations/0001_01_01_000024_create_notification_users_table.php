<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Pivot: registra qué usuarios recibieron cada notificación del sistema y si la leyeron.
        Schema::create("notification_users", function (Blueprint $table) {
            $table
                ->foreignId("notification_id")
                ->constrained("system_notifications")
                ->cascadeOnDelete();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->boolean("read")->default(false);
            $table->timestamp("read_at")->nullable();
            $table->primary(["notification_id", "user_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("notification_users");
    }
};
