<?php

namespace App\Http\Controllers;

use App\Exports\ConsultsExport;
use App\Exports\DialysisExport;
use App\Exports\ReportsWorkbookExport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Throwable;

class PatientWorkflowController extends Controller
{
    public function exportReports(Request $request)
    {
        $mode = $request->query('mode', 'workbook');

        $consults = DB::table('consults')
            ->join('patients', 'patients.id', '=', 'consults.id_paciente')
            ->select(
                'consults.id',
                'patients.numero_historia',
                'patients.nombres_apellidos',
                'patients.dni',
                'consults.procedencia',
                'consults.diagnostico_renal',
                'consults.etiologia',
                'consults.hd_cronica_previa',
                'consults.acceso_vascular',
                'consults.indicacion_hd',
                'consults.urea_inicial',
                'consults.creatinina_inicial',
                'consults.potasio_inicial',
                'consults.hemoglobina',
                'consults.albumina',
                'consults.vasopresores',
                'consults.ventilacion_mecanica',
                'consults.complicacion_hd',
                'consults.destino',
                'consults.created_at'
            )
            ->orderByDesc('consults.created_at')
            ->get();

        $dialysis = DB::table('dialysis')
            ->join('patients', 'patients.id', '=', 'dialysis.id_paciente')
            ->select(
                'dialysis.id',
                'patients.numero_historia',
                'patients.nombres_apellidos',
                'patients.dni',
                'dialysis.peso',
                'dialysis.numero_sesion',
                'dialysis.diagnostico_renal',
                'dialysis.erc_previa',
                'dialysis.erc_estadio',
                'dialysis.hd_cronica_previa',
                'dialysis.etiologia',
                'dialysis.comorbilidades',
                'dialysis.uci',
                'dialysis.vasopresores',
                'dialysis.ventilacion_mecanica',
                'dialysis.sofa',
                'dialysis.acceso_vascular',
                'dialysis.tipo_cateter',
                'dialysis.indicacion_hd',
                'dialysis.horas_hd',
                'dialysis.ultrafiltracion_ml',
                'dialysis.anticoagulacion',
                'dialysis.hipotension_intradialisis',
                'dialysis.arritmias',
                'dialysis.complicaciones',
                'dialysis.urea_inicial',
                'dialysis.creatinina_inicial',
                'dialysis.potasio_inicial',
                'dialysis.sodio',
                'dialysis.bicarbonato',
                'dialysis.calcio',
                'dialysis.fosforo',
                'dialysis.ph',
                'dialysis.lactato',
                'dialysis.hemoglobina',
                'dialysis.leucocitos',
                'dialysis.plaquetas',
                'dialysis.albumina',
                'dialysis.pcr',
                'dialysis.diuresis_24h_ml',
                'dialysis.recuperacion_renal',
                'dialysis.hd_alta',
                'dialysis.dias_hospitalizacion',
                'dialysis.destino_final',
                'dialysis.mortalidad_28',
                'dialysis.observaciones',
                'dialysis.created_at'
            )
            ->orderByDesc('dialysis.created_at')
            ->get();

        if ($mode === 'separate') {
            return $this->exportSeparateCsvZip($consults, $dialysis);
        }

        return Excel::download(new ReportsWorkbookExport($consults, $dialysis), "reportes_consulta_hemodialisis.xlsx");
    }

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
            'numero_historia' => $this->nextClinicalHistoryNumber(),
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

