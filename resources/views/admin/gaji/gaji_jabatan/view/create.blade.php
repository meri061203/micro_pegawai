<div class="modal fade" id="form_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form method="post" id="bt_submit_create">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Gaji Jabatan</h5>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-column mb-2">
                                <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                    <span>ID Gaji Master</span>
                                </label>
                                <input type="text" id="gaji_master_id"
                                       class="form-control form-control-sm fs-sm-8 fs-lg-6"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex flex-column mb-2">
                                    <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                        <span>ID Komponen</span>
                                    </label>
                                <select data-control="select2" id="komponen_id"
                                        class="form-control form-control-sm fs-sm-8 fs-lg-6" data-allow-clear="true"
                                        data-placeholder="Pilih Komponen" required>
                                </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex flex-column mb-2">
                                    <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                        <span>ID Jabatan</span>
                                    </label>
                                <select data-control="select2" id="id_jabatan"
                                        class="form-control form-control-sm fs-sm-8 fs-lg-6" data-allow-clear="true"
                                        data-placeholder="Pilih Jabatan" required>
                                </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                           
                            <div class="d-flex flex-column mb-2">
                                <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1 required">
                                    <span>Nominal</span>
                                </label>
                                <input type="text" id="nomial"
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