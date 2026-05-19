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
    Route::get('/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('consults.create');
    Route::get('/pacientes/{patient}/consultas/generar', [PatientWorkflowController::class, 'createConsult'])->name('patients.consults.create');
    Route::get('/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('dialysis.create');
    Route::get('/pacientes/{patient}/dialisis/generar', [PatientWorkflowController::class, 'createDialysis'])->name('patients.dialysis.create');
});
