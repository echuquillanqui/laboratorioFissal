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
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('hemodialysis_session_id')->nullable()->constrained('hemodialysis_sessions')->nullOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('fecha_nota');
            $table->text('subjetivo')->nullable();
            $table->text('objetivo')->nullable();
            $table->text('analisis')->nullable();
            $table->text('plan')->nullable();
            $table->text('intervencion')->nullable();
            $table->text('evaluacion')->nullable();
            $table->string('estado', 30)->default('borrador')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'fecha_nota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_nursing_notes');
    }
};
