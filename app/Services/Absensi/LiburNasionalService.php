<?php

namespace App\Services\Absensi;

use App\Models\Absensi\LiburNasional;
use Illuminate\Support\Collection;

final class LiburNasionalService
{
    public function getListData(): Collection
    {
        return LiburNasional::all();
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return LiburNasional::orderBy($orderBy)->get();
    }

    public function create(array $data): LiburNasional
    {
        return LiburNasional::create($data);
    }

    public function getDetailData(string $id): ?LiburNasional
    {
        return LiburNasional::query()->where('libur_nasional.id', $id)->first();
    }

    public function findById(string $id): ?LiburNasional
    {
        return LiburNasional::find($id);
    }

    public function update(LiburNasional $unit, array $data): LiburNasional
    {
        $unit->update($data);

        return $unit;
    }
}