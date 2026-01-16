<div class="modal fade" id="form_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form method="post" id="bt_submit_edit">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Absensi</h5>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                    <span>ID Absensi</span>
                                </label>
                                <input type="text" id="edit_absensi_id"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="fw-bolder mb-1 required">
                                    <span>ID Jenis Absensi</span>
                                </label>
                                <select id="edit_id_jenis_absensi"
                                        class="form-select form-select-sm fs-sm-8 fs-lg-6 select2"
                                        required>
                                    <option value="">-- Pilih Jenis Absensi --</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="fw-bolder mb-1 required">
                                    <span>ID SDM</span>
                                </label>
                                <select id="edit_id_sdm"
                                        class="form-select form-select-sm fs-sm-8 fs-lg-6 select2"
                                        required>
                                    <option value="">-- Pilih SDM --</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                    <span>Tanggal</span>
                                </label>
                                <input type="date" id="edit_tanggal"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                    <span>Keterangan</span>
                                </label>
                                <input type="text" id="edit_keterangan"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-dark fs-sm-8 fs-lg-6" data-bs-dismiss="modal"
                            aria-label="Close">Close
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary fs-sm-8 fs-lg-6">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>