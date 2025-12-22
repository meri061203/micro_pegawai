<?php

namespace App\Services\Gaji;

use App\Models\Gaji\GajiPeriode;
use Illuminate\Support\Collection;

final class GajiPeriodeService
{
    public function getListData(): Collection
    {
        return GajiPeriode::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return GajiPeriode::orderBy($orderBy)->get();
    }

    public function create(array $data): GajiPeriode
    {
        return GajiPeriode::create($data);
    }

    public function getDetailData(string $id): ?GajiPeriode
    {
        return GajiPeriode::query()->where('gaji_periode.id', $id)->first();
    }

    public function findById(string $id): ?GajiPeriode
    {
        return GajiPeriode::find($id);
    }

    public function update(GajiPeriode $unit, array $data): GajiPeriode
    {
        $unit->update($data);

        return $unit;
    }
}