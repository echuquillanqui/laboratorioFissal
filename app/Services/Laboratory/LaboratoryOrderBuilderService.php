<?php

namespace App\Services\Laboratory;

use App\Models\Laboratory\LaboratoryOrder;
use App\Models\Laboratory\LaboratoryPackage;
use App\Models\Laboratory\LaboratoryProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaboratoryOrderBuilderService
{
    public function createOrder(array $payload, array $testIds = [], array $profileIds = [], array $packageIds = []): LaboratoryOrder
    {
        return DB::transaction(function () use ($payload, $testIds, $profileIds, $packageIds) {
            $order = LaboratoryOrder::create($payload);
            $expanded = $this->expandItems($testIds, $profileIds, $packageIds);

            $order->items()->createMany($expanded->map(fn (array $item) => [
                'laboratory_test_id' => $item['test_id'],
                'origen' => $item['origin'],
            ])->all());

            return $order->load('items');
        });
    }

    public function expandItems(array $testIds, array $profileIds, array $packageIds): Collection
    {
        $items = collect($testIds)->map(fn ($id) => ['test_id' => (int) $id, 'origin' => 'individual']);

        LaboratoryProfile::with('tests:id')->whereIn('id', $profileIds)->get()
            ->each(fn ($profile) => $profile->tests->each(fn ($test) => $items->push(['test_id' => $test->id, 'origin' => 'perfil'])));

        LaboratoryPackage::with('items')->whereIn('id', $packageIds)->get()->each(function ($package) use ($items) {
            $profileIds = $package->items->where('tipo_item', 'profile')->pluck('reference_id');
            $testIds = $package->items->where('tipo_item', 'test')->pluck('reference_id');

            $testIds->each(fn ($id) => $items->push(['test_id' => $id, 'origin' => 'paquete']));

            LaboratoryProfile::with('tests:id')->whereIn('id', $profileIds)->get()
                ->each(fn ($profile) => $profile->tests->each(fn ($test) => $items->push(['test_id' => $test->id, 'origin' => 'paquete'])));
        });

        return $items->unique('test_id')->values();
    }
}
