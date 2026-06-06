<?php

use App\Http\Controllers\PatientWorkflowController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Hemodialysis\PdfController as HemodialysisPdfController;
use App\Livewire\Hemodialysis\HemodialysisAdmissionCrud;
use App\Livewire\Hemodialysis\HemodialysisLaboratoryMonitorCrud;
use App\Livewire\Hemodialysis\HemodialysisMedicalEvaluationCrud;
use App\Livewire\Hemodialysis\HemodialysisNursingNoteCrud;
use App\Livewire\Hemodialysis\HemodialysisSessionCrud;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/usuarios', 'users.index')->middleware('auth')->name('users.index');

Route::middleware('auth')->group(function () {
    Route::get('/pacientes', [PatientWorkflowController::class, 'index'])->name('patients.index');
    Route::get('/pacientes/nuevo', [PatientWorkflowController::class, 'create'])->name('patients.create');
    Route::post('/pacientes', [PatientWorkflowController::class, 'store'])->name('patients.store');
    Route::get('/pacientes/{patient}/editar', [PatientWorkflowController::class, 'edit'])->name('patients.edit');
    Route::put('/pacientes/{patient}', [PatientWorkflowController::class, 'update'])->name('patients.update');
    Route::delete('/pacientes/{patient}', [PatientWorkflowController::class, 'destroy'])->name('patients.destroy');
    Route::get('/pacientes/autocomplete', [PatientWorkflowController::class, 'autocomplete'])->name('patients.autocomplete');
    Route::get('/pacientes/buscar', [PatientWorkflowController::class, 'search'])->name('patients.search');
    Route::get('/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('consults.create');
    Route::get('/pacientes/{patient}/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('patients.consults.create');
    Route::get('/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('dialysis.create');
    Route::get('/pacientes/{patient}/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('patients.dialysis.create');
    Route::get('/reportes/exportar', [PatientWorkflowController::class, 'exportReports'])->name('reports.export');
});

use App\Livewire\Laboratory\LaboratoryTestCrud;

use App\Livewire\Laboratory\LaboratoryAreaCrud;
use App\Livewire\Laboratory\LaboratoryProfileCrud;
use App\Livewire\Laboratory\LaboratoryPackageCrud;
use App\Livewire\Laboratory\LaboratoryOrderManager;
use App\Livewire\Laboratory\LaboratoryResultManager;
use App\Livewire\Laboratory\LaboratoryMassOrderManager;

Route::middleware('auth')->name('laboratory.')->group(function () {
    Route::get('/areas', LaboratoryAreaCrud::class)->name('areas.index');
    Route::get('/pruebas', LaboratoryTestCrud::class)->name('tests.index');
    Route::get('/perfiles', LaboratoryProfileCrud::class)->name('profiles.index');
    Route::get('/paquetes', LaboratoryPackageCrud::class)->name('packages.index');
    Route::get('/ordenes', LaboratoryOrderManager::class)->name('orders.index');
    Route::get('/resultados', LaboratoryResultManager::class)->name('results.index');
    Route::get('/ordenes-masivas', LaboratoryMassOrderManager::class)->name('mass-orders.index');
});

Route::middleware('auth')->prefix('historias-clinicas/hemodialisis')->name('hemodialysis.')->group(function () {
    Route::get('/ingresos', HemodialysisAdmissionCrud::class)->name('admissions.index');
    Route::get('/ingresos/nuevo', HemodialysisAdmissionCrud::class)->name('admissions.create');
    Route::get('/ingresos/{record}/editar', HemodialysisAdmissionCrud::class)->name('admissions.edit');
    Route::get('/evaluaciones-medicas', HemodialysisMedicalEvaluationCrud::class)->name('evaluations.index');
    Route::get('/fichas', HemodialysisSessionCrud::class)->name('sessions.index');
    Route::get('/notas-enfermeria', HemodialysisNursingNoteCrud::class)->name('nursing-notes.index');
    Route::get('/monitoreo-laboratorio', HemodialysisLaboratoryMonitorCrud::class)->name('laboratory-monitors.index');

    Route::get('/ingresos/{record}/pdf', [HemodialysisPdfController::class, 'admission'])->name('admissions.pdf');
    Route::get('/evaluaciones-medicas/{record}/pdf', [HemodialysisPdfController::class, 'evaluation'])->name('evaluations.pdf');
    Route::get('/fichas/{record}/pdf', [HemodialysisPdfController::class, 'session'])->name('sessions.pdf');
    Route::get('/notas-enfermeria/{record}/pdf', [HemodialysisPdfController::class, 'nursingNote'])->name('nursing-notes.pdf');
    Route::get('/monitoreo-laboratorio/{record}/pdf', [HemodialysisPdfController::class, 'laboratoryMonitor'])->name('laboratory-monitors.pdf');
});
