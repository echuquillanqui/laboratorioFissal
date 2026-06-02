@extends('pdf.hemodialysis.layout')
@section('title', 'Historia Clínica de Ingreso a Hemodiálisis')
@section('content')
<div class="box"><table><tr><th>Fecha ingreso</th><td>{{ $record->fecha_ingreso_hd?->format('Y-m-d') }}</td><th>Procedencia</th><td>{{ $record->procedencia }}</td></tr><tr><th>Diagnóstico renal</th><td>{{ $record->diagnostico_renal }}</td><th>Etiología</th><td>{{ $record->etiologia }}</td></tr><tr><th>Acceso vascular</th><td>{{ $record->acceso_vascular_inicial }}</td><th>Indicación HD</th><td>{{ $record->indicacion_hd }}</td></tr></table></div>
<div class="box"><div class="label">Antecedentes</div>{{ $record->antecedentes }}</div><div class="box"><div class="label">Comorbilidades</div>{{ $record->comorbilidades }}</div><div class="box"><div class="label">Observaciones</div>{{ $record->observaciones }}</div>
@endsection
