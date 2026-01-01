<?php

namespace App\Models\Absensi;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class Cuti extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'absensi';

    protected $table = 'cuti';
    protected $primaryKey = 'id';
    protected $increamenting = true;
    public $timestamps = false;

    protected $fillable = [
        'cuti_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan',
        'disetujui_oleh',
        'disetujui_pada',
        'total_hari',
        'sdm_id',
    ];

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'id' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_pada' => 'datetime',
    ];

        protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getTanggalMulaiAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getTanggalSelesaiAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }
}