<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hemodialysis_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('hemodialysis_admission_id')->nullable()->constrained('hemodialysis_admissions')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('numero_sesion');
            $table->date('fecha_sesion');
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->decimal('peso_pre', 6, 2)->nullable();
            $table->decimal('peso_post', 6, 2)->nullable();
            $table->string('acceso_vascular', 120)->nullable();
            $table->string('tipo_cateter', 120)->nullable();
            $table->decimal('horas_hd', 4, 2)->nullable();
            $table->unsignedInteger('ultrafiltracion_ml')->nullable();
            $table->string('anticoagulacion', 120)->nullable();
            $table->string('flujo_sanguineo', 60)->nullable();
            $table->string('flujo_dializado', 60)->nullable();
            $table->string('dializador', 120)->nullable();
            $table->boolean('hipotension_intradialisis')->default(false);
            $table->boolean('arritmias')->default(false);
            $table->text('complicaciones')->nullable();
            $table->text('prescripcion_medica')->nullable();
            $table->text('tolerancia')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 30)->default('borrador')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['patient_id', 'numero_sesion'], 'uniq_hd_session_patient_number');
            $table->index(['patient_id', 'fecha_sesion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_sessions');
    }
};
