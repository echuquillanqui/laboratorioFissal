<?php

namespace Tests\Feature;

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
            'hemodialysis.evaluations.index',
            'hemodialysis.sessions.index',
            'hemodialysis.nursing-notes.index',
            'hemodialysis.laboratory-monitors.index',
        ];

        foreach ($routes as $route) {
            $this->get(route($route))->assertOk();
        }
    }
}
