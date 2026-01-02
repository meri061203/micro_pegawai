<?php

namespace App\Services\Sdm;

use App\Models\Person\Person;
use App\Models\Sdm\PersonSdm;
use App\Models\sdm\Sdm;
use App\Services\Person\PersonService;
use App\Services\Tools\FileUploadService;
use Illuminate\Support\Collection;

final readonly class PersonSdmService
{
    public function __construct(
        private PersonService $personService,
    )
    {
    }

    public function getPersonDetailByUuid(string $uuid): ?Person
    {
        return $this->personService->getPersonDetailByUuid($uuid);
    }

    public function getHistoriByUuid(string $uuid): Collection
    {
       return PersonSdm::query()
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select([
                'sdm.id',
                'sdm.nip',
                'sdm.status_pegawai',
                'sdm.tipe_pegawai',
                'sdm.tanggal_masuk',
                'person.nama_lengkap',
                'person.uuid_person',
            ])
            ->where('person.uuid_person', $uuid)
            ->orderByDesc('sdm.tanggal_masuk')
            ->get();
    }

    public function getListData(): Collection
    {
        return PersonSdm::query()
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select([
                'sdm.id',
                'sdm.nip',
                'sdm.status_pegawai',
                'sdm.tipe_pegawai',
                'sdm.tanggal_masuk',
                'person.nama_lengkap',
                'person.uuid_person',
            ])
            ->get();
    }

    public function create(array $data): PersonSdm
    {
        return PersonSdm::create($data);
    }

    public function getDetailData(string $id): ?PersonSdm
    {
        return PersonSdm::query()
            ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
            ->select([
                'sdm.*',
                'person.nama_lengkap',
                'person.tempat_lahir',
                'person.nik',
                'person.kk',
                'person.tanggal_lahir',
                'person.alamat',
                'person.no_hp',
            ])
            ->where('sdm.id', $id)
            ->first();
    }

    public function findById(string $id): ?PersonSdm
    {
        return PersonSdm::find($id);
    }

    public function update(PersonSdm $personSdm, array $data): PersonSdm
    {
        $personSdm->update($data);

        return $personSdm;
    }

    public function checkDuplicate(int $idPerson): bool
    {
        return PersonSdm::where('id_person', $idPerson)
            ->exists();
    }


    public function findByNik(string $nik): ?Person
    {
        return $this->personService->findByNik($nik);
    }

    public function delete($data): bool
    {
        return $data->delete();
    }

    public function getListDataOrdered(
    string $orderBy = 'sdm.id',
    string $direction = 'asc'
    ): Collection {
    return PersonSdm::query()
        ->leftJoin('person', 'person.id', '=', 'sdm.id_person')
        ->select([
            'sdm.id',
            'sdm.nip',
            'sdm.status_pegawai',
            'sdm.tipe_pegawai',
            'sdm.tanggal_masuk',
            'person.nama_lengkap',
            'person.uuid_person',
        ])
        ->orderBy($orderBy, $direction)
        ->get();
    }



}