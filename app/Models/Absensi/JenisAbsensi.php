<?php

namespace App\Models\Absensi;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class JenisAbsensi extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'absensi';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'jenis_absensi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'jenis_absensi_id',
        'nama',

        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

}