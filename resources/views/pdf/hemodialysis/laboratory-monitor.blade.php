@extends('pdf.hemodialysis.layout')
@section('title', 'Monitoreo de Laboratorio')
@section('content')
<div class="box"><table><tr><th>Fecha muestra</th><td>{{ $record->fecha_muestra?->format('Y-m-d') }}</td><th>Sesión</th><td>{{ $record->session?->numero_sesion ?? '—' }}</td></tr></table></div>
<table><thead><tr><th>Prueba</th><th>Valor</th><th>Unidad</th><th>Referencia</th><th>Alerta</th></tr></thead><tbody>@foreach($record->results as $result)<tr><td>{{ $result->nombre_prueba }}</td><td>{{ $result->valor }}</td><td>{{ $result->unidad }}</td><td>{{ $result->valor_referencia }}</td><td>{{ $result->alerta ? 'Sí' : 'No' }}</td></tr>@endforeach</tbody></table>
<div class="box"><div class="label">Observación</div>{{ $record->observacion }}</div>
@endsection
