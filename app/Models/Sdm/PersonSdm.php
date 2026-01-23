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
    
    protected $connection = 'mysql';

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
        'id_person' => 'integer',
        'tanggal_masuk' => 'date',
    ];

     public function person()
    {
        return $this->belongsTo(\App\Models\Person\Person::class, 'id_person');
    }

    public function sdm_struktural()
    {
        return $this->hasOne(\App\Models\Sdm\SdmStruktural::class, 'id_sdm', 'id');
    }

    public function setNipAttribute($value): void
    {
        $this->attributes['nip'] = trim(strip_tags($value));
    }

    public function setStatusPegawaiAttribute($value): void
    {
        $this->attributes['status_pegawai'] = $value ? trim(strip_tags($value)) : null;
    }

    public function setTipePegawaiAttribute($value): void
    {
        $this->attributes['tipe_pegawai'] = $value ? trim(strip_tags($value)) : null;
    }

    public function getTanggalMasukAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setIdPersonAttribute($value): void
    {
        $this->attributes['id_person'] = trim(strip_tags($value));
    }

}
























