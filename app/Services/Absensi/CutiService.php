<?php

namespace App\Services\Absensi;

use App\Models\Absensi\cuti;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CutiService
{
    public function getListData(): Collection
    {
        // 1. Ambil cuti dari koneksi att
        $cuti = DB::connection('absensi')
            ->table('cuti')
            ->get();

        // 2. Ambil sdm + person dari mysql
        $sdm = DB::connection('mysql')
            ->table('sdm')
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select(
                'sdm.id',
                'person.nama_lengkap'
            )
            ->get()
            ->keyBy('id'); // key = sdm.id

        // 3. Mapping manual
        $cuti->transform(function ($row) use ($sdm) {
            $row->nama_lengkap = $sdm[$row->sdm_id]->nama_lengkap ?? null;
            return $row;
        });

        return $cuti;
    }

    public function create(array $data): cuti
    {
        $data['cuti_id'] = $this->generateId();
        $data['status'] = 'PENGAJUAN';

        return cuti::create($data);
    }

    public function getDetailData(string $id)
    {
        $cuti = cuti::findOrFail($id);

        $sdm = DB::connection('mysql')
            ->table('sdm')
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select(
                'sdm.id',
                'person.nama_lengkap'
            )
            ->where('sdm.id', $cuti->sdm_id)
            ->first();

        // field tambahan
        $cuti->nama_lengkap = $sdm->nama_lengkap ?? '-';

        return $cuti;
    }

    public function findById(string $id): ?cuti
    {
        return cuti::find($id);
    }

    public function update(cuti $model, array $data): cuti
    {
        $model->update($data);
        return $model;
    }

    public function getListDataOrdered(string $orderBy): Collection
    {
        return cuti::orderBy($orderBy)->get();
    }

    private function generateId(): string
    {
        $last = cuti::orderBy('cuti_id', 'desc')->first();

        if (!$last) {
            return 'CT-001';
        }

        $lastNumber = intval(substr($last->cuti_id, 4));
        $newNumber = $lastNumber + 1;

        return 'CT-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}