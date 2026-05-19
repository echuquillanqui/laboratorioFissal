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
    Route::get('/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('consults.create');
    Route::get('/pacientes/{patient}/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('patients.consults.create');
    Route::get('/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('dialysis.create');
    Route::get('/pacientes/{patient}/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('patients.dialysis.create');
});

use App\Livewire\Laboratory\LaboratoryTestCrud;

Route::middleware('auth')->prefix('laboratorio')->name('laboratory.')->group(function () {
    Route::get('/pruebas', LaboratoryTestCrud::class)->name('tests.index');
});
