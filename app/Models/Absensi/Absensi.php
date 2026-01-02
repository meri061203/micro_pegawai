<?php

namespace App\Models\Absensi;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class Absensi extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'att';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'absensi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'absensi_id',
        'id_sdm',
        'id_jenis_absensi',
        'tanggal',
        'keterangan',
        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer',
        'id_sdm' => 'integer',
        'id_jenis_absensi' => 'integer',
    ];

}

