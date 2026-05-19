<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laboratory_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_area_id')->constrained('laboratory_areas');
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 150)->index();
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 40)->nullable();
            $table->string('tipo_dato', 20)->index();
            $table->decimal('valor_minimo', 10, 2)->nullable();
            $table->decimal('valor_maximo', 10, 2)->nullable();
            $table->decimal('valor_alerta_minimo', 10, 2)->nullable();
            $table->decimal('valor_alerta_maximo', 10, 2)->nullable();
            $table->boolean('tiene_opciones')->default(false);
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['laboratory_area_id', 'estado']);
        });

        Schema::create('laboratory_test_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_test_id')->constrained('laboratory_tests')->cascadeOnDelete();
            $table->string('valor', 120);
            $table->string('etiqueta', 120);
            $table->unsignedInteger('orden')->default(1);
            $table->timestamps();
            $table->unique(['laboratory_test_id', 'valor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratory_test_options');
        Schema::dropIfExists('laboratory_tests');
    }
};
