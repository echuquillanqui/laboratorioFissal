<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PatientWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_tray_exposes_consult_and_dialysis_actions(): void
    {
        $this->actingAs(User::factory()->create());
        $patientId = $this->createPatient();

        $this->get(route('patients.index'))
            ->assertOk()
            ->assertSee('Generar consulta')
            ->assertSee('Generar diálisis')
            ->assertSee(route('patients.consults.create', $patientId), false)
            ->assertSee(route('patients.dialysis.create', $patientId), false);
    }

    public function test_consult_form_is_prefilled_from_selected_patient(): void
    {
        $this->actingAs(User::factory()->create());
        $patientId = $this->createPatient();

        $this->get(route('patients.consults.create', $patientId))
            ->assertOk()
            ->assertSee('Paciente prellenado')
            ->assertSee('value="Test Paciente Demo"', false)
            ->assertSee('value="71234567"', false)
            ->assertSee('value="HC-TEST-001"', false)
            ->assertSee('name="id_paciente" value="'.$patientId.'"', false);
    }

    public function test_dialysis_form_is_prefilled_from_selected_patient(): void
    {
        $this->actingAs(User::factory()->create());
        $patientId = $this->createPatient(['numero_sesion' => 3]);

        $this->get(route('patients.dialysis.create', $patientId))
            ->assertOk()
            ->assertSee('Paciente prellenado')
            ->assertSee('value="Test Paciente Demo"', false)
            ->assertSee('value="HC-TEST-001"', false)
            ->assertSee('name="numero_sesion_sugerida" value="4"', false)
            ->assertSee('name="id_paciente" value="'.$patientId.'"', false);
    }

    private function createPatient(array $overrides = []): int
    {
        return DB::table('patients')->insertGetId(array_merge([
            'nombres_apellidos' => 'Test Paciente Demo',
            'dni' => '71234567',
            'fecha_ingreso' => '2026-05-19',
            'edad' => 50,
            'sexo' => 'F',
            'codigo_unico' => 'HD-T001',
            'numero_sesion' => 1,
            'numero_historia' => 'HC-TEST-001',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }
}
