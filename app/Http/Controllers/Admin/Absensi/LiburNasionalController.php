<?php

namespace App\Http\Controllers\admin\Absensi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absensi\LiburNasionalRequest;
use App\Services\Absensi\LiburNasionalService;
use App\Services\Tools\ResponseService;
use App\Services\Tools\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LiburNasionalController extends Controller
{
    public function __construct(
        private readonly LiburNasionalService  $liburnasionalservice,
        private readonly TransactionService $transactionService,
        private readonly ResponseService    $responseService,
    )
    {
    }

    public function index(): View
    {
        return view('admin.absensi.libur_nasional.index');
    }

    public function list(Request $request): JsonResponse
    {
        return $this->transactionService->handleWithDataTable(
            function () use ($request) {
                return $this->liburnasionalservice->getListData($request);
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

    public function store(LiburNasionalRequest $request): JsonResponse
    {
        return $this->transactionService->handleWithTransaction(function () use ($request) {
            $data = $this->liburnasionalservice->create($request->only([
                'libur_nasional_id',
                'nama_libur_nasional',
                'tanggal',
            ]));
            return $this->responseService->successResponse('Data berhasil dibuat', $data, 201);
        });
    }

    public function show(string $id): JsonResponse
    {
        return $this->transactionService->handleWithShow(function () use ($id) {
            $data = $this->liburnasionalservice->getDetailData($id);

            return $this->responseService->successResponse('Data berhasil diambil', $data);
        });
    }

    public function update(LiburNasionalRequest $request, string $id): JsonResponse
    {
        $data = $this->liburnasionalservice->findById($id);
        if (!$data) {
            return $this->responseService->errorResponse('Data tidak ditemukan');
        }
        return $this->transactionService->handleWithTransaction(function () use ($request, $data) {
            $updatedData = $this->liburnasionalservice->update($data, $request->only([
                'libur_nasional_id',
                'nama_libur_nasional',
                'tanggal',
            ]));
            return $this->responseService->successResponse('Data berhasil diperbarui', $updatedData);
        });
    }
}