<?php

namespace App\Models\sdm;


use App\Traits\SkipsEmptyAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Str;
use Carbon\Carbon;

final class SdmDokumen extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
    use SkipsEmptyAudit {
        SkipsEmptyAudit::transformAudit insteadof AuditableTrait;
    }

    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'dokumen';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'id',
        'id_sdm',
        'nama_file',
        'file',
        'jenis_dokumen',

    ];

    protected $guarded = [
        'id',
    ];

    protected $casts =[
        'id_sdm' => 'integer',
        'id' => 'integer',

    ];

    public function setIdSdmAttribute($value): void
    {
        $this->attributes['id_sdm'] = trim(strip_tags($value));
    }

    public function setNamaDokumenAttribute($value): void
    {
        $this->attributes['nama_file'] = strtoupper(trim(strip_tags($value)));
    }


}