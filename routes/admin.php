<?php

use App\Http\Controllers\admin\Absensi\AbsensiController;
use App\Http\Controllers\admin\absensi\CutiController;
use App\Http\Controllers\admin\absensi\IzinController;
use App\Http\Controllers\admin\Absensi\JadwalKerjaController;
use App\Http\Controllers\admin\Absensi\JenisAbsensiController;
use App\Http\Controllers\admin\absensi\LemburController;
use App\Http\Controllers\admin\Absensi\LiburNasionalController;
use App\Http\Controllers\admin\Absensi\LiburPerusahaanController;
use App\Http\Controllers\admin\Absensi\PengajuanIzinController as AbsensiPengajuanIzinController;
use App\Http\Controllers\admin\gaji\GajiJabatanController;
use App\Http\Controllers\admin\gaji\GajiPeriodeController;
use App\Http\Controllers\admin\gaji\GajiUmumController;
use App\Http\Controllers\admin\gaji\KomponenGajiController;
use App\Http\Controllers\admin\gaji\PengajuanIzinController;
use App\Http\Controllers\admin\gaji\TarifLemburController;
use App\Http\Controllers\admin\gaji\TarifPotonganController;
use App\Http\Controllers\Admin\Master\MasterJabatanController;
use App\Http\Controllers\Admin\Master\MasterPeriodeController;
use App\Http\Controllers\Admin\Master\MasterUnitController;
use App\Http\Controllers\Admin\Person\PersonAsuransiController;
use App\Http\Controllers\Admin\Person\PersonController;
use App\Http\Controllers\Admin\Ref\RefEselonController;
use App\Http\Controllers\Admin\Ref\RefHubunganKeluargaController;
use App\Http\Controllers\Admin\Ref\RefJenisAsuransiController;
use App\Http\Controllers\Admin\Ref\RefJenjangPendidikanController;
use App\Http\Controllers\admin\sdm\DokumenController;
use App\Http\Controllers\Admin\Sdm\PersonSdmController;
use App\Http\Controllers\Admin\Sdm\SdmKeluargaController;
use App\Http\Controllers\Admin\Sdm\SdmRekeningController;
use App\Http\Controllers\Admin\Sdm\SdmRiwayatPendidikanController;
use App\Http\Controllers\Admin\Sdm\SdmStrukturalController;
use App\Http\Controllers\Content\PortalController;
use App\Models\Absensi\Izin;
use App\Models\Absensi\PengajuanIzin;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;

Route::get('view-file/{folder}/{filename}', [PortalController::class, 'viewFile'])
    ->where(['folder' => '[A-Za-z0-9_\-]+', 'filename' => '[A-Za-z0-9_\-\.]+'])
    ->name('view-file');

Route::prefix('person')->group(function () {
    Route::get('/', [PersonController::class, 'index'])
        ->name('person.index');
    Route::get('data', [PersonController::class, 'list'])
        ->name('person.list');
    Route::get('show/{id}', [PersonController::class, 'show'])
        ->name('person.show');
    Route::post('/store', [PersonController::class, 'store'])
        ->name('person.store');
    Route::delete('destroy/{id}', [PersonController::class, 'destroy'])
        ->name('person.destroy');
    Route::post('update/{id}', [PersonController::class, 'update'])
        ->name('person.update');
});

