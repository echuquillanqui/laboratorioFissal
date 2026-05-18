<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email_username_or_dni(): void
    {
        $user = User::factory()->create([
            'email' => 'doctor@example.com',
            'username' => 'doctor',
            'dni' => '12345678',
            'password' => 'password',
        ]);

        foreach (['doctor@example.com', 'doctor', '12345678'] as $login) {
            $response = $this->post('/login', [
                'login' => $login,
                'password' => 'password',
            ]);

            $response->assertRedirect('/home');
            $this->assertAuthenticatedAs($user);

            auth()->logout();
            $this->assertGuest();
        }
    }
}
