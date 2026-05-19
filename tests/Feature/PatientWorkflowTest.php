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
            ->assertSee('Agregar paciente')
            ->assertDontSee('Consulta rápida')
            ->assertDontSee('Diálisis rápida')
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

    public function test_patient_can_be_created_and_updated(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('patients.store'), [
            'nombres_apellidos' => 'Nuevo Paciente Prueba',
            'dni' => '72345678',
            'fecha_ingreso' => '2026-05-19',
            'edad' => 45,
            'sexo' => 'M',
            'codigo_unico' => 'HD-N001',
            'numero_sesion' => 0,
            'numero_historia' => 'HC-NUEVO-001',
        ])->assertRedirect(route('patients.index'));

        $patientId = DB::table('patients')->where('dni', '72345678')->value('id');

        $this->put(route('patients.update', $patientId), [
            'nombres_apellidos' => 'Paciente Prueba Actualizado',
            'dni' => '72345678',
            'fecha_ingreso' => '2026-05-20',
            'edad' => 46,
            'sexo' => 'M',
            'codigo_unico' => 'HD-N001',
            'numero_sesion' => 2,
            'numero_historia' => 'HC-NUEVO-001',
        ])->assertRedirect(route('patients.index'));

        $this->assertDatabaseHas('patients', [
            'id' => $patientId,
            'nombres_apellidos' => 'Paciente Prueba Actualizado',
            'edad' => 46,
            'numero_sesion' => 2,
        ]);
    }

    public function test_patient_without_clinical_records_can_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $patientId = $this->createPatient();

        $this->delete(route('patients.destroy', $patientId))
            ->assertRedirect(route('patients.index'));

        $this->assertDatabaseMissing('patients', ['id' => $patientId]);
    }

    public function test_patient_with_consults_or_dialysis_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create());
        $patientId = $this->createPatient();
        $this->createConsult($patientId);

        $this->get(route('patients.index'))
            ->assertOk()
            ->assertSee('No eliminable');

        $this->delete(route('patients.destroy', $patientId))
            ->assertRedirect(route('patients.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('patients', ['id' => $patientId]);
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

    private function createConsult(int $patientId): int
    {
        return DB::table('consults')->insertGetId([
            'id_paciente' => $patientId,
            'procedencia' => 'Emergencia',
            'diagnostico_renal' => 'LRA',
            'etiologia' => 'Sepsis',
            'hd_cronica_previa' => false,
            'acceso_vascular' => 'CVC temporal',
            'indicacion_hd' => 'Hiperkalemia',
            'destino' => 'ALTA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