Route::prefix('sdm')->group(function () {
    Route::get('/', [PersonSdmController::class, 'index'])
        ->name('sdm.sdm.index');
    Route::get('data', [PersonSdmController::class, 'list'])
        ->name('sdm.sdm.list');
    Route::get('show/{id}', [PersonSdmController::class, 'show'])
        ->name('sdm.sdm.show');
    Route::post('/store', [PersonSdmController::class, 'store'])
        ->name('sdm.sdm.store');
    Route::post('update/{id}', [PersonSdmController::class, 'update'])
        ->name('sdm.sdm.update');
    Route::get('histori/{id}', [PersonSdmController::class, 'histori'])
        ->name('sdm.sdm.histori');
    Route::post('destroy/{id}', [PersonSdmController::class, 'destroy'])
        ->name('sdm.sdm.destroy');
    Route::get('find/by/nik/{id}', [PersonSdmController::class, 'find_by_nik'])
        ->name('sdm.sdm.find_by_nik');

    Route::prefix('riwayat-pendidikan')->group(function () {
        Route::get('/{id}', [SdmRiwayatPendidikanController::class, 'index'])
            ->name('sdm.riwayat-pendidikan.index');
        Route::get('data/{id}', [SdmRiwayatPendidikanController::class, 'list'])
            ->name('sdm.riwayat-pendidikan.list');
        Route::get('show/{id}', [SdmRiwayatPendidikanController::class, 'show'])
            ->name('sdm.riwayat-pendidikan.show');
        Route::post('/store', [SdmRiwayatPendidikanController::class, 'store'])
            ->name('sdm.riwayat-pendidikan.store');
        Route::post('update/{id}', [SdmRiwayatPendidikanController::class, 'update'])
            ->name('sdm.riwayat-pendidikan.update');
        Route::post('destroy/{id}', [SdmRiwayatPendidikanController::class, 'destroy'])
            ->name('sdm.riwayat-pendidikan.destroy');
    });

    Route::prefix('keluarga')->group(function () {
        Route::get('/{id}', [SdmKeluargaController::class, 'index'])
            ->name('sdm.keluarga.index');
        Route::get('data/{id}', [SdmKeluargaController::class, 'list'])
            ->name('sdm.keluarga.list');
        Route::get('show/{id}', [SdmKeluargaController::class, 'show'])
            ->name('sdm.keluarga.show');
        Route::post('/store', [SdmKeluargaController::class, 'store'])
            ->name('sdm.keluarga.store');
        Route::post('update/{id}', [SdmKeluargaController::class, 'update'])
            ->name('sdm.keluarga.update');
        Route::post('destroy/{id}', [SdmKeluargaController::class, 'destroy'])
            ->name('sdm.keluarga.destroy');
        Route::get('find/by/nik/{id}', [SdmKeluargaController::class, 'find_by_nik'])
            ->name('sdm.keluarga.find_by_nik');
    });

    Route::prefix('asuransi')->group(function () {
        Route::get('/{id}', [PersonAsuransiController::class, 'index'])
            ->name('sdm.asuransi.index');
        Route::get('data/{id}', [PersonAsuransiController::class, 'list'])
            ->name('sdm.asuransi.list');
        Route::get('show/{id}', [PersonAsuransiController::class, 'show'])
            ->name('sdm.asuransi.show');
        Route::post('/store', [PersonAsuransiController::class, 'store'])
            ->name('sdm.asuransi.store');
        Route::post('update/{id}', [PersonAsuransiController::class, 'update'])
            ->name('sdm.asuransi.update');
        Route::post('destroy/{id}', [PersonAsuransiController::class, 'destroy'])
            ->name('sdm.asuransi.destroy');
        Route::get('find/by/nik/{id}', [PersonAsuransiController::class, 'find_by_nik'])
            ->name('sdm.asuransi.find_by_nik');
    });

    Route::prefix('rekening')->group(function () {
        Route::get('/{id}', [SdmRekeningController::class, 'index'])
            ->name('sdm.rekening.index');
        Route::get('data/{id}', [SdmRekeningController::class, 'list'])
            ->name('sdm.rekening.list');
        Route::get('show/{id}', [SdmRekeningController::class, 'show'])
            ->name('sdm.rekening.show');
        Route::post('/store', [SdmRekeningController::class, 'store'])
            ->name('sdm.rekening.store');
        Route::post('update/{id}', [SdmRekeningController::class, 'update'])
            ->name('sdm.rekening.update');
        Route::post('destroy/{id}', [SdmRekeningController::class, 'destroy'])
            ->name('sdm.rekening.destroy');
    });

    Route::prefix('struktural')->group(function () {
        Route::get('/{id}', [SdmStrukturalController::class, 'index'])
            ->name('sdm.struktural.index');
        Route::get('data/{id}', [SdmStrukturalController::class, 'list'])
            ->name('sdm.struktural.list');
        Route::get('show/{id}', [SdmStrukturalController::class, 'show'])
            ->name('sdm.struktural.show');
        Route::post('/store', [SdmStrukturalController::class, 'store'])
            ->name('sdm.struktural.store');
        Route::post('update/{id}', [SdmStrukturalController::class, 'update'])
            ->name('sdm.struktural.update');
        Route::post('destroy/{id}', [SdmStrukturalController::class, 'destroy'])
            ->name('sdm.struktural.destroy');
    });

    Route::prefix('dokumen')->group(function () {
        Route::get('/{id}', [DokumenController::class, 'index'])
            ->name('sdm.dokumen.index');
        Route::get('data/{id}', [DokumenController::class, 'list'])
            ->name('sdm.dokumen.list');
        Route::get('show/{id}', [DokumenController::class, 'show'])
            ->name('sdm.dokumen.show');
        Route::post('/store', [DokumenController::class, 'store'])
            ->name('sdm.dokumen.store');
        Route::post('update/{id}', [DokumenController::class, 'update'])
            ->name('sdm.dokumen.update');
        Route::post('destroy/{id}', [DokumenController::class, 'destroy'])
            ->name('sdm.dokumen.destroy');
    });
});

