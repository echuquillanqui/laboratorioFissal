<?php

use App\Http\Controllers\PatientWorkflowController;
use Illuminate\Support\Facades\Route;

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
