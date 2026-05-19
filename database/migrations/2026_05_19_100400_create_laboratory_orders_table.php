<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laboratory_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('user_id')->constrained('users');
            $table->date('fecha_orden')->index();
            $table->string('estado', 20)->default('pendiente')->index();
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'fecha_orden']);
        });

        Schema::create('laboratory_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_order_id')->constrained('laboratory_orders')->cascadeOnDelete();
            $table->foreignId('laboratory_test_id')->constrained('laboratory_tests');
            $table->string('origen', 20);
            $table->unique(['laboratory_order_id', 'laboratory_test_id'], 'uniq_order_test');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratory_order_items');
        Schema::dropIfExists('laboratory_orders');
    }
};
