<?php

namespace App\Services\Absensi;

use App\Models\Absensi\LiburPerusahaan;
use Illuminate\Support\Collection;

final class LiburPerusahaanService
{
    public function getListData(): Collection
    {
        return LiburPerusahaan::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return LiburPerusahaan::orderBy($orderBy)->get();
    }

    public function create(array $data): LiburPerusahaan
    {
        return LiburPerusahaan::create($data);
    }

    public function getDetailData(string $id): ?LiburPerusahaan
    {
        return LiburPerusahaan::query()->where('libur_perusahaan.id', $id)->first();
    }

    public function findById(string $id): ?LiburPerusahaan
    {
        return LiburPerusahaan::find($id);
    }

    public function update(LiburPerusahaan $unit, array $data): LiburPerusahaan
    {
        $unit->update($data);

        return $unit;
    }
}