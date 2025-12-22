<?php

namespace App\Services\Absensi;

use App\Models\Absensi\JadwalKerja;
use Illuminate\Support\Collection;

final class JadwalKerjaService
{
    public function getListData(): Collection
    {
        return JadwalKerja::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return JadwalKerja::orderBy($orderBy)->get();
    }

    public function create(array $data): JadwalKerja
    {
        return JadwalKerja::create($data);
    }

    public function getDetailData(string $id): ?JadwalKerja
    {
        return JadwalKerja::query()->where('jadwal_kerja.id', $id)->first();
    }

    public function findById(string $id): ?JadwalKerja
    {
        return JadwalKerja::find($id);
    }

    public function update(JadwalKerja $unit, array $data): JadwalKerja
    {
        $unit->update($data);

        return $unit;
    }
}