<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laboratory_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('laboratory_profile_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratory_profile_id')->constrained('laboratory_profiles')->cascadeOnDelete();
            $table->foreignId('laboratory_test_id')->constrained('laboratory_tests');
            $table->unique(['laboratory_profile_id', 'laboratory_test_id'], 'uniq_profile_test');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laboratory_profile_test');
        Schema::dropIfExists('laboratory_profiles');
    }
};
