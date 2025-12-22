<?php

namespace App\Models\Gaji;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class GajiPeriode extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'gaji';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'gaji_periode';
    protected $primaryKey = 'id';
    protected $fillable = [
        'periode_id',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer'
    ];

}