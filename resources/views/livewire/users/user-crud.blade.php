<div class="container-fluid px-3 px-lg-5 users-page">
    <section class="users-hero mb-4">
        <div class="users-hero-content">
            <span class="eyebrow text-primary">Administración</span>
            <h1 class="fw-bold mb-2">Usuarios del laboratorio</h1>
            <p class="text-muted mb-0">Gestiona accesos, credenciales y datos profesionales desde una experiencia rápida con Livewire.</p>
        </div>
        <div class="users-hero-actions">
            <button type="button" class="btn btn-primary" wire:click="create">
                <span class="me-2">＋</span>Nuevo usuario
            </button>
        </div>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="user-stat-card stat-blue">
                <small>Total de usuarios</small>
                <strong>{{ $totalUsers }}</strong>
                <span>Personal registrado</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="user-stat-card stat-green">
                <small>Correos verificados</small>
                <strong>{{ $verifiedUsers }}</strong>
                <span>Cuentas validadas</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="user-stat-card stat-purple">
                <small>Resultados filtrados</small>
                <strong>{{ $users->count() }}</strong>
                <span>Según búsqueda actual</span>
            </div>
        </div>
    </div>

    <div class="card panel-card users-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Directorio de usuarios</h2>
                    <p class="text-muted mb-0">Busca por nombre, usuario, DNI, correo, CMP o RNE.</p>
                </div>
                <div class="users-search input-group">
                    <span class="input-group-text">⌕</span>
                    <input type="search" class="form-control" placeholder="Buscar usuario..." wire:model.live.debounce.350ms="search">
                </div>
            </div>

            <div class="table-responsive users-table-wrapper">
                <table class="table align-middle users-table mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Credenciales</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="table-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        <div>
                                            <strong class="d-block">{{ $user->name }}</strong>
                                            <small class="text-muted">{{ $user->username ? '@'.$user->username : 'Sin usuario' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="info-chip">DNI: {{ $user->dni ?: '—' }}</span>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold">{{ $user->email }}</span>
                                    <small class="text-muted">{{ $user->email_verified_at ? 'Verificado' : 'Pendiente de verificación' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="credential-pill">CMP {{ $user->cmp ?: '—' }}</span>
                                        <span class="credential-pill">RNE {{ $user->rne ?: '—' }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group action-group" role="group" aria-label="Acciones para {{ $user->name }}">
                                        <button type="button" class="btn btn-sm btn-light" wire:click="edit({{ $user->id }})">Editar</button>
                                        <button type="button" class="btn btn-sm btn-danger" wire:click="askDelete({{ $user->id }})">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state text-center py-5">
                                        <div class="empty-icon mx-auto mb-3">☑</div>
                                        <h3 class="h5 fw-bold mb-1">No hay usuarios para mostrar</h3>
                                        <p class="text-muted mb-3">Crea un usuario nuevo o ajusta el término de búsqueda.</p>
                                        <button type="button" class="btn btn-primary" wire:click="create">Crear usuario</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade user-modal" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <span class="eyebrow text-primary">{{ $isEditing ? 'Edición' : 'Nuevo registro' }}</span>
                        <h2 class="modal-title h4 fw-bold" id="userModalLabel">{{ $isEditing ? 'Actualizar usuario' : 'Crear usuario' }}</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" wire:click="resetForm"></button>
                </div>
                <form wire:submit="save">
                    <div class="modal-body pt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre completo</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.blur="name" placeholder="Ej. Dra. Ana Torres">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Usuario</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" wire:model.blur="username" placeholder="atorres">
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">DNI</label>
                                <input type="text" class="form-control @error('dni') is-invalid @enderror" wire:model.blur="dni" maxlength="8" placeholder="12345678">
                                @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model.blur="email" placeholder="usuario@laboratorio.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">CMP</label>
                                <input type="text" class="form-control @error('cmp') is-invalid @enderror" wire:model.blur="cmp" placeholder="Código CMP">
                                @error('cmp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">RNE</label>
                                <input type="text" class="form-control @error('rne') is-invalid @enderror" wire:model.blur="rne" placeholder="Código RNE">
                                @error('rne') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model.blur="password" placeholder="{{ $isEditing ? 'Dejar en blanco para conservar' : 'Mínimo 8 caracteres' }}">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirmar contraseña</label>
                                <input type="password" class="form-control" wire:model.blur="password_confirmation" placeholder="Repite la contraseña">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="resetForm">Cancelar</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Guardar cambios' : 'Crear usuario' }}</span>
                            <span wire:loading wire:target="save">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                const setupUsersCrud = () => {
                    const modalElement = document.getElementById('userModal');

                    if (!modalElement || modalElement.dataset.usersCrudReady === 'true') {
                        return;
                    }

                    modalElement.dataset.usersCrudReady = 'true';

                    const getDetail = (event) => Array.isArray(event.detail) ? event.detail[0] : event.detail;
                    const userModal = () => window.bootstrap.Modal.getOrCreateInstance(modalElement);
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2600,
                        timerProgressBar: true,
                    });

                    window.addEventListener('show-user-modal', () => userModal().show());
                    window.addEventListener('hide-user-modal', () => userModal().hide());

                    window.addEventListener('notify-user-saved', (event) => {
                        toast.fire({
                            icon: 'success',
                            title: getDetail(event).message,
                        });
                    });

                    window.addEventListener('notify-user-error', (event) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Acción no permitida',
                            text: getDetail(event).message,
                            confirmButtonColor: '#0f5bff',
                        });
                    });

                    window.addEventListener('confirm-user-delete', (event) => {
                        const detail = getDetail(event);

                        Swal.fire({
                            title: '¿Eliminar usuario?',
                            text: `Esta acción retirará a ${detail.name} del sistema.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6b7890',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Livewire.dispatch('deleteUser', { id: detail.id });
                            }
                        });
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupUsersCrud);
                } else {
                    setupUsersCrud();
                }
            </script>
        @endpush
    @endonce
</div>