    public function autocomplete(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        if ($search === '' || mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $patients = DB::table('patients')
            ->select('id', 'nombres_apellidos', 'dni', 'codigo_unico', 'numero_historia')
            ->where(function ($query) use ($search) {
                $query->where('nombres_apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('codigo_unico', 'like', "%{$search}%")
                    ->orWhere('numero_historia', 'like', "%{$search}%");
            })
            ->orderBy('nombres_apellidos')
            ->limit(10)
            ->get();

        return response()->json($patients);
    }


    public function search(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $page = max(1, (int) $request->query('page', 1));

        $query = DB::table('patients')
            ->select('id', 'nombres_apellidos', 'dni', 'codigo_unico', 'numero_historia', 'sexo', 'edad', 'fecha_nacimiento', 'direccion', 'telefono')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nombres_apellidos', 'like', "%{$search}%")
                        ->orWhere('dni', 'like', "%{$search}%")
                        ->orWhere('codigo_unico', 'like', "%{$search}%")
                        ->orWhere('numero_historia', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombres_apellidos');

        $patients = $query->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'results' => collect($patients->items())->map(fn ($patient) => [
                'id' => $patient->id,
                'text' => "{$patient->numero_historia} · {$patient->dni} · {$patient->nombres_apellidos}",
                'dni' => $patient->dni,
                'numero_historia' => $patient->numero_historia,
                'nombres_apellidos' => $patient->nombres_apellidos,
            ]),
            'pagination' => ['more' => $patients->hasMorePages()],
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
                ->select('patients.id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso', 'regimen')
                ->selectSub(fn ($query) => $query
                    ->from('consults')
                    ->selectRaw('count(*)')
                    ->whereColumn('consults.id_paciente', 'patients.id'), 'consultas_count')
                ->selectSub(fn ($query) => $query
                    ->from('dialysis')
                    ->selectRaw('count(*)')
                    ->whereColumn('dialysis.id_paciente', 'patients.id'), 'dialisis_count')
                ->selectSub(fn ($query) => $query
                    ->from('hemodialysis_sessions')
                    ->selectRaw('count(*)')
                    ->whereColumn('hemodialysis_sessions.patient_id', 'patients.id'), 'hemodialisis_count')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('nombres_apellidos', 'like', "%{$search}%")
                            ->orWhere('dni', 'like', "%{$search}%")
                            ->orWhere('numero_historia', 'like', "%{$search}%")
                            ->orWhere('codigo_unico', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString()
                ->through(fn ($patient) => array_merge((array) $patient, [
                    'can_delete' => ((int) $patient->consultas_count === 0 && (int) $patient->dialisis_count === 0 && (int) ($patient->hemodialisis_count ?? 0) === 0),
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
                    ->select('id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso', 'fecha_nacimiento', 'direccion', 'telefono', 'regimen')
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
            'fecha_nacimiento' => ['nullable', 'date'],
            'edad' => ['required', 'integer', 'min:0', 'max:120'],
            'sexo' => ['required', Rule::in(['M', 'F'])],
            'codigo_unico' => ['required', 'string', 'max:7', Rule::unique('patients', 'codigo_unico')->ignore($patient)],
            'numero_sesion' => ['required', 'integer', 'min:0'],
            'regimen' => ['nullable', Rule::in(['SIS', 'ESSALUD', 'SALUDPOL', 'PARTICULAR', 'OTROS'])],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'numero_historia' => [$patient === null ? 'nullable' : 'required', 'string', 'max:255', Rule::unique('patients', 'numero_historia')->ignore($patient)],
        ]);
    }



    private function nextClinicalHistoryNumber(): string
    {
        $lastPatient = DB::table('patients')
            ->select('numero_historia')
            ->where('numero_historia', 'like', 'HC-%')
            ->orderByDesc('id')
            ->first();

        if ($lastPatient === null || ! preg_match('/^HC-(\d{6})$/', (string) $lastPatient->numero_historia, $matches)) {
            return 'HC-000001';
        }

        return 'HC-'.str_pad((string) (((int) $matches[1]) + 1), 6, '0', STR_PAD_LEFT);
    }

    private function hasClinicalRecords(int $patient): bool
    {
        return DB::table('consults')->where('id_paciente', $patient)->exists()
            || DB::table('dialysis')->where('id_paciente', $patient)->exists()
            || DB::table('hemodialysis_sessions')->where('patient_id', $patient)->exists()
            || DB::table('hemodialysis_admissions')->where('patient_id', $patient)->exists()
            || DB::table('hemodialysis_medical_evaluations')->where('patient_id', $patient)->exists()
            || DB::table('hemodialysis_nursing_notes')->where('patient_id', $patient)->exists()
            || DB::table('hemodialysis_laboratory_monitors')->where('patient_id', $patient)->exists();
    }

    private function emptyPatient(): array
    {
        return [
            'nombres_apellidos' => '',
            'dni' => '',
            'fecha_ingreso' => now()->toDateString(),
            'fecha_nacimiento' => null,
            'edad' => '',
            'sexo' => 'F',
            'codigo_unico' => '',
            'numero_sesion' => 0,
            'numero_historia' => '',
            'regimen' => null,
            'direccion' => '',
            'telefono' => '',
        ];
    }

    private function exportSeparateCsvZip($consults, $dialysis): Response
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'reportes_');
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString(
            'consultas.csv',
            Excel::raw(new ConsultsExport($consults), ExcelFormat::CSV)
        );

        $zip->addFromString(
            'hemodialisis.csv',
            Excel::raw(new DialysisExport($dialysis), ExcelFormat::CSV)
        );

        $zip->close();

        return response()->download($zipPath, 'reportes_consulta_hemodialisis.zip')->deleteFileAfterSend(true);
    }

    private function samplePatientsPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = trim((string) $request->query('buscar', ''));

        $patients = collect($this->samplePatients())
            ->when($search !== '', fn ($items) => $items->filter(function ($patient) use ($search) {
                return str_contains(mb_strtolower($patient['nombres_apellidos']), mb_strtolower($search))
                    || str_contains($patient['dni'], $search)
                    || str_contains($patient['numero_historia'], $search)
                    || str_contains(mb_strtolower($patient['codigo_unico']), mb_strtolower($search));
            }))
            ->sortByDesc('id')
            ->values();
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
                'regimen' => 'SIS',
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
                'regimen' => 'ESSALUD',
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
