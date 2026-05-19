<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laboratory_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_order_item_id')->constrained('laboratory_order_items')->cascadeOnDelete();
            $table->string('resultado_texto', 255)->nullable();
            $table->decimal('resultado_numerico', 10, 2)->nullable();
            $table->string('resultado_opcion', 120)->nullable();
            $table->text('observacion')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('users');
            $table->timestamp('fecha_validacion')->nullable();
            $table->string('estado', 20)->default('pendiente')->index();
            $table->timestamps();
            $table->unique('laboratory_order_item_id');
        });

        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->morphs('auditable');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('event', 20);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
        Schema::dropIfExists('laboratory_results');
    }
};
