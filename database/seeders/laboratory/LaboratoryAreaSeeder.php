<?php

namespace Database\Seeders\Laboratory;

use App\Models\Laboratory\LaboratoryArea;
use Illuminate\Database\Seeder;

class LaboratoryAreaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Hematología', 'Bioquímica', 'Inmunología', 'Microbiología', 'Parasitología', 'Uroanálisis'] as $name) {
            LaboratoryArea::updateOrCreate(['nombre' => $name], ['estado' => true]);
        }
    }
}
