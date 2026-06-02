<?php

namespace Database\Seeders\Hemodialysis;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HemodialysisPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['ver_hd', 'crear_hd', 'editar_hd', 'eliminar_hd', 'imprimir_hd'];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superadmin = Role::findOrCreate('superadmin', 'web');
        $medico = Role::findOrCreate('medico', 'web');
        $enfermeria = Role::findOrCreate('enfermeria', 'web');

        $superadmin->syncPermissions($permissions);
        $medico->syncPermissions(['ver_hd', 'crear_hd', 'editar_hd', 'imprimir_hd']);
        $enfermeria->syncPermissions(['ver_hd', 'crear_hd', 'editar_hd', 'imprimir_hd']);

        User::where('username', 'superadmin')->first()?->assignRole($superadmin);
        User::where('username', 'medico')->first()?->assignRole($medico);
        User::where('username', 'enfermera')->first()?->assignRole($enfermeria);
    }
}
