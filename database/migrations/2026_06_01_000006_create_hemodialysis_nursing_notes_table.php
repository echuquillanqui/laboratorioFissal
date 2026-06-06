<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hemodialysis_nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('hemodialysis_session_id')->nullable();
            $table->foreignId('nurse_id')->nullable();
            $table->dateTime('fecha_nota');
            $table->text('subjetivo')->nullable();
            $table->text('objetivo')->nullable();
            $table->text('analisis')->nullable();
            $table->text('plan')->nullable();
            $table->text('intervencion')->nullable();
            $table->text('evaluacion')->nullable();
            $table->string('estado', 30)->default('borrador')->index('hd_nursing_estado_idx');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id', 'hd_nursing_patient_fk')->references('id')->on('patients')->cascadeOnDelete();
            $table->foreign('hemodialysis_session_id', 'hd_nursing_session_fk')->references('id')->on('hemodialysis_sessions')->nullOnDelete();
            $table->foreign('nurse_id', 'hd_nursing_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->index(['patient_id', 'fecha_nota'], 'hd_nursing_patient_fecha_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_nursing_notes');
    }
};
