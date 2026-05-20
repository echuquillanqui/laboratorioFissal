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
            $row->peso,
            $row->numero_sesion,
            $row->diagnostico_renal,
            $row->erc_previa ? 'Sí' : 'No',
            $row->erc_estadio,
            $row->hd_cronica_previa ? 'Sí' : 'No',
            $row->etiologia,
            $row->comorbilidades,
            $row->uci ? 'Sí' : 'No',
            $row->vasopresores ? 'Sí' : 'No',
            $row->ventilacion_mecanica ? 'Sí' : 'No',
            $row->sofa,
            $row->acceso_vascular,
            $row->tipo_cateter,
            $row->indicacion_hd,
            $row->horas_hd,
            $row->ultrafiltracion_ml,
            $row->anticoagulacion,
            $row->hipotension_intradialisis ? 'Sí' : 'No',
            $row->arritmias ? 'Sí' : 'No',
            $row->complicaciones,
            $row->urea_inicial,
            $row->creatinina_inicial,
            $row->potasio_inicial,
            $row->sodio,
            $row->bicarbonato,
            $row->calcio,
            $row->fosforo,
            $row->ph,
            $row->lactato,
            $row->hemoglobina,
            $row->leucocitos,
            $row->plaquetas,
            $row->albumina,
            $row->pcr,
            $row->diuresis_24h_ml,
            $row->recuperacion_renal ? 'Sí' : 'No',
            $row->hd_alta ? 'Sí' : 'No',
            $row->dias_hospitalizacion,
            $row->destino_final,
            $row->mortalidad_28 ? 'Sí' : 'No',
            $row->observaciones,
            $row->created_at,
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Historia clínica', 'Paciente', 'DNI', 'Peso', 'N° sesión', 'Diagnóstico renal', 'ERC previa', 'Estadio ERC', 'HD crónica previa', 'Etiología', 'Comorbilidades', 'UCI', 'Vasopresores', 'Ventilación mecánica', 'SOFA', 'Acceso vascular', 'Tipo de catéter', 'Indicación HD', 'Horas HD', 'Ultrafiltración (ml)', 'Anticoagulación', 'Hipotensión intradiálisis', 'Arritmias', 'Complicaciones', 'Urea inicial', 'Creatinina inicial', 'Potasio inicial', 'Sodio', 'Bicarbonato', 'Calcio', 'Fósforo', 'pH', 'Lactato', 'Hemoglobina', 'Leucocitos', 'Plaquetas', 'Albúmina', 'PCR', 'Diuresis 24h (ml)', 'Recuperación renal', 'HD al alta', 'Días hospitalización', 'Destino final', 'Mortalidad 28 días', 'Observaciones', 'Fecha'];
    }
}
