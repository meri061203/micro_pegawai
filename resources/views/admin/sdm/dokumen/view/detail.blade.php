<!-- Modal Detail -->
<div class="modal fade" id="form_detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">

                    Detail Dokumen
                </h5>
                <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Jenis Dokumen</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_jenis_dokumen"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Nama File</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_nama_file"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1">File</label>
                            <div id="detail_file_section">
                                <div class="d-flex align-items-center mb-3">
                                    <a href="#" id="detail_file_link" target="_blank" class="btn btn-sm btn-light-primary">
                                        Lihat File
                                    </a>
                                    <span id="detail_file_name" class="ms-3 text-muted"></span>
                                </div>
                            </div>
                            <div id="no_file_section" style="display: none;">
                                <div class="alert alert-warning">
                                    Tidak ada file yang diupload.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark fs-sm-8 fs-lg-6" data-bs-dismiss="modal">

                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>