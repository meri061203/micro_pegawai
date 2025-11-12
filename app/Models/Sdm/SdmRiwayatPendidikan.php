<?php

namespace App\Models\Sdm;

use App\Traits\SkipsEmptyAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

final class SdmRiwayatPendidikan extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    public $incrementing = true;

    public $timestamps = false;

    protected $table = 'pendidikan';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'id',
        'id_sdm',
        'id_jenjang_pendidikan',
        'institusi',
        'jurusan',
        'tahun_masuk',
        'tahun_lulus',
        'jenis_nilai',
        'sks',
        'sumber_biaya',
        'file_ijazah',
        'file_transkip',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'id_sdm' => 'integer',
        'id_jenjang_pendidikan' => 'integer',
        'tahun_masuk' => 'integer',
        'tahun_lulus' => 'integer',
        'ipk' => 'decimal:2',
        'sks' => 'integer',
    ];


    public function setInstitusiAttribute($v): void
{
    $this->attributes['institusi'] = $v ? trim(strip_tags($v)) : null;
}

public function setJurusanAttribute($v): void
{
    $this->attributes['jurusan'] = $v ? trim(strip_tags($v)) : null;
}

public function setTahunMasukAttribute($v): void
{
    $this->attributes['tahun_masuk'] = is_numeric($v) ? (int)$v : null;
}

public function setTahunLulusAttribute($v): void
{
    $this->attributes['tahun_lulus'] = is_numeric($v) ? (int)$v : null;
}

public function setSksAttribute($v): void
{
    $this->attributes['sks'] = is_numeric($v) ? (int)$v : null;
}

public function setSumberBiayaAttribute($v): void
{
    $this->attributes['sumber_biaya'] = in_array($v, ['MANDIRI', 'BEASISWA']) ? $v : null;
}

public function setJenisNilaiAttribute($v): void
{
    $this->attributes['jenis_nilai'] = in_array($v, ['IPK', 'NILAI']) ? $v : null;
}

public function setFileIjazahAttribute($v): void
{
    $this->attributes['file_ijazah'] = $v ? trim($v) : null;
}

public function setFileTranskipAttribute($v): void
{
    $this->attributes['file_transkip'] = $v ? trim($v) : null;
}

    
}
