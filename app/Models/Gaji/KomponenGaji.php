<?php

namespace App\Models\Gaji;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class KomponenGaji extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'gaji';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'komponen_gaji';
    protected $primaryKey = 'id';
    protected $fillable = [
        'komponen_id',
        'umum_id',
        'nama_komponen',
        'jenis',
        'deskripsi',
        'is_umum',
        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_umum' => 'boolean',
    ];

}