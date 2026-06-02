@extends('pdf.hemodialysis.layout')
@section('title', 'Ficha de Hemodiálisis')
@section('content')
<div class="box"><table><tr><th>N° sesión</th><td>{{ $record->numero_sesion }}</td><th>Fecha</th><td>{{ $record->fecha_sesion?->format('Y-m-d') }}</td></tr><tr><th>Hora inicio</th><td>{{ $record->hora_inicio }}</td><th>Hora fin</th><td>{{ $record->hora_fin }}</td></tr><tr><th>Peso pre/post</th><td>{{ $record->peso_pre }} / {{ $record->peso_post }}</td><th>UF ml</th><td>{{ $record->ultrafiltracion_ml }}</td></tr><tr><th>Acceso</th><td>{{ $record->acceso_vascular }}</td><th>Anticoagulación</th><td>{{ $record->anticoagulacion }}</td></tr></table></div>
@foreach(['prescripcion_medica'=>'Prescripción médica','complicaciones'=>'Complicaciones','tolerancia'=>'Tolerancia','observaciones'=>'Observaciones'] as $field => $label)<div class="box"><div class="label">{{ $label }}</div>{{ $record->{$field} }}</div>@endforeach
@endsection
