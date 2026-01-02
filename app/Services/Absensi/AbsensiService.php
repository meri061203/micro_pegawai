<?php

namespace App\Services\Absensi;

use App\Models\Absensi\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class AbsensiService
{
    /**
     * List data absensi + mapping manual
     */
    public function getListData(Request $request): Collection
    {
        $data = Absensi::query()
            ->leftJoin('jenis_absensi', 'absensi.id_jenis_absensi', '=', 'jenis_absensi.jenis_absensi_id')
            ->select([
                'absensi.*',
                'jenis_absensi.nama',
            ])
            ->get();

        // ambil master person dari DB SDM
        $refSdm = DB::connection('mysql')
            ->table('sdm')
            ->pluck('nip', 'id');

        foreach ($data as $row) {
            $row->nip = $refSdm[$row->id_sdm] ?? null;
        }

        return $data;
    }

    /**
     * List data absensi dengan order
     */
    public function getListDataOrdered(string $orderBy, string $direction = 'asc'): Collection
    {
        return Absensi::orderBy($orderBy, $direction)->get();
    }

    /**
     * Create absensi
     */
    public function create(array $data): Absensi
    {
        return Absensi::create($data);
    }

    /**
     * Detail absensi
     */
    public function getDetailData(string $id): ?Absensi
    {
    $absensi = Absensi::find($id);

    if (!$absensi) {
        return null;
    }

    $sdm = DB::connection('mysql')
        ->table('sdm')
        ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
        ->select(
            'sdm.id',
            'person.nama_lengkap'
        )
        ->where('sdm.id', $absensi->id_sdm)
        ->first();

    // tambahkan field non-db
    $absensi->nama_lengkap = $sdm->nama_lengkap ?? '-';

    return $absensi;
    }

    /**
     * Find by primary id
     */
    public function findById(string $id): ?Absensi
    {
        return Absensi::find($id);
    }

    /**
     * Update absensi
     */
    public function update(Absensi $absensi, array $data): Absensi
    {
        $absensi->update($data);
        return $absensi;
    }

    /**
     * API data (filter)
     */
    public function getApiData(Request $request): Collection
    {
        return Absensi::query()
            ->leftJoin('jenis_absensi', 'absensi.id_jenis_absensi', '=', 'jenis_absensi.jenis_absensi_id')
            ->leftJoin('sdm', 'absensi.id_sdm', '=', 'sdm.id')
            ->select([
                'absensi.*',
                'jenis_absensi.nama',
                'sdm.nip as nama_sdm',
            ])
            ->when($request->query('id'), function ($query, $idSdm) {
                $query->where('absensi.id', $idSdm);
            })
            ->when($request->query('tanggal'), function ($query, $tanggal) {
                $query->whereDate('absensi.tanggal', $tanggal);
            })
            ->get();
    }
}
