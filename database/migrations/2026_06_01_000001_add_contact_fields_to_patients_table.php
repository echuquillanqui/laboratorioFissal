<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('fecha_ingreso');
            $table->string('direccion')->nullable()->after('regimen');
            $table->string('telefono', 30)->nullable()->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['fecha_nacimiento', 'direccion', 'telefono']);
        });
    }
};
