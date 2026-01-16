<?php

namespace App\Http\Controllers\admin\gaji;

use App\Http\Controllers\Controller;
use App\Http\Requests\gaji\GajiJabatanRequest;
use App\Services\Gaji\GajiJabatanService;
use App\Services\Tools\ResponseService;
use App\Services\Tools\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GajiJabatanController extends Controller
{
    public function __construct(
        private readonly GajiJabatanService $gajiJabatanService,
        private readonly TransactionService $transactionService,
        private readonly ResponseService    $responseService,
    )
    {
    }

    public function index(): View
    {
        return view('admin.gaji.gaji_jabatan.index');
    }

    public function list(Request $request): JsonResponse
    {
        return $this->transactionService->handleWithDataTable(
            function () use ($request) {
                return $this->gajiJabatanService->getListData($request);
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

    public function store(GajiJabatanRequest $request) : JsonResponse {

        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $data = $this->gajiJabatanService->create($request->only([
                'gaji_master_id',
                'komponen_id',
                'nominal',
                'id_jabatan',
            ]));
            return $this->responseService->successResponse('Data berhasil dibuat', $data, 201);
        });

    }

    public function show(string $id): JsonResponse
    {
        return $this->transactionService->handleWithShow(function () use ($id) {
            $data = $this->gajiJabatanService->getDetailData($id);

            return $this->responseService->successResponse('Data berhasil diambil', $data);
        });
    }

    public function update(GajiJabatanRequest $request, string $id): JsonResponse
    {
        $data = $this->gajiJabatanService->findById($id);
        if (!$data) {
            return $this->responseService->errorResponse('Data tidak ditemukan');
        }
        return $this->transactionService->handleWithTransaction(function () use ($request, $data) {
            $updatedData = $this->gajiJabatanService->update($data, $request->only([
                'gaji_master_id',
                'komponen_id',
                'id_jabatan',
                'nominal',
            ]));
            return $this->responseService->successResponse('Data berhasil diperbarui', $updatedData);
        });
    }
}