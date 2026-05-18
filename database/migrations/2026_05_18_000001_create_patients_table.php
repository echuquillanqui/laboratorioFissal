<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nombres_apellidos');
            $table->string('dni', 8)->unique();
            $table->date('fecha_ingreso');
            $table->unsignedTinyInteger('edad');
            $table->enum('sexo', ['M', 'F']);
            $table->string('codigo_unico', 7)->unique();
            $table->unsignedInteger('numero_sesion')->default(0);
            $table->string('numero_historia')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
