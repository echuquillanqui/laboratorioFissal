<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laboratory_monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('hemodialysis_session_id')->nullable()->constrained('hemodialysis_sessions')->nullOnDelete();
            $table->foreignId('laboratory_order_id')->nullable()->constrained('laboratory_orders')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha_muestra');
            $table->text('observacion')->nullable();
            $table->string('estado', 30)->default('borrador')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'fecha_muestra']);
        });

        Schema::create('laboratory_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_monitor_id')->constrained('laboratory_monitors')->cascadeOnDelete();
            $table->foreignId('laboratory_test_id')->nullable()->constrained('laboratory_tests')->nullOnDelete();
            $table->string('nombre_prueba', 150);
            $table->string('valor', 120)->nullable();
            $table->string('unidad', 40)->nullable();
            $table->string('valor_referencia', 120)->nullable();
            $table->boolean('alerta')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratory_results');
        Schema::dropIfExists('laboratory_monitors');
    }
};
