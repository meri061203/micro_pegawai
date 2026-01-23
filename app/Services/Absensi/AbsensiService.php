<?php

namespace App\Services\Absensi;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\JadwalKerja;
use App\Models\Absensi\JenisAbsensi;
use DomainException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

final class AbsensiService
{
    public function getListData(): Collection
    {
        $absensi = DB::connection('att')
            ->table('absensi')
            ->leftJoin('jadwal_kerja', 'absensi.jadwal_id', '=', 'jadwal_kerja.jadwal_id')
            ->leftJoin('jenis_absensi', 'absensi.jenis_absen_id', '=', 'jenis_absensi.jenis_absen_id')
            ->select(
                'absensi.*',
                'jadwal_kerja.nama',
                'jadwal_kerja.jam_mulai',
                'jadwal_kerja.jam_selesai',
                'jenis_absensi.nama_absen',
                'jenis_absensi.kategori',
                'jenis_absensi.warna'
            )
            ->orderBy('absensi.tanggal')
            ->get();

        $sdm = DB::connection('mysql')
            ->table('sdm')
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select('sdm.id', 'person.nama_lengkap')
            ->get()
            ->keyBy('id');

        $absensi->transform(function ($row) use ($sdm) {
            $row->nama_lengkap = $sdm->get($row->sdm_id)->nama_lengkap ?? null;
            return $row;
        });

        return $absensi;
    }


    public function create(array $data)
    {
        $tanggal = $data['tanggal'] ?? now()->toDateString();
        
        $sudahAbsen = Absensi::where('sdm_id', $data['sdm_id'])
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahAbsen) {
            return [
                'error' => true,
                'message' => 'Pegawai sudah melakukan absensi hari ini'
            ];
        }
        
        // 1. Tentukan Jadwal (Prioritas: Cari Nama 'Masuk' dan 'Keluar')
        $jadwalMasuk = JadwalKerja::where('nama', 'like', '%Masuk%')->first();
        $jadwalKeluar = JadwalKerja::where('nama', 'like', '%Keluar%')->first();

        if (!$jadwalMasuk) {
             return [
                'error' => true,
                'message' => 'Jadwal "Masuk" tidak ditemukan di Master Data Jadwal Kerja.'
            ];
        }

        $waktuMulai = Carbon::createFromFormat('H:i', $data['waktu_mulai']);
        $waktuSelesai = !empty($data['waktu_selesai']) ? Carbon::createFromFormat('H:i', $data['waktu_selesai']) : null;

        $jamMasukMulai    = Carbon::createFromFormat('H:i', $jadwalMasuk->jam_mulai);
        $jamMasukSelesai   = Carbon::createFromFormat('H:i', $jadwalMasuk->jam_selesai);

        // Validasi Waktu Mulai (Clock-In)
        if ($waktuMulai->lt($jamMasukMulai)) {
            return [
                'error' => true,
                'message' => 'Belum memasuki jam kerja (Minimal: ' . $jadwalMasuk->jam_mulai . ')'
            ];
        }

        // Validasi Waktu Selesai (Clock-Out) jika ada
        if ($waktuSelesai && $jadwalKeluar) {
             $jamKeluarMulai  = Carbon::createFromFormat('H:i', $jadwalKeluar->jam_mulai);
             $jamKeluarSelesai = Carbon::createFromFormat('H:i', $jadwalKeluar->jam_selesai);

             if ($waktuSelesai->lt($jamKeluarMulai)) {
                 return [
                    'error' => true,
                    'message' => 'Belum Memasuki Jam Pulang (Minimal: ' . $jadwalKeluar->jam_mulai . ')'
                ];
             }

             if ($waktuSelesai->gt($jamKeluarSelesai)) {
                return [
                    'error' => true,
                    'message' => 'Waktu absen pulang sudah berakhir (Maksimal: ' . $jadwalKeluar->jam_selesai . ')'
                ];
            }
        }

        // 2. Hitung keterlambatan (Jika waktu mulai > toleransi/batas mulai)
        $totalTerlambat = 0;
        $jenisNama = 'HADIR';

        if ($waktuMulai->gt($jamMasukSelesai)) {
            $totalTerlambat = $jamMasukSelesai->diffInMinutes($waktuMulai) / 60;
            $jenisNama = 'TERLAMBAT';
        }

        // Cari Jenis Absensi
        $jenisAbsensi = JenisAbsensi::where('nama_absen', 'like', "%{$jenisNama}%")->first();
        if (!$jenisAbsensi) {
             $jenisAbsensi = JenisAbsensi::first(); 
        }

        // 4. Simpan absensi
        return Absensi::create([
            'absensi_id'       => $this->generateId(),
            'sdm_id'           => $data['sdm_id'],
            'jadwal_id'        => $jadwalMasuk->jadwal_id ?? $jadwalMasuk->id,
            'tanggal'          => $data['tanggal'] ?? now()->format('Y-m-d'),
            'waktu_mulai'      => $data['waktu_mulai'],
            'waktu_selesai'    => $data['waktu_selesai'] ?? null,
            'jenis_absen_id'   => $jenisAbsensi->jenis_absen_id,
            'total_terlambat'  => $totalTerlambat,
        ]);
    }




    public function getDetailData(string $id)
    {
        $absensi = DB::connection('att')
            ->table('absensi')
            ->leftJoin('jadwal_kerja', 'absensi.jadwal_id', '=', 'jadwal_kerja.jadwal_id')
            ->leftJoin('jenis_absensi', 'absensi.jenis_absen_id', '=', 'jenis_absensi.jenis_absen_id')
            ->leftJoin(DB::connection('mysql')->getDatabaseName() . '.sdm as sdm', 'sdm.id', '=', 'absensi.sdm_id')
            ->leftJoin(DB::connection('mysql')->getDatabaseName() . '.person as person', 'person.id', '=', 'sdm.id_person')
            ->select(
                'absensi.*',
                'jadwal_kerja.nama',
                'jadwal_kerja.jam_mulai',
                'jadwal_kerja.jam_selesai',
                'jenis_absensi.nama_absen',
                'jenis_absensi.kategori',
                'jenis_absensi.warna',
                'person.nama_lengkap'
            )
            ->where('absensi.id', $id)
            ->first();

        return $absensi;
    }




    public function findById(string $id): ?Absensi
    {
        return Absensi::find($id);
    }

    public function update(Absensi $model, array $data): Absensi
    {
        $model->update($data);
        return $model;
    }


    public function getListDataOrdered(string $orderBy): Collection
    {
        return Absensi::orderBy($orderBy)->get();
    }

    private function generateId(): string
    {
        $last = Absensi::orderBy('absensi_id', 'desc')->first();

        if (!$last) {
            return 'ABS-001';
        }
        $lastNumber = intval(substr($last->absensi_id, 4));

        $newNumber = $lastNumber + 1;

        return 'ABS-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}