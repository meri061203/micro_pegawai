<?php

namespace App\Services\Sdm;

use App\Models\Person\Person;
use App\Models\Sdm\PersonSdm;
use App\Services\Person\PersonService;
use Illuminate\Http\Request;
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
                'person.nik',
                'person.kk',
                'person.no_hp',
                'person.nama_lengkap',
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
}
