@extends('pdf.hemodialysis.layout')
@section('title', 'Nota de Enfermería SOAPIE')
@section('content')
<div class="box"><table><tr><th>Fecha nota</th><td>{{ $record->fecha_nota?->format('Y-m-d H:i') }}</td><th>Sesión</th><td>{{ $record->session?->numero_sesion ?? '—' }}</td></tr></table></div>
@foreach(['subjetivo'=>'S - Subjetivo','objetivo'=>'O - Objetivo','analisis'=>'A - Análisis','plan'=>'P - Plan','intervencion'=>'I - Intervención','evaluacion'=>'E - Evaluación'] as $field => $label)<div class="box"><div class="label">{{ $label }}</div>{{ $record->{$field} }}</div>@endforeach
@endsection
