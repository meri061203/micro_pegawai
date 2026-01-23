<?php

namespace App\Services\Gaji;

use App\Models\Absensi\Absensi;
use App\Models\Absensi\Cuti;
use App\Models\Absensi\Izin;
use App\Models\Absensi\Lembur;
use App\Models\Gaji\GajiTrx;
use App\Models\Gaji\GajiDetail;
use App\Models\Gaji\GajiJabatan;
use App\Models\Gaji\GajiPeriode;
use App\Models\Gaji\GajiUmum;
use App\Models\Gaji\KomponenGaji;
use App\Models\Gaji\TarifPotongan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollManualProcessorService
{
    /**
     * Proses payroll satu pegawai
     */
    public function processSingleEmployee(string $periodeId, string $sdmId): GajiTrx
    {
        DB::connection('gaji')->beginTransaction();

        try {
            /** =========================
             *  1. Ambil Data Periode
             * ========================= */
            /** =========================
             *  1. Ambil Data Periode
             * ========================= */
            $periode = GajiPeriode::on('gaji')->where('periode_id', $periodeId)->firstOrFail();


            /** =========================
             *  2. Ambil Data Pegawai
             * ========================= */
            $pegawai = DB::connection('mysql')
                ->table('sdm')
                ->leftJoin('sdm_struktural', function($join) {
                    $join->on('sdm.id', '=', 'sdm_struktural.id_sdm')
                         ->whereNull('sdm_struktural.file_sk_keluar');
                })
                ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
                ->leftJoin('master_unit', 'master_unit.id_unit', '=', 'sdm_struktural.id_unit')
                ->leftJoin('master_jabatan', 'master_jabatan.id_jabatan', '=', 'sdm_struktural.id_jabatan')
                ->select([
                    'sdm_struktural.id_struktural',
                    'sdm.id as id_sdm',
                    'sdm_struktural.id_unit',
                    'sdm_struktural.id_jabatan',
                    'person.nama_lengkap',
                    'person.nik',
                    'master_unit.unit as nama_unit',
                    'master_jabatan.jabatan as nama_jabatan',
                    'sdm.nip',
                    'sdm.status_pegawai',
                ])
                ->where('sdm.id', $sdmId)
                ->first();

            if (!$pegawai) {
                throw new \Exception("Data SDM dengan ID {$sdmId} tidak ditemukan di master data (tabel sdm)");
            }

            if (!$pegawai->id_jabatan) {
                throw new \Exception("Pegawai {$pegawai->nama_lengkap} (ID: {$sdmId}) tidak memiliki data jabatan/unit kerja aktif di sdm_struktural");
            }



            /** =========================
             *  3. Buat Header Transaksi
             * ========================= */
            $transaksiId = $this->generateTransactionId();

            $gajiTrx = GajiTrx::on('gaji')->create([
                'transaksi_id'       => $transaksiId,
                'periode_id'         => $periode->periode_id,
                'sdm_id'             => $sdmId,
                'total_penghasil'    => 0,
                'total_potongan'     => 0,
                'total_dibayar'      => 0,
            ]);

            $totalPenghasilan = 0;
            $totalPotongan = 0;

            /** =========================
             *  4. Ambil Komponen Gaji
             * ========================= */
            $komponenList = $this->getEmployeeComponents($pegawai->id_jabatan);

            foreach ($komponenList as $komponen) {
                if (!$komponen) continue;

                $nominal = $this->calculateNominal($komponen, $pegawai->id_jabatan);
                $qtyData = $this->calculateQuantityData($komponen, $sdmId, $periode);
                $jumlah  = $qtyData['qty'];
                $desc    = $qtyData['desc'];

                $subtotal = $nominal * $jumlah;

                if ($subtotal == 0 && $jumlah == 0) continue;

                /** =========================
                 *  5. Insert Detail
                 * ========================= */
                GajiDetail::on('gaji')->create([
                    'transaksi_id'     => $transaksiId,
                    'detail_id'        => \Illuminate\Support\Str::uuid()->toString(),
                    'komponen_id'      => $komponen->komponen_id,
                    'nominal'          => $subtotal,
                    'keterangan'       => $desc . ($jumlah > 1 ? " (" . floatval($jumlah) . " x " . number_format($nominal) . ")" : ""),
                ]);

                $jenis = strtoupper($komponen->jenis);
                $incomeKeywords = ['PENERIMAAN', 'PENGHASIL', 'PENDAPATAN', 'GAJI', 'TUNJANGAN', 'HONOR', 'INSENTIF', 'BONUS'];
                $isIncome = false;
                foreach ($incomeKeywords as $kw) {
                    if (str_contains($jenis, $kw)) {
                        $isIncome = true;
                        break;
                    }
                }

                if ($isIncome) {
                    $totalPenghasilan += $subtotal;
                } else {
                    $totalPotongan += $subtotal;
                }
            }

            /** =========================
             *  6. Update Total Header
             * ========================= */
            $gajiTrx->update([
                'total_penghasil' => $totalPenghasilan,
                'total_potongan'    => $totalPotongan,
                'total_dibayar'     => $totalPenghasilan - $totalPotongan,
            ]);

            DB::connection('gaji')->commit();

            return $gajiTrx->fresh('details');

        } catch (\Exception $e) {
            DB::connection('gaji')->rollBack();

            Log::error('Gagal proses payroll manual', [
                'periode_id' => $periodeId,
                'sdm_id'     => $sdmId,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }


    /** =========================
     *  TRANSACTION ID
     * ========================= */
    private function generateTransactionId(): string
    {
        $prefix = 'PAY-' . date('ym'); // Example: PAY-2512

        $last = GajiTrx::on('gaji')
            ->where('transaksi_id', 'like', 'PAY-%')
            ->orderByRaw('CAST(SUBSTRING(transaksi_id, 5) AS UNSIGNED) DESC')
            ->first();

        $lastId = $last ? $last->transaksi_id : 'PAY-0000';
        $num = intval(substr($lastId, 4)) + 1;

        $newId = 'PAY-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        // Final guard against duplicates
        while (GajiTrx::on('gaji')->where('transaksi_id', $newId)->exists()) {
            $num++;
            $newId = 'PAY-' . str_pad($num, 4, '0', STR_PAD_LEFT);
        }

        return $newId;
    }

    /** =========================
     *  KOMPONEN GAJI
     * ========================= */
    private function getEmployeeComponents(string $jabatanId)
    {
        // Fix: Manual lookup because relation might be broken (int vs string ID mismatch)
        $gajiJabatanRaw = GajiJabatan::where('id_jabatan', $jabatanId)->get();
        
        // Collect all potential IDs (could be int ID or string KOMP-XXX)
        $potentialIds = $gajiJabatanRaw->pluck('komponen_id')->filter()->unique()->toArray();

        // Fetch KomponenGaji that matches EITHER connection
        $komponenJabatan = collect();
        if (!empty($potentialIds)) {
            $komponenJabatan = KomponenGaji::whereIn('komponen_id', $potentialIds)
                ->orWhereIn('id', $potentialIds)
                ->get();
        }

        $komponenUmum = KomponenGaji::where('is_umum', true)
            ->get();

        $all = $komponenJabatan->merge($komponenUmum);

        // EXTRA: Auto-include Standard Components (Gaji Pokok, Tunjangan, etc.)
        // This forces the system to try calculating them even if GajiJabatan link is broken.
        // The 'calculateNominal' Name-Match logic will then find the value.
        $standardNames = [
            'Gaji Pokok', 'Tunjangan Struktural', 'Tunjangan Fungsional', 
            'Tunjangan Kinerja', 'Uang Makan', 'Transport', 'Tunjangan Harian'
        ];

        $currentNames = $all->pluck('nama_komponen')->map(fn($n) => strtolower($n));

        foreach ($standardNames as $std) {
            if (!$currentNames->contains(strtolower($std))) {
                 $comp = KomponenGaji::where('nama_komponen', 'like', $std)->first();
                 if ($comp) {
                     $all->push($comp);
                     $currentNames->push(strtolower($comp->nama_komponen));
                 }
            }
        }

        // Autodiscover attendance components if missing
        $keywords = ['Lembur', 'Telat', 'Terlambat', 'Izin', 'Cuti', 'Absensi', 'Alpha', 'Sakit'];
        $currentNames = $all->pluck('nama_komponen')->map(fn($n) => strtolower($n));

        foreach ($keywords as $kw) {
            $found = false;
            foreach ($currentNames as $cn) {
                if (str_contains($cn, strtolower($kw))) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $comp = KomponenGaji::on('gaji')->where('nama_komponen', 'like', "%$kw%")->first();
                if ($comp) {
                    $all->push($comp);
                    $currentNames->push(strtolower($comp->nama_komponen));
                }
            }
        }

        return $all->unique('komponen_id');
    }

    /** =========================
     *  NOMINAL
     * ========================= */
    private function calculateNominal($komponen, string $jabatanId): float
    {
        // 1. Cek jika ada nominal khusus per jabatan (GajiJabatan)
        // Coba Cari berdasarkan komponen_id (String Code)
        $gajiJabatan = GajiJabatan::where('id_jabatan', $jabatanId)
            ->where('komponen_id', $komponen->komponen_id)
            ->first();

        // Jika tidak ketemu, Coba cari berdasarkan ID (Integer/PK) - in case data input salah
            if (!$gajiJabatan) {
                 $gajiJabatan = GajiJabatan::where('id_jabatan', $jabatanId)
                    ->where('komponen_id', $komponen->id)
                    ->first();
            }

            // 3. Fallback FINAL: Cek by Name Matching (Manual Scan)
            // Ini menangani kasus parah dimana ID kacau (misal: di GajiJabatan pakai ID lama yang tidak match string/int secara langsung, tapi namanya sama di tabel master)
            if (!$gajiJabatan) {
                $candidates = GajiJabatan::where('id_jabatan', $jabatanId)->get();
                foreach($candidates as $c) {
                    // Coba load komponen kandidat
                    $k = KomponenGaji::where('komponen_id', $c->komponen_id)
                        ->orWhere('id', $c->komponen_id)
                        ->first();
                    
                    if ($k && trim(strtolower($k->nama_komponen)) === trim(strtolower($komponen->nama_komponen ?? ''))) {
                        $gajiJabatan = $c;
                        break;
                    }
                }
            }

        if ($gajiJabatan && !empty($gajiJabatan->nominal)) {
            return (float) $gajiJabatan->nominal;
        }

        // 2. Jika tidak ada, gunakan aturan dari Master Komponen
        switch ($komponen->aturan_nominal ?? 'manual') {
            case 'gaji_umum':
                return $this->getNominalFromGajiUmum($komponen->referensi_id, $komponen->komponen_id);

            case 'tarif_potongan':
                return $this->getNominalFromTarifPotongan($komponen->referensi_id, $komponen->komponen_id);

            case 'tarif_lembur':
                return $this->getNominalFromTarifLembur();

            default:
                $nominal = (float) ($komponen->nominal_default ?? 0);
        }

        // Fallback jika nominal masih 0 (karena konfigurasi database belum lengkap)
        if ($nominal == 0) {
            $nama = strtolower($komponen->nama_komponen ?? '');
            if (str_contains($nama, 'terlambat') || str_contains($nama, 'telat')) {
                return 50000;
            } elseif (str_contains($nama, 'alpha') || str_contains($nama, 'alpa')) {
                return 100000;
            } elseif (str_contains($nama, 'izin')) {
                return 75000;
            } elseif (str_contains($nama, 'cuti')) {
                return 0; // Cuti biasanya dibayar (tidak memotong) atau memotong jatah, tergantung kebijakan. Set 0 aman.
            } elseif (str_contains($nama, 'transport') || str_contains($nama, 'makan') || str_contains($nama, 'hadir')) {
                return 25000;
            }
        }

        return $nominal;
    }

    private function getNominalFromGajiUmum($refId, $komponenId = null): float
    {
        $refId = trim($refId);
        $m = GajiUmum::on('gaji')->where('id', $refId)
            ->orWhere('umum_id', $refId)
            ->first();

        return $m ? (float) $m->nominal : 0;
    }

    private function getNominalFromTarifPotongan($refId, $komponenId = null): float
    {
        $refId = trim($refId);
        $komponenId = trim($komponenId);

        $q = TarifPotongan::on('gaji')->where('id', $refId)
            ->orWhere('potongan_id', $refId);

        if ($komponenId) {
            $q->orWhere('komponen_id', $komponenId);
        }

        $m = $q->first();
        return $m ? (float) $m->tarif_per_kejadian : 0;
    }

    private function getNominalFromTarifLembur(): float
    {
        // Ambil tarif lembur terbaru yang aktif
        $m = \App\Models\Gaji\TarifLembur::orderBy('berlaku_mulai', 'desc')->first();
        return $m ? (float) $m->tarif_per_jam : 0;
    }

    /** =========================
     *  QUANTITY
     * ========================= */
    private function calculateQuantityData($komponen, string $sdmId, $periode): array
    {
        $name = strtolower($komponen->nama_komponen ?? '');
        $qty = 1.0;
        $desc = "Auto - {$komponen->nama_komponen}";

        if (str_contains($name, 'lembur')) {
            $qty = $this->getJamLembur($sdmId, $periode);
            $desc = "Lembur ($qty jam)";
        } elseif (str_contains($name, 'absen') || str_contains($name, 'telat') || str_contains($name, 'terlambat')) {
            $qty = $this->getHariTerlambat($sdmId, $periode);
            $desc = "Potongan Terlambat ($qty hari)";
        } elseif (str_contains($name, 'izin')) {
            $qty = $this->getJamIzin($sdmId, $periode);
            $desc = "Potongan Izin ($qty jam)";
        } elseif (str_contains($name, 'cuti')) {
            $qty = $this->getHariCuti($sdmId, $periode);
            $desc = "Potongan Cuti ($qty hari)";
        } elseif (str_contains($name, 'absen')) {
            $qty = $this->getHariAbsen($sdmId, $periode);
            $desc = "Potongan Absen ($qty hari)";
        } elseif (str_contains($name, 'makan') || str_contains($name, 'transport') || str_contains($name, 'harian') || str_contains($name, 'hadir')) {
            $qty = $this->getHariKerja($sdmId, $periode);
            $desc = "Tunjangan Harian ($qty hari)";
        }

        return ['qty' => (float)$qty, 'desc' => $desc];
    }

    private function getJamLembur($sdmId, $periode): float
    {
        return (float) Lembur::where('sdm_id', $sdmId)
            ->where('status', 'DISETUJUI')
            ->whereBetween('tanggal', [$periode->tanggal_mulai, $periode->tanggal_selesai])
            ->sum('durasi_jam');
    }

    private function getHariTerlambat($sdmId, $periode): float
    {
        // Loose search for 'Terlambat' or 'Telat'
        $jenisIds = \App\Models\Absensi\JenisAbsensi::where('nama_absen', 'like', '%ERLAMBAT%')
            ->orWhere('nama_absen', 'like', '%ELAT%')
            ->pluck('jenis_absen_id')
            ->toArray();

        if (empty($jenisIds)) return 0;

        // Try to sum duration first (if the system uses hours)
        $sumHours = Absensi::where('sdm_id', $sdmId)
            ->whereBetween('tanggal', [$periode->tanggal_mulai, $periode->tanggal_selesai])
            ->whereIn('jenis_absen_id', $jenisIds)
            ->sum('total_terlambat');

        if ($sumHours > 0) {
             return (float) $sumHours;
        }

        // If no duration stored, count incidents
        return (float) Absensi::where('sdm_id', $sdmId)
            ->whereBetween('tanggal', [$periode->tanggal_mulai, $periode->tanggal_selesai])
            ->whereIn('jenis_absen_id', $jenisIds)
            ->count();
    }

    private function getHariKerja($sdmId, $periode): float
    {
        // Loose search for 'Hadir', 'Masuk', 'Terlambat' (Terlambat is usually considered Working Day too)
        $jenisIds = \App\Models\Absensi\JenisAbsensi::where('nama_absen', 'like', '%HADIR%')
            ->orWhere('nama_absen', 'like', '%MASUK%')
            ->orWhere('nama_absen', 'like', '%ERLAMBAT%')
            ->orWhere('nama_absen', 'like', '%ELAT%')
            ->pluck('jenis_absen_id')
            ->toArray();

        if (empty($jenisIds)) return 0;

        return (float) Absensi::where('sdm_id', $sdmId)
            ->whereBetween('tanggal', [$periode->tanggal_mulai, $periode->tanggal_selesai])
            ->whereIn('jenis_absen_id', $jenisIds)
            ->count();
    }

    private function getHariAbsen($sdmId, $periode): float
    {
        // Loose search for 'Sakit', 'Alpha', 'Izin', 'Cuti'
        // Using partials to catch 'Sakit Dokter', 'Izin Pulang', etc.
        $jenisIds = \App\Models\Absensi\JenisAbsensi::where('nama_absen', 'like', '%SAKIT%')
            ->orWhere('nama_absen', 'like', '%ALPHA%')
            ->orWhere('nama_absen', 'like', '%ALPA%')
            ->orWhere('nama_absen', 'like', '%IZIN%')
            ->orWhere('nama_absen', 'like', '%CUTI%')
            ->pluck('jenis_absen_id')
            ->toArray();

        if (empty($jenisIds)) return 0;

        return (float) Absensi::where('sdm_id', $sdmId)
            ->whereBetween('tanggal', [$periode->tanggal_mulai, $periode->tanggal_selesai])
            ->whereIn('jenis_absen_id', $jenisIds)
            ->count();
    }
}