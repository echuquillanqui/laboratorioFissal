<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DialysisExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $dialysis) {}

    public function collection(): Collection
    {
        return $this->dialysis->map(fn ($row) => [
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
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Historia clínica', 'Paciente', 'DNI', 'N° sesión', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino final', 'Fecha'];
    }
}
