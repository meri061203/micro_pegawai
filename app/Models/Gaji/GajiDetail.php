<?php

namespace App\Models\Gaji;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class GajiDetail extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'gaji';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'gaji_detail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaksi_id',
        'detail_id',
        'komponen_id',
        'nominal',
        'keterangan',
        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function komponen()
    {
        return $this->belongsTo(KomponenGaji::class, 'komponen_id', 'komponen_id');
    }

}
