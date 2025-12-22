<?php

namespace App\Services\Gaji;

use App\Models\Gaji\KomponenGaji;
use Illuminate\Support\Collection;

final class KomponenGajiService
{
    public function getListData(): Collection
    {
        return KomponenGaji::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return KomponenGaji::orderBy($orderBy)->get();
    }

    public function create(array $data): KomponenGaji
    {
        $data['is_umum'] = isset($data['is_umum']) && $data['is_umum'] ? 1 : 0;

        return KomponenGaji::create($data);
    }

    public function getDetailData(string $id): ?KomponenGaji
    {
        return KomponenGaji::where('id', $id)->first();
    }

    public function findById(string $id): ?KomponenGaji
    {
        return KomponenGaji::find($id);
    }

    public function update(KomponenGaji $unit, array $data): KomponenGaji
    {
        $data['is_umum'] = isset($data['is_umum']) && $data['is_umum'] ? 1 : 0;

        $unit->update($data);
        return $unit;
    }
}