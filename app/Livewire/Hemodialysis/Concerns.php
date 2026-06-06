<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

trait Concerns
{
    public ?array $patientSummary = null;

    public function updatedPatientId($value): void
    {
        $this->loadPatientSummary((int) $value);
    }

    protected function loadPatientSummary(?int $patientId): void
    {
        $this->patientSummary = $patientId ? Patient::find($patientId)?->only([
            'id', 'numero_historia', 'nombres_apellidos', 'dni', 'sexo', 'edad', 'fecha_nacimiento', 'direccion', 'telefono', 'regimen', 'codigo_unico'
        ]) : null;
    }

    protected function userId(): ?int
    {
        return Auth::id();
    }

    protected function can(string $_permission): bool
    {
        return Auth::check();
    }
}
