@extends('admin.layouts.index')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('list')
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.gaji.payroll.index') }}" class="text-muted text-hover-primary">Payroll</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-dark">Data Penggajian</li>
@endsection

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3 class="card-label fw-bolder fs-3 mb-1">Data Transaksi Gaji</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.gaji.payroll.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-2"></i>Generate Payroll
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table id="kt_datatable" class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th class="ps-4 rounded-start">No. Transaksi</th>
                            <th>Periode</th>
                            <th>Pegawai</th>
                            <th>Unit/Jabatan</th>
                            <th class="text-end">Total Terima</th>
                            <th class="pe-4 text-end rounded-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bolder text-dark">{{ $trx->transaksi_id }}</span>
                                </td>
                                <td>
                                    @if($trx->gaji_periode)
                                        <span class="badge badge-light-info">
                                            {{ $trx->gaji_periode->bulan }} {{ $trx->gaji_periode->tahun }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bolder">{{ $trx->sdm?->person?->nama_lengkap ?? '-' }}</span>
                                        <span class="text-muted small">{{ $trx->sdm?->nip ?? $trx->sdm?->person?->nik ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-600 small">
                                        {{ $trx->sdm?->sdm_struktural?->master_unit?->unit ?? '-' }} / 
                                        {{ $trx->sdm?->sdm_struktural?->master_jabatan?->jabatan ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-end fw-bolder text-success">
                                    Rp {{ number_format($trx->total_dibayar, 0, ',', '.') }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.gaji.payroll.show', $trx->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Lihat Slip Gaji">
                                        <i class="bi bi-file-earmark-text fs-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data transaksi gaji.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kt_datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [[ 0, "desc" ]]
            });
        });
    </script>
@endsection