Route::prefix('master')->group(function () {
    Route::prefix('periode')->group(function () {
        Route::get('/', [MasterPeriodeController::class, 'index'])
            ->name('master.periode.index');
        Route::get('data', [MasterPeriodeController::class, 'list'])
            ->name('master.periode.list');
        Route::get('show/{id}', [MasterPeriodeController::class, 'show'])
            ->name('master.periode.show');
        Route::post('/store', [MasterPeriodeController::class, 'store'])
            ->name('master.periode.store');
        Route::post('update/{id}', [MasterPeriodeController::class, 'update'])
            ->name('master.periode.update');
    });

    Route::prefix('unit')->group(function () {
        Route::get('/', [MasterUnitController::class, 'index'])
            ->name('master.unit.index');
        Route::get('data', [MasterUnitController::class, 'list'])
            ->name('master.unit.list');
        Route::get('show/{id}', [MasterUnitController::class, 'show'])
            ->name('master.unit.show');
        Route::post('/store', [MasterUnitController::class, 'store'])
            ->name('master.unit.store');
        Route::post('update/{id}', [MasterUnitController::class, 'update'])
            ->name('master.unit.update');
    });

    Route::prefix('jabatan')->group(function () {
        Route::get('/', [MasterJabatanController::class, 'index'])
            ->name('master.jabatan.index');
        Route::get('data', [MasterJabatanController::class, 'list'])
            ->name('master.jabatan.list');
        Route::get('show/{id}', [MasterJabatanController::class, 'show'])
            ->name('master.jabatan.show');
        Route::post('/store', [MasterJabatanController::class, 'store'])
            ->name('master.jabatan.store');
        Route::post('update/{id}', [MasterJabatanController::class, 'update'])
            ->name('master.jabatan.update');
    });

});

