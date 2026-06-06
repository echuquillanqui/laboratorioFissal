<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hemodialysis_laboratory_monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('hemodialysis_session_id')->nullable();
            $table->foreignId('laboratory_order_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->date('fecha_muestra');
            $table->text('observacion')->nullable();
            $table->string('estado', 30)->default('borrador')->index('hd_lab_monitor_estado_idx');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id', 'hd_lab_monitor_patient_fk')->references('id')->on('patients')->cascadeOnDelete();
            $table->foreign('hemodialysis_session_id', 'hd_lab_monitor_session_fk')->references('id')->on('hemodialysis_sessions')->nullOnDelete();
            $table->foreign('laboratory_order_id', 'hd_lab_monitor_order_fk')->references('id')->on('laboratory_orders')->nullOnDelete();
            $table->foreign('created_by', 'hd_lab_monitor_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->index(['patient_id', 'fecha_muestra'], 'hd_lab_monitor_patient_fecha_idx');
        });

        Schema::create('hemodialysis_laboratory_monitor_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hemodialysis_laboratory_monitor_id');
            $table->foreignId('laboratory_test_id')->nullable();
            $table->string('nombre_prueba', 150);
            $table->string('valor', 120)->nullable();
            $table->string('unidad', 40)->nullable();
            $table->string('valor_referencia', 120)->nullable();
            $table->boolean('alerta')->default(false);
            $table->timestamps();

            $table->foreign('hemodialysis_laboratory_monitor_id', 'hd_lab_result_monitor_fk')->references('id')->on('hemodialysis_laboratory_monitors')->cascadeOnDelete();
            $table->foreign('laboratory_test_id', 'hd_lab_result_test_fk')->references('id')->on('laboratory_tests')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hemodialysis_laboratory_monitor_results');
        Schema::dropIfExists('hemodialysis_laboratory_monitors');
    }
};
