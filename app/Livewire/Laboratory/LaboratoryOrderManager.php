<?php

namespace App\Livewire\Laboratory;

use App\Enums\LaboratoryOrderEstado;
use App\Models\Laboratory\LaboratoryOrder;
use App\Models\Laboratory\LaboratoryPackage;
use App\Models\Laboratory\LaboratoryProfile;
use App\Models\Laboratory\LaboratoryTest;
use App\Models\Patient;
use App\Services\Laboratory\LaboratoryOrderBuilderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LaboratoryOrderManager extends Component
{
    use WithPagination;

    public ?int $patient_id = null;
    public array $test_ids = [];
    public array $profile_ids = [];
    public array $package_ids = [];
    public ?string $observacion = null;

    public string $orderSearch = '';
    public ?int $selectedOrderId = null;
    public ?int $editOrderId = null;
    public ?string $editEstado = null;
    public ?string $editObservacion = null;

    public function updatedOrderSearch(): void
    {
        $this->resetPage('ordersPage');
    }

    public function save(LaboratoryOrderBuilderService $service): void
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $service->createOrder([
            'patient_id' => $this->patient_id,
            'user_id' => Auth::id(),
            'fecha_orden' => now()->toDateString(),
            'estado' => LaboratoryOrderEstado::PENDIENTE->value,
            'observacion' => $this->observacion,
        ], $this->test_ids, $this->profile_ids, $this->package_ids);

        $this->reset(['patient_id', 'test_ids', 'profile_ids', 'package_ids', 'observacion']);
    }

    public function startEdit(int $id): void
    {
        $order = LaboratoryOrder::findOrFail($id);
        $this->editOrderId = $order->id;
        $this->editEstado = $order->estado;
        $this->editObservacion = $order->observacion;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editOrderId', 'editEstado', 'editObservacion']);
    }

    public function updateOrder(): void
    {
        $this->validate([
            'editOrderId' => 'required|exists:laboratory_orders,id',
            'editEstado' => 'required|string',
            'editObservacion' => 'nullable|string|max:1000',
        ]);

        LaboratoryOrder::findOrFail($this->editOrderId)->update([
            'estado' => $this->editEstado,
            'observacion' => $this->editObservacion,
        ]);

        $this->cancelEdit();
    }

    public function deleteOrder(int $id): void
    {
        LaboratoryOrder::findOrFail($id)->delete();
        $this->resetPage('ordersPage');
    }

    public function render()
    {
        $search = trim($this->orderSearch);

        $orders = LaboratoryOrder::query()
            ->with(['patient:id,nombres_apellidos', 'user:id,name', 'items.test:id,nombre'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('id', $search)
                    ->orWhereHas('patient', function ($patientQuery) use ($search) {
                        $patientQuery->where('nombres_apellidos', 'like', "%{$search}%")
                            ->orWhere('numero_documento', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10, ['*'], 'ordersPage');

        return view('livewire.laboratory.order-manager', [
            'patients' => Patient::orderBy('nombres_apellidos')->get(['id', 'nombres_apellidos']),
            'orderSuggestions' => $search === ''
                ? collect()
                : Patient::query()
                    ->where('nombres_apellidos', 'like', "%{$search}%")
                    ->orWhere('numero_documento', 'like', "%{$search}%")
                    ->orderBy('nombres_apellidos')
                    ->limit(8)
                    ->get(['id', 'nombres_apellidos', 'numero_documento']),
            'tests' => LaboratoryTest::where('estado', true)->orderBy('nombre')->get(['id', 'nombre']),
            'profiles' => LaboratoryProfile::where('estado', true)->orderBy('nombre')->get(['id', 'nombre']),
            'packages' => LaboratoryPackage::where('estado', true)->orderBy('nombre')->get(['id', 'nombre']),
            'orders' => $orders,
            'statuses' => collect(LaboratoryOrderEstado::cases())->pluck('value'),
        ])->layout('layouts.app');
    }
}