Route::prefix('ref')->group(function () {
    Route::prefix('jenjang-pendidikan')->group(function () {
        Route::get('/', [RefJenjangPendidikanController::class, 'index'])
            ->name('ref.jenjang-pendidikan.index');
        Route::get('data', [RefJenjangPendidikanController::class, 'list'])
            ->name('ref.jenjang-pendidikan.list');
        Route::get('show/{id}', [RefJenjangPendidikanController::class, 'show'])
            ->name('ref.jenjang-pendidikan.show');
        Route::post('/store', [RefJenjangPendidikanController::class, 'store'])
            ->name('ref.jenjang-pendidikan.store');
        Route::post('update/{id}', [RefJenjangPendidikanController::class, 'update'])
            ->name('ref.jenjang-pendidikan.update');
    });

    Route::prefix('hubungan-keluarga')->group(function () {
        Route::get('/', [RefHubunganKeluargaController::class, 'index'])
            ->name('ref.hubungan-keluarga.index');
        Route::get('data', [RefHubunganKeluargaController::class, 'list'])
            ->name('ref.hubungan-keluarga.list');
        Route::get('show/{id}', [RefHubunganKeluargaController::class, 'show'])
            ->name('ref.hubungan-keluarga.show');
        Route::post('/store', [RefHubunganKeluargaController::class, 'store'])
            ->name('ref.hubungan-keluarga.store');
        Route::post('update/{id}', [RefHubunganKeluargaController::class, 'update'])
            ->name('ref.hubungan-keluarga.update');
    });

    Route::prefix('jenis-asuransi')->group(function () {
        Route::get('/', [RefJenisAsuransiController::class, 'index'])
            ->name('ref.jenis-asuransi.index');
        Route::get('data', [RefJenisAsuransiController::class, 'list'])
            ->name('ref.jenis-asuransi.list');
        Route::get('show/{id}', [RefJenisAsuransiController::class, 'show'])
            ->name('ref.jenis-asuransi.show');
        Route::post('/store', [RefJenisAsuransiController::class, 'store'])
            ->name('ref.jenis-asuransi.store');
        Route::post('update/{id}', [RefJenisAsuransiController::class, 'update'])
            ->name('ref.jenis-asuransi.update');
    });

    Route::prefix('eselon')->group(function () {
        Route::get('/', [RefEselonController::class, 'index'])
            ->name('ref.eselon.index');
        Route::get('data', [RefEselonController::class, 'list'])
            ->name('ref.eselon.list');
        Route::get('show/{id}', [RefEselonController::class, 'show'])
            ->name('ref.eselon.show');
        Route::post('/store', [RefEselonController::class, 'store'])
            ->name('ref.eselon.store');
        Route::post('update/{id}', [RefEselonController::class, 'update'])
            ->name('ref.eselon.update');
    });
});

