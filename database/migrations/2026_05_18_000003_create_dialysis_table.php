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
        Schema::create('dialysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_paciente')->constrained('patients')->cascadeOnDelete();
            $table->decimal('peso', 6, 2)->nullable();
            $table->enum('diagnostico_renal', ['LRA', 'ERC5', 'ERC5D']);
            $table->boolean('erc_previa')->default(false);
            $table->enum('erc_estadio', ['G1', 'G2', 'G3', 'G4', 'G5'])->nullable();
            $table->boolean('hd_cronica_previa')->default(false);
            $table->enum('etiologia', ['Sepsis', 'DM', 'HTA']);
            $table->string('comorbilidades')->nullable();
            $table->boolean('uci')->default(false);
            $table->boolean('vasopresores')->default(false);
            $table->boolean('ventilacion_mecanica')->default(false);
            $table->unsignedTinyInteger('sofa')->nullable();
            $table->enum('acceso_vascular', ['CVC temporal', 'FAV']);
            $table->string('tipo_cateter')->nullable();
            $table->enum('indicacion_hd', ['Hiperkalemia', 'Sobrecarga']);
            $table->unsignedInteger('numero_sesion');
            $table->decimal('horas_hd', 4, 2)->nullable();
            $table->unsignedInteger('ultrafiltracion_ml')->nullable();
            $table->string('anticoagulacion')->nullable();
            $table->boolean('hipotension_intradialisis')->default(false);
            $table->boolean('arritmias')->default(false);
            $table->string('complicaciones')->nullable();
            $table->decimal('urea_inicial', 8, 2)->nullable();
            $table->decimal('creatinina_inicial', 8, 2)->nullable();
            $table->decimal('potasio_inicial', 5, 2)->nullable();
            $table->decimal('sodio', 5, 2)->nullable();
            $table->decimal('bicarbonato', 5, 2)->nullable();
            $table->decimal('calcio', 5, 2)->nullable();
            $table->decimal('fosforo', 5, 2)->nullable();
            $table->decimal('ph', 4, 2)->nullable();
            $table->decimal('lactato', 5, 2)->nullable();
            $table->decimal('hemoglobina', 5, 2)->nullable();
            $table->decimal('leucocitos', 8, 2)->nullable();
            $table->decimal('plaquetas', 8, 2)->nullable();
            $table->decimal('albumina', 5, 2)->nullable();
            $table->decimal('pcr', 8, 2)->nullable();
            $table->unsignedInteger('diuresis_24h_ml')->nullable();
            $table->boolean('recuperacion_renal')->default(false);
            $table->boolean('hd_alta')->default(false);
            $table->unsignedSmallInteger('dias_hospitalizacion')->nullable();
            $table->enum('destino_final', ['ALTA', 'UCI', 'FALLECIDO']);
            $table->boolean('mortalidad_28')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['id_paciente', 'numero_sesion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialysis');
    }
};
