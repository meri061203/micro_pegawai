<?php

namespace App\Services\Absensi;

use App\Models\Absensi\JenisAbsensi;
use Illuminate\Support\Collection;

final class JenisAbsensiService
{
    public function getListData(): Collection
    {
        return JenisAbsensi::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return JenisAbsensi::orderBy($orderBy)->get();
    }

    public function create(array $data): JenisAbsensi
    {
        return JenisAbsensi::create($data);
    }

    public function getDetailData(string $id): ?JenisAbsensi
    {
        return JenisAbsensi::query()->where('jenis_absensi.id', $id)->first();
    }

    public function findById(string $id): ?JenisAbsensi
    {
        return JenisAbsensi::find($id);
    }

    public function update(JenisAbsensi $unit, array $data): JenisAbsensi
    {
        $unit->update($data);

        return $unit;
    }
}