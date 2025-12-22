<!-- Modal Create -->
<div class="modal fade" id="form_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">

                    Tambah Dokumen
                </h5>
                <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <form method="post" id="bt_submit_create" novalidate enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="uuid_person" value="{{ $id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex flex-column mb-2">
                                <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1 required">Jenis Dokumen</label>
                                <input type="text" name="jenis_dokumen"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       id="jenis_dokumen" placeholder="Masukkan Jenis Dokumen" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column mb-2">
                                <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1">Nama File</label>
                                <input type="text" name="nama_file"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       id="nama_file" placeholder="Masukkan Nama File" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column mb-2">
                                <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1">File</label>
                                <input type="file" id="file" name="file"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text fs-sm-9 fs-lg-7 text-muted">
                                    Format file: PDF, JPG, JPEG, PNG. Maksimal 5MB
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-dark fs-sm-8 fs-lg-6" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary fs-sm-8 fs-lg-6">
                        <span class="indicator-label">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>