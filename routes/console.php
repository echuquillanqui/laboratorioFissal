<?php

use App\Exports\MigrationAuditExport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('report:migrations {--path= : Ruta de salida del archivo .xlsx}', function () {
    $migrationsPath = database_path('migrations');
    $files = collect(File::files($migrationsPath))
        ->filter(fn ($file) => str_ends_with($file->getFilename(), '.php'))
        ->sortBy(fn ($file) => $file->getFilename())
        ->values();

    $rows = $files->map(function ($file) {
        $content = File::get($file->getPathname());
        $filename = $file->getFilename();

        preg_match_all("/Schema::create\s*\(\s*'([^']+)'/", $content, $createMatches);
        preg_match_all("/Schema::table\s*\(\s*'([^']+)'/", $content, $tableMatches);

        $tables = collect(array_merge($createMatches[1] ?? [], $tableMatches[1] ?? []))
            ->unique()
            ->values();

        $columnCount = preg_match_all('/\$table->(?!foreign|index|unique|primary|fullText|spatialIndex)[a-zA-Z_]+\s*\(/', $content);
        $foreignCount = preg_match_all('/\$table->foreign(Id|Uuid)?\s*\(|->constrained\s*\(/', $content);
        $indexCount = preg_match_all('/\$table->(index|unique|primary|fullText|spatialIndex)\s*\(/', $content);

        $hasSoftDeletes = str_contains($content, 'softDeletes(') || str_contains($content, 'softDeletesTz(');
        $hasTimestamps = str_contains($content, 'timestamps(') || str_contains($content, 'timestampsTz(');
        $hasDown = preg_match('/function\s+down\s*\(\s*\)/', $content) === 1;

        $observaciones = [];
        if ($tables->isEmpty()) {
            $observaciones[] = 'No se detectó Schema::create o Schema::table.';
        }
        if (! $hasDown) {
            $observaciones[] = 'No tiene método down().';
        }

        $estado = empty($observaciones) ? 'Completa' : 'Incompleta';

        return [
            'archivo' => $filename,
            'tabla' => $tables->isEmpty() ? 'N/A' : $tables->join(', '),
            'columnas_detectadas' => $columnCount,
            'foreign_keys_detectadas' => $foreignCount,
            'indices_detectados' => $indexCount,
            'soft_deletes' => $hasSoftDeletes ? 'Sí' : 'No',
            'timestamps' => $hasTimestamps ? 'Sí' : 'No',
            'tiene_down' => $hasDown ? 'Sí' : 'No',
            'estado' => $estado,
            'observaciones' => empty($observaciones) ? 'OK' : implode(' ', $observaciones),
        ];
    });

    $outputPath = $this->option('path') ?: 'reports/reporte_migraciones_'.now()->format('Ymd_His').'.xlsx';
    Excel::store(new MigrationAuditExport($rows), $outputPath, 'local');

    $fullOutputPath = storage_path('app/'.$outputPath);

    $this->info('Reporte generado correctamente.');
    $this->line('Archivo: '.$fullOutputPath);
    $this->line('Total de migraciones evaluadas: '.$rows->count());

    return self::SUCCESS;
})->purpose('Genera un reporte Excel del estado de migraciones por tabla.');