Route::prefix('gaji')->group(function () {
    Route::prefix('gaji_umum')->group(function () {
        Route::get('/', [GajiUmumController::class, 'index'])
            ->name('gaji.gaji_umum.index');
        Route::get('data', [GajiUmumController::class, 'list'])
            ->name('gaji.gaji_umum.list');
        Route::get('show/{id}', [GajiUmumController::class, 'show'])
            ->name('gaji.gaji_umum.show');
        Route::post('/store', [GajiUmumController::class, 'store'])
            ->name('gaji.gaji_umum.store');
        Route::post('update/{id}', [GajiUmumController::class, 'update'])
            ->name('gaji.gaji_umum.update');
    });

    Route::prefix('gaji_periode')->group(function () {
        Route::get('/', [GajiPeriodeController::class, 'index'])
            ->name('gaji.gaji_periode.index');
        Route::get('data', [GajiPeriodeController::class, 'list'])
            ->name('gaji.gaji_periode.list');
        Route::get('show/{id}', [GajiPeriodeController::class, 'show'])
            ->name('gaji.gaji_periode.show');
        Route::post('/store', [GajiPeriodeController::class, 'store'])
            ->name('gaji.gaji_periode.store');
        Route::post('update/{id}', [GajiPeriodeController::class, 'update'])
            ->name('gaji.gaji_periode.update');
    }); 

    Route::prefix('tarif_lembur')->group(function () {
        Route::get('/', [TarifLemburController::class, 'index'])
            ->name('gaji.tarif_lembur.index');
        Route::get('data', [TarifLemburController::class, 'list'])
            ->name('gaji.tarif_lembur.list');
        Route::get('show/{id}', [TarifLemburController::class, 'show'])
            ->name('gaji.tarif_lembur.show');
        Route::post('/store', [TarifLemburController::class, 'store'])
            ->name('gaji.tarif_lembur.store');
        Route::post('update/{id}', [TarifLemburController::class, 'update'])
            ->name('gaji.tarif_lembur.update');
    }); 

    Route::prefix('tarif_potongan')->group(function () {
        Route::get('/', [TarifPotonganController::class, 'index'])
            ->name('gaji.tarif_potongan.index');
        Route::get('data', [TarifPotonganController::class, 'list'])
            ->name('gaji.tarif_potongan.list');
        Route::get('show/{id}', [TarifPotonganController::class, 'show'])
            ->name('gaji.tarif_potongan.show');
        Route::post('/store', [TarifPotonganController::class, 'store'])
            ->name('gaji.tarif_potongan.store');
        Route::post('update/{id}', [TarifPotonganController::class, 'update'])
            ->name('gaji.tarif_potongan.update');
    }); 

    Route::prefix('komponen_gaji')->group(function () {
        Route::get('/', [KomponenGajiController::class, 'index'])
            ->name('gaji.komponen_gaji.index');
        Route::get('data', [KomponenGajiController::class, 'list'])
            ->name('gaji.komponen_gaji.list');
        Route::get('show/{id}', [KomponenGajiController::class, 'show'])
            ->name('gaji.komponen_gaji.show');
        Route::post('/store', [KomponenGajiController::class, 'store'])
            ->name('gaji.komponen_gaji.store');
        Route::post('update/{id}', [KomponenGajiController::class, 'update'])
            ->name('gaji.komponen_gaji.update');
    });

    Route::prefix('gaji_jabatan')->group(function () {
        Route::get('/', [GajiJabatanController::class, 'index'])
            ->name('gaji.gaji_jabatan.index');
        Route::get('data', [GajiJabatanController::class, 'list'])
            ->name('gaji.gaji_jabatan.list');
        Route::get('show/{id}', [GajiJabatanController::class, 'show'])
            ->name('gaji.gaji_jabatan.show');
        Route::post('/store', [GajiJabatanController::class, 'store'])
            ->name('gaji.gaji_jabatan.store');
        Route::post('update/{id}', [GajiJabatanController::class, 'update'])
            ->name('gaji.gaji_jabatan.update');
       
    });
  
});

