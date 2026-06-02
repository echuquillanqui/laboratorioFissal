@extends('pdf.hemodialysis.layout')
@section('title', 'Evaluación Médica de Ingreso')
@section('content')
<div class="box"><table><tr><th>Fecha evaluación</th><td>{{ $record->fecha_evaluacion?->format('Y-m-d H:i') }}</td><th>Estado</th><td>{{ $record->estado }}</td></tr></table></div>
@foreach(['motivo_ingreso'=>'Motivo de ingreso','examen_fisico'=>'Examen físico','diagnosticos'=>'Diagnósticos','plan_tratamiento'=>'Plan de tratamiento','riesgos'=>'Riesgos','indicaciones_medicas'=>'Indicaciones médicas'] as $field => $label)<div class="box"><div class="label">{{ $label }}</div>{{ $record->{$field} }}</div>@endforeach
@endsection
