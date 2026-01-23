<?php

namespace App\Models\Gaji;

use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

final class GajiTrx extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    protected $connection = 'gaji';
    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'gaji_trx';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaksi_id',
        'periode_id',
        'sdm_id',
        'total_penghasil',
        'total_potongan',
        'total_dibayar',
        ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function details()
    {
        return $this->hasMany(GajiDetail::class, 'transaksi_id', 'transaksi_id');
    }

    public function sdm()
    {
        return $this->belongsTo(\App\Models\Sdm\PersonSdm::class, 'sdm_id', 'id');
    }

    public function gaji_periode()
    {
        return $this->belongsTo(GajiPeriode::class, 'periode_id', 'periode_id');
    }

}
