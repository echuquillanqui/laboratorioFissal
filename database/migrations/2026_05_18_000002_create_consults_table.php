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
        Schema::create('consults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_paciente')->constrained('patients')->cascadeOnDelete();
            $table->enum('procedencia', ['Emergencia', 'UCI', 'Medicina']);
            $table->enum('diagnostico_renal', ['LRA', 'ERC5', 'ERC5D']);
            $table->enum('etiologia', ['Sepsis', 'DM', 'HTA']);
            $table->boolean('hd_cronica_previa')->default(false);
            $table->enum('acceso_vascular', ['CVC temporal', 'FAV']);
            $table->enum('indicacion_hd', ['Hiperkalemia', 'Sobrecarga']);
            $table->decimal('urea_inicial', 8, 2)->nullable();
            $table->decimal('creatinina_inicial', 8, 2)->nullable();
            $table->decimal('potasio_inicial', 5, 2)->nullable();
            $table->decimal('hemoglobina', 5, 2)->nullable();
            $table->decimal('albumina', 5, 2)->nullable();
            $table->boolean('vasopresores')->default(false);
            $table->boolean('ventilacion_mecanica')->default(false);
            $table->string('complicacion_hd')->nullable();
            $table->enum('destino', ['ALTA', 'UCI', 'FALLECIDO']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consults');
    }
};
