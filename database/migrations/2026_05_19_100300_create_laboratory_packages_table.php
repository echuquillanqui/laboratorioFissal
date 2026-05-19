<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laboratory_packages', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('laboratory_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_package_id')->constrained('laboratory_packages')->cascadeOnDelete();
            $table->string('tipo_item', 20);
            $table->unsignedBigInteger('reference_id');
            $table->index(['tipo_item', 'reference_id']);
            $table->unique(['laboratory_package_id', 'tipo_item', 'reference_id'], 'uniq_package_item');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratory_package_items');
        Schema::dropIfExists('laboratory_packages');
    }
};
