@extends('admin.layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/buttons.dataTables.min.css') }}">
@endsection
@section('list')
    <li class="breadcrumb-item text-muted">Referensi</li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>

    <li class="breadcrumb-item text-dark">Absensi</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="content flex-column-fluid">
            <div class="card mb-xl-8 mb-5 border-2 shadow">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder mb-1">Data Absensi</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a type="button" class="btn btn-sm btn-primary fs-sm-8 fs-lg-6"
                               data-bs-toggle="modal" data-bs-target="#form_create"
                               title="Tambah Absensi">Tambah Absensi</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="notice d-flex border-primary mb-4 rounded border border-dashed p-4 shadow bg-hover-light-dark">
                        <div class="d-flex flex-stack fs-sm-8 fs-lg-6">
                            <div class="row">
                                <span class="text-gray-700">Berikut ini adalah data Absensi.</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mb-8 shadow p-4 mx-0 border-hover-dark border-primary border-1 border-dashed fs-sm-8 fs-lg-6 rounded-2">
                        <table id="example"
                               class="table table-sm align-middle table-row-bordered table-row-solid gs-0 gy-2">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0 fs-sm-8 fs-lg-6">
                                <th class="min-w-75px ps-5">Aksi</th>
                                <th class="min-w-150px">ID Absensi</th>
                                <th class="min-w-150px">ID Jenis Absensi</th>
                                <th class="min-w-150px">ID SDM</th>
                                <th class="min-w-120px">Tanggal</th>
                                <th class="min-w-120px">Keterangan</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.absensi.absensi.view.detail')
    @include('admin.absensi.absensi.view.create')
    @include('admin.absensi.absensi.view.edit')
@endsection

@section('javascript')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/print.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap.min.js') }}"></script>
    <script>
    function fetchDataDropdown(
    url,
    selector,
    valueField,
    textField,
    callback = null
    ) {
    DataManager.executeOperations(url, "admin_" + url, 120)
        .then(response => {
            const $el = $(selector);
            $el.empty().append('<option value=""></option>');

            if (!response.success) {
                if (!response.errors) {
                    Swal.fire('Warning', response.message, 'warning');
                }
                return;
            }

            response.data.forEach(item => {
                const value = item[valueField] ?? '';
                const text  = item[textField] ?? '';
                $el.append(
                    `<option value="${value}">${text}</option>`
                );
            });

            // init / re-init select2
            if ($el.hasClass("select2-hidden-accessible")) {
                $el.trigger("change");
            } else {
                $el.select2({ width: '100%' });
            }

            if (typeof callback === 'function') {
                callback();
            }
        })
        .catch(error => {
            ErrorHandler.handleError(error);
        });
    }
    </script>



    @include('admin.absensi.absensi.script.list')
    @include('admin.absensi.absensi.script.create')
    @include('admin.absensi.absensi.script.edit')
    @include('admin.absensi.absensi.script.detail')
@endsection