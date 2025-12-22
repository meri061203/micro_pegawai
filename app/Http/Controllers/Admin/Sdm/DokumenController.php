<?php

namespace App\Http\Controllers\admin\sdm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sdm\SdmDokumenStoreRequest;
use App\Http\Requests\Sdm\SdmDokumenUpdateRequest;
use App\Services\Sdm\DokumenService;
use App\Services\Sdm\SdmDokumenService;
use App\Services\Tools\FileUploadService;
use App\Services\Tools\ResponseService;
use App\Services\Tools\TransactionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class DokumenController extends Controller
{
    public function __construct(
        private readonly DokumenService             $dokumenService,
        private readonly TransactionService         $transactionService,
        private readonly ResponseService            $responseService,
        private readonly FileUploadService          $fileUploadService,

    )
    {}

    public function index(string $uuid): View
    {
        $person = $this->dokumenService->getPersonDetailByUuid($uuid);

        return view('admin.sdm.dokumen.index', ['person' => $person, 'id' => $uuid]);
    }

    public function list(string $uuid, Request $request): JsonResponse
    {
        return $this->transactionService->handleWithDataTable(

            function () use ($uuid, $request) {
                return $this->dokumenService->getListData($uuid, $request);

            },
            [
                'action' => fn($row) => implode(' ', [
                    $this->transactionService->actionButton($row->id, 'detail'),
                    $this->transactionService->actionButton($row->id, 'edit'),
                    $this->transactionService->actionButton($row->id, 'delete'),
                ]),
            ]
        );
    }

    public function listApi(string $uuid, Request $request): JsonResponse
    {
        return $this->transactionService->handleWithDataTable(
            function () use ($uuid, $request) {
                return $this->dokumenService->getListData($uuid, $request);
            }
        );
    }

    public function store(SdmDokumenStoreRequest $request): JsonResponse
    {
        $idSdm = $this->dokumenService->resolveIdSdmFromUuid($request->uuid_person);
        if (!$idSdm) {
            return $this->responseService->errorResponse('SDM tidak ditemukan');
        }
        $fileDokumen = $request->file('file');
        if ($fileDokumen) {
            try {
                $this->fileUploadService->validateFileForUpload($fileDokumen);
            } catch (InvalidArgumentException $e) {
                return $this->responseService->errorResponse($e->getMessage(), 422);
            }
        }
        return $this->transactionService->handleWithTransaction(function () use ($request, $idSdm, $fileDokumen) {
            $payload = $request->only([
                'jenis_dokumen','nama_file',
            ]);
            $payload['id_sdm'] = $idSdm;
            $data = $this->dokumenService->create($payload);
            if ($fileDokumen) {
                $uploadResult = $this->dokumenService->handleFileUpload($fileDokumen, $idSdm, 'dokumen');
                
                $this->dokumenService->update($data,[
                    'file' => $uploadResult['filename'],
                ]);
            }
            return $this->responseService->successResponse('Data berhasil dibuat', $data, 201);
        });
    }

    public function update(SdmDokumenUpdateRequest $request, string $id): JsonResponse
    {
        $data = $this->dokumenService->findById($id);
        if (!$data) {
            return $this->responseService->errorResponse('Data tidak ditemukan');
        }
        $fileDokumen = $request->file('file');

        if ($fileDokumen) {
            try {
                $this->fileUploadService->validateFileForUpload($fileDokumen);
            } catch (InvalidArgumentException$e) {
                return $this->responseService->errorResponse($e->getMessage(), 422);
            }
        }

        return $this->transactionService->handleWithTransaction(function () use ($request, $data, $fileDokumen) {
            $payload = $request->only([
                'jenis_dokumen','nama_file',
            ]);
            $updatedData = $this->dokumenService->update($data, $payload);
            if ($fileDokumen) {
                $uploadResult = $this->dokumenService->updateFileUpload(
                    $fileDokumen,
                    $data->file_dokumen,
                    $data->id_sdm,
                    'dokumen'
                );
                $updatedData->update([
                    'file' => $uploadResult['filename'],
                ]);
            }

            return $this->responseService->successResponse('Data berhasil diperbarui', $updatedData);
        });
    }

    public function show(string $id): JsonResponse
    {
        return $this->transactionService->handleWithShow(function () use ($id) {
            $data = $this->dokumenService->getDetailData($id);

            return $this->responseService->successResponse('Data berhasil diambil', $data);
        });
    }

    public function destroy(string $id): JsonResponse
    {
        $data = $this->dokumenService->findById($id);
        if (!$data) {
            return $this->responseService->errorResponse('Data tidak ditemukan');
        }

        return $this->transactionService->handleWithTransaction(function () use ($data) {
            $this->dokumenService->delete($data);

            return $this->responseService->successResponse('Data berhasil dihapus');
        });
    }
}