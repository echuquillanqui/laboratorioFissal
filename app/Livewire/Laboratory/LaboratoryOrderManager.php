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
    public ?int $editPatientId = null;
    public array $editTestIds = [];

    public bool $showStatusModal = false;
    public ?int $statusOrderId = null;
    public ?string $statusEstado = null;

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
        $order = LaboratoryOrder::with('items')->findOrFail($id);
        $this->editOrderId = $order->id;
        $this->editEstado = $order->estado;
        $this->editObservacion = $order->observacion;
        $this->editPatientId = $order->patient_id;
        $this->editTestIds = $order->items->pluck('laboratory_test_id')->unique()->map(fn ($testId) => (string) $testId)->values()->all();
    }

    public function removeEditItem(int $testId): void
    {
        $this->editTestIds = collect($this->editTestIds)
            ->reject(fn ($id) => (int) $id === $testId)
            ->values()
            ->all();
    }

    public function cancelEdit(): void
    {
        $this->reset(['editOrderId', 'editEstado', 'editObservacion', 'editPatientId', 'editTestIds']);
    }

    public function updateOrder(): void
    {
        $this->validate([
            'editOrderId' => 'required|exists:laboratory_orders,id',
            'editPatientId' => 'required|exists:patients,id',
            'editEstado' => 'required|string',
            'editObservacion' => 'nullable|string|max:1000',
            'editTestIds' => 'array|min:1',
            'editTestIds.*' => 'exists:laboratory_tests,id',
        ]);

        $order = LaboratoryOrder::with('items')->findOrFail($this->editOrderId);

        $order->update([
            'patient_id' => $this->editPatientId,
            'estado' => $this->editEstado,
            'observacion' => $this->editObservacion,
        ]);

        $currentIds = $order->items->pluck('laboratory_test_id')->all();
        $targetIds = collect($this->editTestIds)->map(fn ($id) => (int) $id)->unique()->values();

        $order->items()->whereIn('laboratory_test_id', array_diff($currentIds, $targetIds->all()))->delete();

        $existing = $order->items()->pluck('laboratory_test_id')->all();
        $toAdd = $targetIds->diff($existing);

        if ($toAdd->isNotEmpty()) {
            $order->items()->createMany($toAdd->map(fn ($id) => [
                'laboratory_test_id' => $id,
                'origen' => 'individual',
            ])->all());
        }

        $this->cancelEdit();
    }

    public function openStatusModal(int $id): void
    {
        $order = LaboratoryOrder::findOrFail($id);
        $this->statusOrderId = $order->id;
        $this->statusEstado = $order->estado;
        $this->showStatusModal = true;
    }

    public function closeStatusModal(): void
    {
        $this->reset(['showStatusModal', 'statusOrderId', 'statusEstado']);
    }

    public function saveStatus(): void
    {
        $this->validate([
            'statusOrderId' => 'required|exists:laboratory_orders,id',
            'statusEstado' => 'required|string',
        ]);

        LaboratoryOrder::findOrFail($this->statusOrderId)->update([
            'estado' => $this->statusEstado,
        ]);

        $this->closeStatusModal();
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
