<?php

namespace App\Services\Sdm;


use App\Models\Person\Person;
use App\Models\Sdm\PersonSdm;
use App\Models\sdm\Sdm;
use App\Models\sdm\SdmDokumen;
use App\Services\Person\PersonService;
use App\Services\Tools\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class  DokumenService{
    public function __construct(
        private FileUploadService $fileUploadService,
        private PersonService     $personService,
    ){}

    public function getPersonDetailByUuid(string $uuid): ?Person
    {
        return $this->personService->getPersonDetailByUuid($uuid);
    }

    public function getListData(string $uuid,  Request $request): Collection
    {
        $idSdm = PersonSdm::query()
            ->join('person', 'person.id', '=', 'sdm.id_person')
            ->where('person.uuid_person', $uuid)
            ->value('sdm.id');

        if (!$idSdm) {
            return collect();
        }

        return SdmDokumen::query()
            ->leftJoin('sdm', 'sdm.id', '=', 'dokumen.id_sdm')
            ->where('dokumen.id_sdm', $idSdm)
            ->when($request->query('jenis_dokumen'), fn($q, $v) => $q->where('dokumen.jenis_dokumen', $v))
            ->orderBy('dokumen.jenis_dokumen')
            ->get();
    }

    public function create(array $data): SdmDokumen
    {
        return SdmDokumen::create($data);
    }

    public function getDetailData(string $id): ?SdmDokumen
    {
        return SdmDokumen::query()
            ->where('dokumen.id', $id)
            ->first();
    }

    public function findById(string $id): ?SdmDokumen
    {
        return SdmDokumen::find($id);
    }

    public function update(SdmDokumen $Dokumen, array $data): SdmDokumen
    {
        $Dokumen->update($data);

        return $Dokumen;
    }

    public function delete(SdmDokumen $Dokumen): void
    {
        if ($Dokumen->file_dokumen) {
            $this->fileUploadService->deleteFileByType($Dokumen->file_dokumen, 'dokumen');
        }

        $Dokumen->delete();
    }


    public function resolveIdSdmFromUuid(string $uuid): ?int
    {
        return PersonSdm::query()
            ->join('person', 'person.id', '=', 'sdm.id_person')
            ->where('person.uuid_person', $uuid)
            ->value('sdm.id');
    }

    public function handleFileUpload($file, int $idSdm, string $dokumen): ?array
    {
        if (!$file) {
            return null;
        }

        $personSdm = PersonSdm::query()
            ->join('person', 'person.id', '=', 'sdm.id_person')
            ->select([
                'person.uuid_person',
                'person.nama_lengkap',
            ])
            ->where('sdm.id', $idSdm)
            ->first();

        $uniqueCode = substr(md5(uniqid()), 0, 6);
        $template = '{id_sdm}_{nama_lengkap}_{dokumen}_{unique_code}';

        $data = [
            'id_sdm' => $personSdm->uuid_person ?? 'unknown',
            'nama_lengkap' => $personSdm->nama_lengkap ?? 'unknown',
            'dokumen' => $dokumen,
            'unique_code' => $uniqueCode,
        ];

        return $this->fileUploadService->uploadWithTemplate($file, 'dokumen', $template, $data);
    }

    public function updateFileUpload($file, string $oldFileName, int $idSdm, string $dokumen): ?array
    {
        if (!$file) {
            return null;
        }

        $personSdm = PersonSdm::query()
            ->join('person', 'person.id', '=', 'sdm.id_person')
            ->select([
                'person.uuid_person',
                'person.nama_lengkap',
            ])
            ->where('sdm.id', $idSdm)
            ->first();

        $uniqueCode = substr(md5(uniqid()), 0, 6);
        $template = '{id_sdm}_{nama_lengkap}_{dokumen}_{unique_code}';

        $data = [
            'id_sdm' => $personSdm->uuid_person ?? 'unknown',
            'nama_lengkap' => $personSdm->nama_lengkap ?? 'unknown',
            'dokumen' => $dokumen,
            'unique_code' => $uniqueCode,
        ];

        return $this->fileUploadService->updateWithTemplate($file, $oldFileName, 'dokumen', $template, $data);
    }

}