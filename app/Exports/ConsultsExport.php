<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConsultsExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $consults) {}

    public function collection(): Collection
    {
        return $this->consults->map(fn ($row) => [
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
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Historia clínica', 'Paciente', 'DNI', 'Procedencia', 'Diagnóstico renal', 'Etiología', 'Acceso vascular', 'Indicación HD', 'Destino', 'Fecha'];
    }
}
