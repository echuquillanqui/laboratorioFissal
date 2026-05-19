<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('regimen', ['SIS', 'ESSALUD', 'SALUDPOL', 'PARTICULAR', 'OTROS'])->nullable()->after('numero_sesion');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('regimen');
        });
    }
};
