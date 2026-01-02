<?php

namespace App\Http\Controllers\admin\Absensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absensi\AbsensiRequest;
use App\Services\Absensi\AbsensiService;
use App\Services\Tools\ResponseService;
use App\Services\Tools\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AbsensiController extends Controller
{
    public function __construct(
        private readonly AbsensiService     $AbsensiService,
        private readonly TransactionService $transactionService,
        private readonly ResponseService    $responseService,
    )
    {
    }

    public function index(): View
    {
        return view('admin.absensi.absensi.index');
    }

    public function list(Request $request): JsonResponse
    {
        return $this->transactionService->handleWithDataTable(
            function () use ($request) {
                return $this->AbsensiService->getListData($request);
            },
            [
                'action' => function ($row) {
                    $rowId = $row->id;

                    return implode(' ', [
                        $this->transactionService->actionButton($rowId, 'detail'),
                        $this->transactionService->actionButton($rowId, 'edit'),
                    ]);
                },
            ]
        );
    }

    public function store(AbsensiRequest $request) : JsonResponse {

        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $data = $this->AbsensiService->create($request->only([
                'absensi_id',
                'id_sdm',
                'id_jenis_absensi',
                'tanggal',
                'keterangan',
            ]));
            return $this->responseService->successResponse('Data berhasil dibuat', $data, 201);
        });

    }

    public function show(string $id): JsonResponse
    {
        return $this->transactionService->handleWithShow(function () use ($id) {
            $data = $this->AbsensiService->getDetailData($id);

            return $this->responseService->successResponse('Data berhasil diambil', $data);
        });
    }

    public function update(AbsensiRequest $request, string $id): JsonResponse
    {
        $data = $this->AbsensiService->findById($id);
        if (!$data) {
            return $this->responseService->errorResponse('Data tidak ditemukan');
        }
        return $this->transactionService->handleWithTransaction(function () use ($request, $data) {
            $updatedData = $this->AbsensiService->update($data, $request->only([
                'absensi_id',
                'id_sdm',
                'id_jenis_absensi',
                'tanggal',
                'keterangan',
            ]));
            return $this->responseService->successResponse('Data berhasil diperbarui', $updatedData);
        });
    }
}