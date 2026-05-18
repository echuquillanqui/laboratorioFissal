<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UserCrud extends Component
{
    public string $search = '';

    public ?int $userId = null;

    public string $name = '';

    public ?string $username = null;

    public ?string $dni = null;

    public string $email = '';

    public ?string $cmp = null;

    public ?string $rne = null;

    public string $password = '';

    public string $password_confirmation = '';

    public bool $isEditing = false;

    public function render()
    {
        $users = User::query()
            ->when($this->search !== '', function ($query) {
                $term = '%'.trim($this->search).'%';

                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('username', 'like', $term)
                        ->orWhere('dni', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('cmp', 'like', $term)
                        ->orWhere('rne', 'like', $term);
                });
            })
            ->latest()
            ->get();

        return view('livewire.users.user-crud', [
            'users' => $users,
            'totalUsers' => User::count(),
            'verifiedUsers' => User::whereNotNull('email_verified_at')->count(),
        ]);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->dispatch('show-user-modal');
    }

    public function edit(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->dni = $user->dni;
        $this->email = $user->email;
        $this->cmp = $user->cmp;
        $this->rne = $user->rne;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditing = true;

        $this->resetValidation();
        $this->dispatch('show-user-modal');
    }

    public function save(): void
    {
        $this->normalizeOptionalFields();

        $validated = $this->validate($this->rules(), $this->messages());

        $userData = collect($validated)
            ->except(['password', 'password_confirmation'])
            ->toArray();

        if ($this->password !== '') {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEditing && $this->userId) {
            User::findOrFail($this->userId)->update($userData);
            $message = 'Usuario actualizado correctamente.';
        } else {
            $userData['password'] = Hash::make($this->password);
            User::create($userData);
            $message = 'Usuario creado correctamente.';
        }

        $this->resetForm();
        $this->dispatch('hide-user-modal');
        $this->dispatch('notify-user-saved', message: $message);
    }

    public function askDelete(int $userId): void
    {
        $user = User::findOrFail($userId);

        if (Auth::id() === $user->id) {
            $this->dispatch('notify-user-error', message: 'No puedes eliminar tu propio usuario en sesión.');

            return;
        }

        $this->dispatch('confirm-user-delete', id: $user->id, name: $user->name);
    }

    #[On('deleteUser')]
    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            $this->dispatch('notify-user-error', message: 'No puedes eliminar tu propio usuario en sesión.');

            return;
        }

        $user->delete();
        $this->dispatch('notify-user-saved', message: 'Usuario eliminado correctamente.');
    }

    public function resetForm(): void
    {
        $this->reset([
            'userId',
            'name',
            'username',
            'dni',
            'email',
            'cmp',
            'rne',
            'password',
            'password_confirmation',
            'isEditing',
        ]);

        $this->resetValidation();
    }

    private function rules(): array
    {
        $userId = $this->userId;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'dni' => ['nullable', 'digits:8', Rule::unique('users', 'dni')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'cmp' => ['nullable', 'string', 'max:255', Rule::unique('users', 'cmp')->ignore($userId)],
            'rne' => ['nullable', 'string', 'max:255', Rule::unique('users', 'rne')->ignore($userId)],
            'password' => [$this->isEditing ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'username.unique' => 'Este usuario ya está registrado.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'cmp.unique' => 'Este CMP ya está registrado.',
            'rne.unique' => 'Este RNE ya está registrado.',
            'password.required' => 'La contraseña es obligatoria para usuarios nuevos.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }

    private function normalizeOptionalFields(): void
    {
        foreach (['username', 'dni', 'cmp', 'rne'] as $field) {
            $this->{$field} = blank($this->{$field}) ? null : trim($this->{$field});
        }

        $this->name = trim($this->name);
        $this->email = trim($this->email);
    }
}
