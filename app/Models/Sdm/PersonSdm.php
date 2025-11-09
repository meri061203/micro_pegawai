<?php

namespace App\Models\Sdm;

use App\Traits\SkipsEmptyAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

final class PersonSdm extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    public $timestamps = false;

    public $incrementing = true;

    protected $table = 'sdm';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'id_person',
        'nip',
        'status_pegawai',
        'tipe_pegawai',
        'tanggal_masuk',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'integer',
        'id_person' => 'integer',
        'tanggal_masuk' => 'date',
    ];

    public function setNipAttribute($v): void
    {
        $this->attributes['nip'] = $v ? trim(strip_tags($v)) : null;
    }

    public function setStatusPegawaiAttribute($v): void
    {
        $this->attributes['status_pegawai'] = $v ? trim(strip_tags($v)) : null;
    }

    public function setTipePegawaiAttribute($v): void
    {
        $this->attributes['tipe_pegawai'] = $v ? trim(strip_tags($v)) : null;
    }

      public function getTanggalMasukAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }
}
