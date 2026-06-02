<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #12233f; font-size: 12px; }
        .header { border-bottom: 3px solid #0f5bff; padding-bottom: 10px; margin-bottom: 18px; }
        .brand { font-size: 20px; font-weight: bold; color: #071a2f; }
        .subtitle { color: #6b7890; }
        .box { border: 1px solid #d9e2ef; border-radius: 8px; padding: 10px; margin-bottom: 12px; }
        .title { font-size: 18px; color: #0f5bff; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #d9e2ef; padding: 6px; vertical-align: top; }
        th { background: #eef5ff; text-align: left; }
        .label { color: #6b7890; font-size: 10px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Laboratorio Fissal</div>
        <div class="subtitle">Gestión clínica · Hemodiálisis</div>
    </div>
    <div class="title">@yield('title')</div>
    @php($patient = $record->patient)
    <div class="box">
        <table>
            <tr><th>Historia clínica</th><td>{{ $patient->numero_historia ?? '—' }}</td><th>Paciente</th><td>{{ $patient->nombres_apellidos ?? '—' }}</td></tr>
            <tr><th>DNI</th><td>{{ $patient->dni ?? '—' }}</td><th>Sexo / Edad</th><td>{{ $patient->sexo ?? '—' }} / {{ $patient->edad ?? '—' }}</td></tr>
            <tr><th>Fecha nacimiento</th><td>{{ optional($patient->fecha_nacimiento)->format('Y-m-d') ?? '—' }}</td><th>Teléfono</th><td>{{ $patient->telefono ?? '—' }}</td></tr>
            <tr><th>Dirección</th><td colspan="3">{{ $patient->direccion ?? '—' }}</td></tr>
        </table>
    </div>
    @yield('content')
</body>
</html>
