<?php

namespace Tests\Feature;

use App\Livewire\Users\UserCrud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_users_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee('Usuarios del laboratorio');
    }

    public function test_component_can_create_update_and_delete_user(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        Livewire::test(UserCrud::class)
            ->set('name', 'Dra. Valeria Rojas')
            ->set('username', 'vrojas')
            ->set('dni', '87654321')
            ->set('email', 'valeria@example.com')
            ->set('cmp', 'CMP-100')
            ->set('rne', 'RNE-200')
            ->set('password', 'secret123')
            ->set('password_confirmation', 'secret123')
            ->call('save')
            ->assertDispatched('notify-user-saved');

        $user = User::where('email', 'valeria@example.com')->firstOrFail();
        $this->assertSame('Dra. Valeria Rojas', $user->name);

        Livewire::test(UserCrud::class)
            ->call('edit', $user->id)
            ->set('name', 'Dra. Valeria Rojas Campos')
            ->set('email', 'valeria.campos@example.com')
            ->call('save')
            ->assertDispatched('notify-user-saved');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Dra. Valeria Rojas Campos',
            'email' => 'valeria.campos@example.com',
        ]);

        Livewire::test(UserCrud::class)
            ->call('deleteUser', $user->id)
            ->assertDispatched('notify-user-saved');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
