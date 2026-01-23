@extends('admin.layouts.index')

@section('list')
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.gaji.payroll.index') }}" class="text-muted text-hover-primary">Payroll</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-dark">Generate Payroll</li>
@endsection

@section('content')
    <div class="row g-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-flush shadow-sm">
                <div class="card-header">
                    <h3 class="card-title fw-bolder">Generate Payroll</h3>
                </div>
                <div class="card-body py-5">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-success me-3">
                                <i class="bi bi-check-circle fs-2"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-dark">Berhasil</h4>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-3">
                                <i class="bi bi-exclamation-triangle fs-2"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-dark">Gagal</h4>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.gaji.payroll.store') }}" method="POST" id="form-generate">
                        @csrf
                        
                        <div class="mb-10">
                            <label for="periode_id" class="form-label required fw-bold">Pilih Periode</label>
                            <select name="periode_id" id="periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode..." required>
                                <option value=""></option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->periode_id }}">
                                        {{ $p->periode_id }} - {{ $p->bulan }} {{ $p->tahun }} ({{ $p->status }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hanya periode berstatus DRAFT yang dapat dipilih.</div>
                        </div>

                        <div class="mb-10">
                            <label for="sdm_id" class="form-label fw-bold">Pilih Pegawai (Opsional)</label>
                            <select name="sdm_id" id="sdm_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Semua Pegawai (Generate Masal)">
                                <option value="">-- Semua Pegawai (Generate Masal) --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->person->nama_lengkap ?? $emp->nip }} ({{ $emp->nip }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Biarkan kosong untuk memproses semua pegawai aktif. Jika dipilih, hanya akan memproses satu pegawai.</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.gaji.payroll.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                <span class="indicator-label">Mulai Proses</span>
                                <span class="indicator-progress">Processing... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            // Initialize Select2 if not already auto-initialized by theme
            // $('[data-control="select2"]').select2();

            $('#form-generate').on('submit', function() {
                var btn = $('#btn-submit');
                btn.attr('data-kt-indicator', 'on');
                btn.attr('disabled', true);
            });
        });
    </script>
@endsection
