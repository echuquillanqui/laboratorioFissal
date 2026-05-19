<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratory_tests', function (Blueprint $table) {
            $table->decimal('valor_minimo_m', 10, 3)->nullable()->after('valor_maximo');
            $table->decimal('valor_maximo_m', 10, 3)->nullable()->after('valor_minimo_m');
            $table->decimal('valor_minimo_f', 10, 3)->nullable()->after('valor_maximo_m');
            $table->decimal('valor_maximo_f', 10, 3)->nullable()->after('valor_minimo_f');
        });
    }

    public function down(): void
    {
        Schema::table('laboratory_tests', function (Blueprint $table) {
            $table->dropColumn(['valor_minimo_m', 'valor_maximo_m', 'valor_minimo_f', 'valor_maximo_f']);
        });
    }
};
