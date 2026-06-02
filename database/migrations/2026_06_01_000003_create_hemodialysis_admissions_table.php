<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hemodialysis_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_ingreso_hd');
            $table->string('procedencia', 80)->nullable();
            $table->string('diagnostico_renal', 80)->nullable();
            $table->string('etiologia', 120)->nullable();
            $table->text('antecedentes')->nullable();
            $table->text('comorbilidades')->nullable();
            $table->string('acceso_vascular_inicial', 120)->nullable();
            $table->string('indicacion_hd', 160)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 30)->default('borrador')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'fecha_ingreso_hd']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_admissions');
    }
};
