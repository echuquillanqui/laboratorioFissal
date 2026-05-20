<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PatientWorkflowController extends Controller
{
    public function exportReports(Request $request): Response
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
                'consults.acceso_vascular',
                'consults.indicacion_hd',
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
                'dialysis.numero_sesion',
                'dialysis.diagnostico_renal',
                'dialysis.etiologia',
                'dialysis.acceso_vascular',
                'dialysis.indicacion_hd',
                'dialysis.destino_final',
                'dialysis.created_at'
            )
            ->orderByDesc('dialysis.created_at')
            ->get();

        if ($mode === 'separate') {
            return $this->exportSeparateCsvZip($consults->all(), $dialysis->all());
        }

        return $this->exportWorkbookXml($consults->all(), $dialysis->all());
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
                    ->select('id', 'nombres_apellidos', 'dni', 'edad', 'sexo', 'codigo_unico', 'numero_historia', 'numero_sesion', 'fecha_ingreso', 'regimen')
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
            'regimen' => ['nullable', Rule::in(['SIS', 'ESSALUD', 'SALUDPOL', 'PARTICULAR', 'OTROS'])],
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
            'regimen' => null,
        ];
    }

    private function exportWorkbookXml(array $consults, array $dialysis): Response
    {
        $headersConsults = ['ID', 'Historia clínica', 'Paciente', 'DNI', 'Procedencia', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino', 'Fecha'];
        $headersDialysis = ['ID', 'Historia clínica', 'Paciente', 'DNI', 'N° sesión', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino final', 'Fecha'];

        $xml = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?>'
            .'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            .$this->xmlWorksheet('Consultas', $headersConsults, array_map(fn ($row) => [
                $row->id,
                $row->numero_historia,
                $row->nombres_apellidos,
                $row->dni,
                $row->procedencia,
                $row->diagnostico_renal,
                $row->etiologia,
                $row->acceso_vascular,
                $row->indicacion_hd,
                $row->destino,
                $row->created_at,
            ], $consults))
            .$this->xmlWorksheet('Hemodialisis', $headersDialysis, array_map(fn ($row) => [
                $row->id,
                $row->numero_historia,
                $row->nombres_apellidos,
                $row->dni,
                $row->numero_sesion,
                $row->diagnostico_renal,
                $row->etiologia,
                $row->acceso_vascular,
                $row->indicacion_hd,
                $row->destino_final,
                $row->created_at,
            ], $dialysis))
            .'</Workbook>';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reportes_consulta_hemodialisis.xml"',
        ]);
    }

    private function xmlWorksheet(string $name, array $headers, array $rows): string
    {
        $sheet = '<Worksheet ss:Name="'.$this->xmlEscape($name).'"><Table>';
        $sheet .= '<Row>';
        foreach ($headers as $header) {
            $sheet .= '<Cell><Data ss:Type="String">'.$this->xmlEscape($header).'</Data></Cell>';
        }
        $sheet .= '</Row>';

        foreach ($rows as $row) {
            $sheet .= '<Row>';
            foreach ($row as $value) {
                $sheet .= '<Cell><Data ss:Type="String">'.$this->xmlEscape((string) $value).'</Data></Cell>';
            }
            $sheet .= '</Row>';
        }

        return $sheet.'</Table></Worksheet>';
    }

    private function exportSeparateCsvZip(array $consults, array $dialysis): Response
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'reportes_');
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('consultas.csv', $this->toCsv([
            ['ID', 'Historia clínica', 'Paciente', 'DNI', 'Procedencia', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino', 'Fecha'],
            ...array_map(fn ($row) => [
                $row->id,
                $row->numero_historia,
                $row->nombres_apellidos,
                $row->dni,
                $row->procedencia,
                $row->diagnostico_renal,
                $row->etiologia,
                $row->acceso_vascular,
                $row->indicacion_hd,
                $row->destino,
                $row->created_at,
            ], $consults),
        ]));

        $zip->addFromString('hemodialisis.csv', $this->toCsv([
            ['ID', 'Historia clínica', 'Paciente', 'DNI', 'N° sesión', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino final', 'Fecha'],
            ...array_map(fn ($row) => [
                $row->id,
                $row->numero_historia,
                $row->nombres_apellidos,
                $row->dni,
                $row->numero_sesion,
                $row->diagnostico_renal,
                $row->etiologia,
                $row->acceso_vascular,
                $row->indicacion_hd,
                $row->destino_final,
                $row->created_at,
            ], $dialysis),
        ]));

        $zip->close();

        return response()->download($zipPath, 'reportes_consulta_hemodialisis.zip')->deleteFileAfterSend(true);
    }

    private function toCsv(array $rows): string
    {
        $stream = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $csv = stream_get_contents($stream) ?: '';
        fclose($stream);

        return $csv;
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
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
