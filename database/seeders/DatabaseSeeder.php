<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'dni' => '00000001',
                'email' => 'superadmin@example.com',
                'cmp' => null,
                'rne' => null,
            ],
            [
                'name' => 'Médico',
                'username' => 'medico',
                'dni' => '00000002',
                'email' => 'medico@example.com',
                'cmp' => 'CMP000001',
                'rne' => null,
            ],
            [
                'name' => 'Enfermera',
                'username' => 'enfermera',
                'dni' => '00000003',
                'email' => 'enfermera@example.com',
                'cmp' => null,
                'rne' => 'RNE000001',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                ]),
            );
        }

        $this->call(PatientSeeder::class);
    }
}
