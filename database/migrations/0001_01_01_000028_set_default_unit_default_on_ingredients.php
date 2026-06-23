<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            // La UI de quick-add solo pide el nombre; 'g' es la unidad más común
            // y permite insertar un ingrediente sin especificar la unidad explícitamente.
            $table->string('default_unit')->default('g')->change();
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('default_unit')->default(null)->change();
        });
    }
};
