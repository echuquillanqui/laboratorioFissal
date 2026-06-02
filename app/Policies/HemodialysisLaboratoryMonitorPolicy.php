<?php

namespace App\Policies;

use App\Models\User;

class HemodialysisLaboratoryMonitorPolicy
{
    public function viewAny(User $user): bool { return $user->can('ver_hd'); }
    public function view(User $user): bool { return $user->can('ver_hd'); }
    public function create(User $user): bool { return $user->can('crear_hd'); }
    public function update(User $user): bool { return $user->can('editar_hd'); }
    public function delete(User $user): bool { return $user->can('eliminar_hd'); }
    public function print(User $user): bool { return $user->can('imprimir_hd'); }
}