Route::prefix('absensi')->group(function () {
    Route::prefix('jenis_absensi')->group(function () {
        Route::get('/', [JenisAbsensiController::class, 'index'])
            ->name('absensi.jenis_absensi.index');
        Route::get('data', [JenisAbsensiController::class, 'list'])
            ->name('absensi.jenis_absensi.list');
        Route::get('show/{id}', [JenisAbsensiController::class, 'show'])
            ->name('absensi.jenis_absensi.show');
        Route::post('/store', [JenisAbsensiController::class, 'store'])
            ->name('absensi.jenis_absensi.store');
        Route::post('update/{id}', [JenisAbsensiController::class, 'update'])
            ->name('absensi.jenis_absensi.update');
    });

    Route::prefix('jadwal_kerja')->group(function () {
        Route::get('/', [JadwalKerjaController::class, 'index'])
            ->name('absensi.jadwal_kerja.index');
        Route::get('data', [JadwalKerjaController::class, 'list'])
            ->name('absensi.jadwal_kerja.list');
        Route::get('show/{id}', [JadwalKerjaController::class, 'show'])
            ->name('absensi.jadwal_kerja.show');
        Route::post('/store', [JadwalKerjaController::class, 'store'])
            ->name('absensi.jadwal_kerja.store');
        Route::post('update/{id}', [JadwalKerjaController::class, 'update'])
            ->name('absensi.jadwal_kerja.update');
    });

     Route::prefix('libur_nasional')->group(function () {
        Route::get('/', [LiburNasionalController::class, 'index'])
            ->name('absensi.libur_nasional.index');
        Route::get('data', [LiburNasionalController::class, 'list'])
            ->name('absensi.libur_nasional.list');
        Route::get('show/{id}', [LiburNasionalController::class, 'show'])
            ->name('absensi.libur_nasional.show');
        Route::post('/store', [LiburNasionalController::class, 'store'])
            ->name('absensi.libur_nasional.store');
        Route::post('update/{id}', [LiburNasionalController::class, 'update'])
            ->name('absensi.libur_nasional.update');
    });

    Route::prefix('libur_perusahaan')->group(function () {
        Route::get('/', [LiburPerusahaanController::class, 'index'])
            ->name('absensi.libur_perusahaan.index');
        Route::get('data', [LiburPerusahaanController::class, 'list'])
            ->name('absensi.libur_perusahaan.list');
        Route::get('show/{id}', [LiburPerusahaanController::class, 'show'])
            ->name('absensi.libur_perusahaan.show');
        Route::post('/store', [LiburPerusahaanController::class, 'store'])
            ->name('absensi.libur_perusahaan.store');
        Route::post('update/{id}', [LiburPerusahaanController::class, 'update'])
            ->name('absensi.libur_perusahaan.update');
    });

    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])
            ->name('absensi.absensi.index');
        Route::get('data', [AbsensiController::class, 'list'])
            ->name('absensi.absensi.list');
        Route::get('show/{id}', [AbsensiController::class, 'show'])
            ->name('absensi.absensi.show');
        Route::post('/store', [AbsensiController::class, 'store'])
            ->name('absensi.absensi.store');
        Route::post('update/{id}', [AbsensiController::class, 'update'])
            ->name('absensi.absensi.update');
       
    });

    Route::prefix('pengajuan_izin')->group(function () {
        Route::get('/', [IzinController::class, 'index'])
            ->name('absensi.pengajuan_izin.index');
        Route::get('data', [IzinController::class, 'list'])
            ->name('absensi.pengajuan_izin.list');
        Route::get('show/{id}', [IzinController::class, 'show'])
            ->name('absensi.pengajuan_izin.show');
        Route::post('/store', [IzinController::class, 'store'])
            ->name('absensi.pengajuan_izin.store');
        Route::post('update/{id}', [IzinController::class, 'update'])
            ->name('absensi.pengajuan_izin.update');
        Route::post('approval/{id}', [IzinController::class, 'approval'])->name('absensi.pengajuan_izin.approval');
    });

    Route::prefix('pengajuan_lembur')->group(function () {
        Route::get('/', [LemburController::class, 'index'])
            ->name('absensi.pengajuan_lembur.index');
        Route::get('data', [LemburController::class, 'list'])
            ->name('absensi.pengajuan_lembur.list');
        Route::get('show/{id}', [LemburController::class, 'show'])
            ->name('absensi.pengajuan_lembur.show');
        Route::post('/store', [LemburController::class, 'store'])
            ->name('absensi.pengajuan_lembur.store');
        Route::post('update/{id}', [LemburController::class, 'update'])
            ->name('absensi.pengajuan_lembur.update');
        Route::post('approval/{id}', [LemburController::class, 'approval'])->name('absensi.pengajuan_lembur.approval');
    });

    Route::prefix('pengajuan_cuti')->group(function () {
        Route::get('/', [CutiController::class, 'index'])
            ->name('absensi.pengajuan_cuti.index');
        Route::get('data', [CutiController::class, 'list'])
            ->name('absensi.pengajuan_cuti.list');
        Route::get('show/{id}', [CutiController::class, 'show'])
            ->name('absensi.pengajuan_cuti.show');
        Route::post('/store', [CutiController::class, 'store'])
            ->name('absensi.pengajuan_cuti.store');
        Route::post('update/{id}', [CutiController::class, 'update'])
            ->name('absensi.pengajuan_cuti.update');
        Route::post('approval/{id}', [CutiController::class, 'approval'])->name('absensi.pengajuan_cuti.approval');
    });

});