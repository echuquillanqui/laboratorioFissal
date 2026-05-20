<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MigrationAuditExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Collection $rows) {}

    public function collection(): Collection
    {
        return $this->rows->map(fn (array $row) => [
            $row['archivo'],
            $row['tabla'],
            $row['columnas_detectadas'],
            $row['foreign_keys_detectadas'],
            $row['indices_detectados'],
            $row['soft_deletes'],
            $row['timestamps'],
            $row['tiene_down'],
            $row['estado'],
            $row['observaciones'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Archivo migración',
            'Tabla',
            'Columnas detectadas',
            'Foreign keys detectadas',
            'Índices detectados',
            'Soft deletes',
            'Timestamps',
            'Tiene down()',
            'Estado',
            'Observaciones',
        ];
    }
}
