<?php

namespace App\Services\Gaji;

use App\Models\Gaji\TarifPotongan;
use Illuminate\Support\Collection;

final class TarifPotonganService
{
    public function getListData(): Collection
    {
        return TarifPotongan::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return TarifPotongan::orderBy($orderBy)->get();
    }

    public function create(array $data): TarifPotongan
    {
        return TarifPotongan::create($data);
    }

    public function getDetailData(string $id): ?TarifPotongan
    {
        return TarifPotongan::query()->where('tarif_Potongan.id', $id)->first();
    }

    public function findById(string $id): ?TarifPotongan
    {
        return TarifPotongan::find($id);
    }

    public function update(TarifPotongan $unit, array $data): TarifPotongan
    {
        $unit->update($data);

        return $unit;
    }
}