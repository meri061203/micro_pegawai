<?php

namespace App\Models\Absensi;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class Lembur extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'att';

    protected $table = 'lembur';
    protected $primaryKey = 'id';
    protected $increamenting = true;
    public $timestamps = false;

    protected $fillable = [
        'lembur_id',
        'sdm_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_jam',
        'disetujui_oleh',
        'disetujui_pada',
        'status',
    ];

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'id' => 'integer',
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'disetujui_pada' => 'datetime',
    ];

        protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getTanggalAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getJamMulaiAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getJamSelesaiAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }
}