<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla de notificaciones del sistema enviadas por el admin.
        // Se nombra "system_notifications" para no colisionar con la tabla
        // "notifications" polimórfica que genera el canal database de Laravel.
        Schema::create("system_notifications", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("sent_by")
                ->constrained("users")
                ->cascadeOnDelete();
            $table->string("title");
            $table->text("message");
            $table->enum("target", ["all", "specific"])->default("all");
            $table->timestamp("sent_at")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("system_notifications");
    }
};
