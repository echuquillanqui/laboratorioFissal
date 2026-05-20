<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsWorkbookExport implements WithMultipleSheets
{
    public function __construct(
        private readonly Collection $consults,
        private readonly Collection $dialysis,
    ) {}

    public function sheets(): array
    {
        return [
            'Consultas' => new ConsultsExport($this->consults),
            'Hemodialisis' => new DialysisExport($this->dialysis),
        ];
    }
}
