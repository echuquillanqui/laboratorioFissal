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
            $row->hd_cronica_previa ? 'Sí' : 'No',
            $row->acceso_vascular,
            $row->indicacion_hd,
            $row->urea_inicial,
            $row->creatinina_inicial,
            $row->potasio_inicial,
            $row->hemoglobina,
            $row->albumina,
            $row->vasopresores ? 'Sí' : 'No',
            $row->ventilacion_mecanica ? 'Sí' : 'No',
            $row->complicacion_hd,
            $row->destino,
            $row->created_at,
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Historia clínica', 'Paciente', 'DNI', 'Procedencia', 'Diagnóstico renal', 'Etiología', 'HD crónica previa', 'Acceso vascular', 'Indicación HD', 'Urea inicial', 'Creatinina inicial', 'Potasio inicial', 'Hemoglobina', 'Albúmina', 'Vasopresores', 'Ventilación mecánica', 'Complicación HD', 'Destino', 'Fecha'];
    }
}
