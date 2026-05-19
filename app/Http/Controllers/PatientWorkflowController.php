<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PatientWorkflowController extends Controller
{
    public function index(Request $request)
    {
        return view('patients.index', [
            'patients' => $this->patients($request),
        ]);
    }

    public function create()
    {
        return view('patients.form', [
            'patient' => $this->emptyPatient(),
            'isEditing' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        DB::table('patients')->insert(array_merge($this->validatedPatient($request), [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return redirect()
            ->route('patients.index')
            ->with('status', 'Paciente registrado correctamente.');
    }

    public function edit(int $patient)
    {
        return view('patients.form', [
            'patient' => $this->patient($patient),
            'isEditing' => true,
        ]);
    }

    public function update(Request $request, int $patient): RedirectResponse
    {
        DB::table('patients')
            ->where('id', $patient)
            ->update(array_merge($this->validatedPatient($request, $patient), [
                'updated_at' => now(),
            ]));

        return redirect()
            ->route('patients.index')
            ->with('status', 'Paciente actualizado correctamente.');
    }

    public function destroy(int $patient): RedirectResponse
    {
        if ($this->hasClinicalRecords($patient)) {
            return redirect()
                ->route('patients.index')
                ->with('error', 'No se puede eliminar el paciente porque tiene consultas o diálisis registradas.');
        }

        DB::table('patients')->where('id', $patient)->delete();

        return redirect()
            ->route('patients.index')
            ->with('status', 'Paciente eliminado correctamente.');
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

    private function patients(Request $request): LengthAwarePaginator
    {
        $search = trim((string) $request->query('buscar', ''));
        $perPage = 6;

        try {
            return DB::table('patients')
                ->select('patients.id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso')
                ->selectSub(fn ($query) => $query
                    ->from('consults')
                    ->selectRaw('count(*)')
                    ->whereColumn('consults.id_paciente', 'patients.id'), 'consultas_count')
                ->selectSub(fn ($query) => $query
                    ->from('dialysis')
                    ->selectRaw('count(*)')
                    ->whereColumn('dialysis.id_paciente', 'patients.id'), 'dialisis_count')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('nombres_apellidos', 'like', "%{$search}%")
                            ->orWhere('dni', 'like', "%{$search}%")
                            ->orWhere('numero_historia', 'like', "%{$search}%")
                            ->orWhere('codigo_unico', 'like', "%{$search}%");
                    });
                })
                ->orderBy('nombres_apellidos')
                ->paginate($perPage)
                ->withQueryString()
                ->through(fn ($patient) => array_merge((array) $patient, [
                    'can_delete' => ((int) $patient->consultas_count === 0 && (int) $patient->dialisis_count === 0),
                ]));
        } catch (Throwable) {
            return $this->samplePatientsPaginator($request, $perPage);
        }
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

    private function validatedPatient(Request $request, ?int $patient = null): array
    {
        return $request->validate([
            'nombres_apellidos' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'digits:8', Rule::unique('patients', 'dni')->ignore($patient)],
            'fecha_ingreso' => ['required', 'date'],
            'edad' => ['required', 'integer', 'min:0', 'max:120'],
            'sexo' => ['required', Rule::in(['M', 'F'])],
            'codigo_unico' => ['required', 'string', 'max:7', Rule::unique('patients', 'codigo_unico')->ignore($patient)],
            'numero_sesion' => ['required', 'integer', 'min:0'],
            'numero_historia' => ['required', 'string', 'max:255', Rule::unique('patients', 'numero_historia')->ignore($patient)],
        ]);
    }

    private function hasClinicalRecords(int $patient): bool
    {
        return DB::table('consults')->where('id_paciente', $patient)->exists()
            || DB::table('dialysis')->where('id_paciente', $patient)->exists();
    }

    private function emptyPatient(): array
    {
        return [
            'nombres_apellidos' => '',
            'dni' => '',
            'fecha_ingreso' => now()->toDateString(),
            'edad' => '',
            'sexo' => 'F',
            'codigo_unico' => '',
            'numero_sesion' => 0,
            'numero_historia' => '',
        ];
    }

    private function samplePatientsPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $patients = collect($this->samplePatients());
        $page = Paginator::resolveCurrentPage();

        return new Paginator(
            $patients->forPage($page, $perPage)->values(),
            $patients->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
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
                'consultas_count' => 0,
                'dialisis_count' => 0,
                'can_delete' => true,
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
                'consultas_count' => 1,
                'dialisis_count' => 0,
                'can_delete' => false,
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
                'consultas_count' => 0,
                'dialisis_count' => 1,
                'can_delete' => false,
            ],
        ];
    }
}
