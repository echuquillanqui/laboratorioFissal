<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Throwable;

class PatientWorkflowController extends Controller
{
    public function index()
    {
        return view('patients.index', [
            'patients' => $this->patients(),
        ]);
    }

    public function createConsult(?int $patient = null)
    {
        return view('consults.create', [
            'patient' => $this->patient($patient),
        ]);
    }

    public function createDialysis(?int $patient = null)
    {
        return view('dialysis.create', [
            'patient' => $this->patient($patient),
        ]);
    }

    private function patients(): array
    {
        try {
            $patients = DB::table('patients')
                ->select('id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso')
                ->orderBy('nombres_apellidos')
                ->limit(8)
                ->get()
                ->map(fn ($patient) => (array) $patient)
                ->all();

            if ($patients !== []) {
                return $patients;
            }
        } catch (Throwable) {
            // The layout remains available before migrations/seeds are executed.
        }

        return $this->samplePatients();
    }

    private function patient(?int $id): array
    {
        if ($id !== null) {
            try {
                $patient = DB::table('patients')
                    ->select('id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso')
                    ->where('id', $id)
                    ->first();

                if ($patient !== null) {
                    return (array) $patient;
                }
            } catch (Throwable) {
                // Fallback data keeps the mockup navigable in fresh environments.
            }
        }

        return $this->samplePatients()[0];
    }

    private function samplePatients(): array
    {
        return [
            [
                'id' => 1,
                'nombres_apellidos' => 'Luis Alberto Mendoza Rojas',
                'dni' => '70000001',
                'edad' => 62,
                'sexo' => 'M',
                'codigo_unico' => 'HD-0001',
                'numero_historia' => 'HC-000001',
                'numero_sesion' => 4,
                'fecha_ingreso' => '2026-01-05',
            ],
            [
                'id' => 2,
                'nombres_apellidos' => 'María Elena Quispe Huamán',
                'dni' => '70000002',
                'edad' => 55,
                'sexo' => 'F',
                'codigo_unico' => 'HD-0002',
                'numero_historia' => 'HC-000002',
                'numero_sesion' => 2,
                'fecha_ingreso' => '2026-01-08',
            ],
            [
                'id' => 3,
                'nombres_apellidos' => 'Carlos Eduardo Torres Salazar',
                'dni' => '70000003',
                'edad' => 48,
                'sexo' => 'M',
                'codigo_unico' => 'HD-0003',
                'numero_historia' => 'HC-000003',
                'numero_sesion' => 1,
                'fecha_ingreso' => '2026-01-12',
            ],
        ];
    }
}
