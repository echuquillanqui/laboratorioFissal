<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hemodialysis_medical_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('hemodialysis_admission_id')->nullable()->constrained('hemodialysis_admissions')->nullOnDelete();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('fecha_evaluacion');
            $table->text('motivo_ingreso')->nullable();
            $table->text('examen_fisico')->nullable();
            $table->text('diagnosticos')->nullable();
            $table->text('plan_tratamiento')->nullable();
            $table->text('riesgos')->nullable();
            $table->text('indicaciones_medicas')->nullable();
            $table->string('estado', 30)->default('borrador')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'fecha_evaluacion'], 'hd_med_eval_patient_fecha_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_medical_evaluations');
    }
};
