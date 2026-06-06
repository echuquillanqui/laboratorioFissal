<?php

namespace Tests\Feature;

use App\Models\Hemodialysis\HemodialysisAdmission;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HemodialysisAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_without_permission_guard_can_open_hemodialysis_pages(): void
    {
        $this->actingAs(User::factory()->create());

        $routes = [
            'hemodialysis.admissions.index',
            'hemodialysis.admissions.create',
            'hemodialysis.evaluations.index',
            'hemodialysis.sessions.index',
            'hemodialysis.nursing-notes.index',
            'hemodialysis.laboratory-monitors.index',
        ];

        foreach ($routes as $route) {
            $this->get(route($route))->assertOk();
        }
    }

    public function test_authenticated_user_can_open_hemodialysis_admission_edit_page(): void
    {
        $user = User::factory()->create();
        $patient = Patient::create([
            'nombres_apellidos' => 'Paciente de Prueba',
            'dni' => '12345678',
            'fecha_ingreso' => now()->toDateString(),
            'edad' => 45,
            'sexo' => 'M',
            'codigo_unico' => 'ABC1234',
            'numero_historia' => 'HC-001',
        ]);
        $admission = HemodialysisAdmission::create([
            'patient_id' => $patient->id,
            'created_by' => $user->id,
            'fecha_ingreso_hd' => now()->toDateString(),
            'estado' => 'borrador',
        ]);

        $this->actingAs($user)
            ->get(route('hemodialysis.admissions.edit', $admission))
            ->assertOk();
    }
}
