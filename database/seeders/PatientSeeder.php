<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            ['nombres_apellidos' => 'Luis Alberto Mendoza Rojas', 'dni' => '70000001', 'fecha_ingreso' => '2026-01-05', 'edad' => 62, 'sexo' => 'M', 'codigo_unico' => 'HD-0001', 'numero_historia' => 'HC-000001'],
            ['nombres_apellidos' => 'María Elena Quispe Huamán', 'dni' => '70000002', 'fecha_ingreso' => '2026-01-08', 'edad' => 55, 'sexo' => 'F', 'codigo_unico' => 'HD-0002', 'numero_historia' => 'HC-000002'],
            ['nombres_apellidos' => 'Carlos Eduardo Torres Salazar', 'dni' => '70000003', 'fecha_ingreso' => '2026-01-12', 'edad' => 48, 'sexo' => 'M', 'codigo_unico' => 'HD-0003', 'numero_historia' => 'HC-000003'],
            ['nombres_apellidos' => 'Rosa Isabel Paredes Flores', 'dni' => '70000004', 'fecha_ingreso' => '2026-01-17', 'edad' => 71, 'sexo' => 'F', 'codigo_unico' => 'HD-0004', 'numero_historia' => 'HC-000004'],
            ['nombres_apellidos' => 'Jorge Luis Chávez Núñez', 'dni' => '70000005', 'fecha_ingreso' => '2026-01-20', 'edad' => 64, 'sexo' => 'M', 'codigo_unico' => 'HD-0005', 'numero_historia' => 'HC-000005'],
            ['nombres_apellidos' => 'Ana Cecilia Ramírez Castillo', 'dni' => '70000006', 'fecha_ingreso' => '2026-01-24', 'edad' => 39, 'sexo' => 'F', 'codigo_unico' => 'HD-0006', 'numero_historia' => 'HC-000006'],
            ['nombres_apellidos' => 'Miguel Ángel Soto Vargas', 'dni' => '70000007', 'fecha_ingreso' => '2026-01-29', 'edad' => 58, 'sexo' => 'M', 'codigo_unico' => 'HD-0007', 'numero_historia' => 'HC-000007'],
            ['nombres_apellidos' => 'Patricia del Carmen León Medina', 'dni' => '70000008', 'fecha_ingreso' => '2026-02-02', 'edad' => 67, 'sexo' => 'F', 'codigo_unico' => 'HD-0008', 'numero_historia' => 'HC-000008'],
            ['nombres_apellidos' => 'Ricardo Antonio Herrera Díaz', 'dni' => '70000009', 'fecha_ingreso' => '2026-02-07', 'edad' => 52, 'sexo' => 'M', 'codigo_unico' => 'HD-0009', 'numero_historia' => 'HC-000009'],
            ['nombres_apellidos' => 'Carmen Rosa Gutiérrez Campos', 'dni' => '70000010', 'fecha_ingreso' => '2026-02-11', 'edad' => 60, 'sexo' => 'F', 'codigo_unico' => 'HD-0010', 'numero_historia' => 'HC-000010'],
            ['nombres_apellidos' => 'Fernando José Aguilar Peña', 'dni' => '70000011', 'fecha_ingreso' => '2026-02-16', 'edad' => 46, 'sexo' => 'M', 'codigo_unico' => 'HD-0011', 'numero_historia' => 'HC-000011'],
            ['nombres_apellidos' => 'Lucía Fernanda Morales Reyes', 'dni' => '70000012', 'fecha_ingreso' => '2026-02-21', 'edad' => 35, 'sexo' => 'F', 'codigo_unico' => 'HD-0012', 'numero_historia' => 'HC-000012'],
            ['nombres_apellidos' => 'Oscar Martín Cárdenas Silva', 'dni' => '70000013', 'fecha_ingreso' => '2026-02-25', 'edad' => 73, 'sexo' => 'M', 'codigo_unico' => 'HD-0013', 'numero_historia' => 'HC-000013'],
            ['nombres_apellidos' => 'Teresa Margarita Vega Ponce', 'dni' => '70000014', 'fecha_ingreso' => '2026-03-01', 'edad' => 69, 'sexo' => 'F', 'codigo_unico' => 'HD-0014', 'numero_historia' => 'HC-000014'],
            ['nombres_apellidos' => 'Héctor Raúl Navarro Ríos', 'dni' => '70000015', 'fecha_ingreso' => '2026-03-05', 'edad' => 57, 'sexo' => 'M', 'codigo_unico' => 'HD-0015', 'numero_historia' => 'HC-000015'],
            ['nombres_apellidos' => 'Diana Carolina Espinoza Luna', 'dni' => '70000016', 'fecha_ingreso' => '2026-03-09', 'edad' => 42, 'sexo' => 'F', 'codigo_unico' => 'HD-0016', 'numero_historia' => 'HC-000016'],
            ['nombres_apellidos' => 'Víctor Manuel Rentería Molina', 'dni' => '70000017', 'fecha_ingreso' => '2026-03-14', 'edad' => 65, 'sexo' => 'M', 'codigo_unico' => 'HD-0017', 'numero_historia' => 'HC-000017'],
            ['nombres_apellidos' => 'Elena Beatriz Cabrera Ortiz', 'dni' => '70000018', 'fecha_ingreso' => '2026-03-18', 'edad' => 50, 'sexo' => 'F', 'codigo_unico' => 'HD-0018', 'numero_historia' => 'HC-000018'],
            ['nombres_apellidos' => 'Sergio Andrés Valverde Cruz', 'dni' => '70000019', 'fecha_ingreso' => '2026-03-23', 'edad' => 44, 'sexo' => 'M', 'codigo_unico' => 'HD-0019', 'numero_historia' => 'HC-000019'],
            ['nombres_apellidos' => 'Gloria Mercedes Saavedra Palma', 'dni' => '70000020', 'fecha_ingreso' => '2026-03-27', 'edad' => 76, 'sexo' => 'F', 'codigo_unico' => 'HD-0020', 'numero_historia' => 'HC-000020'],
            ['nombres_apellidos' => 'Alberto Enrique Fuentes León', 'dni' => '70000021', 'fecha_ingreso' => '2026-04-01', 'edad' => 61, 'sexo' => 'M', 'codigo_unico' => 'HD-0021', 'numero_historia' => 'HC-000021'],
            ['nombres_apellidos' => 'Natalia Sofía Mendoza Vargas', 'dni' => '70000022', 'fecha_ingreso' => '2026-04-06', 'edad' => 33, 'sexo' => 'F', 'codigo_unico' => 'HD-0022', 'numero_historia' => 'HC-000022'],
            ['nombres_apellidos' => 'Eduardo Francisco Ibarra Meza', 'dni' => '70000023', 'fecha_ingreso' => '2026-04-10', 'edad' => 54, 'sexo' => 'M', 'codigo_unico' => 'HD-0023', 'numero_historia' => 'HC-000023'],
            ['nombres_apellidos' => 'Silvia Aurora Pizarro Benites', 'dni' => '70000024', 'fecha_ingreso' => '2026-04-15', 'edad' => 47, 'sexo' => 'F', 'codigo_unico' => 'HD-0024', 'numero_historia' => 'HC-000024'],
            ['nombres_apellidos' => 'Roberto Carlos Arias Zamora', 'dni' => '70000025', 'fecha_ingreso' => '2026-04-20', 'edad' => 59, 'sexo' => 'M', 'codigo_unico' => 'HD-0025', 'numero_historia' => 'HC-000025'],
        ];

        $now = now();

        foreach ($patients as $patient) {
            DB::table('patients')->updateOrInsert(
                ['dni' => $patient['dni']],
                array_merge($patient, [
                    'numero_sesion' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
            );
        }
    }
}
