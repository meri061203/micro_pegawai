<!-- Modal Detail -->
<div class="modal fade" id="form_detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">

                    Detail Riwayat Pendidikan
                </h5>
                <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Jenjang
                                Pendidikan</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_jenjang"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Institusi</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_institusi"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Jenis Nilai</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_jenis_nilai"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Jurusan</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_jurusan"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Tahun Masuk</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_tahun_masuk"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Tahun Lulus</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_tahun_lulus"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Jumlah SKS</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_sks"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="d-flex align-items-center fs-sm-8 fs-lg-6 fw-bolder mb-1">Sumber Biaya</label>
                            <p class="fw-light fs-sm-8 fs-lg-6"
                               id="detail_sumber_biaya"></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1">File Ijazah</label>
                            <div id="detail_file_ijazah_section">
                                <div class="d-flex align-items-center mb-3">
                                    <a href="#" id="detail_file_ijazah_link" target="_blank" class="btn btn-sm btn-light-primary">
                                        Lihat File
                                    </a>
                                    <span id="detail_file_ijazah_name" class="ms-3 text-muted"></span>
                                </div>
                            </div>
                            <div id="no_file_ijazah_section" style="display: none;">
                                <div class="alert alert-warning">
                                    Tidak ada file Ijazah yang diupload.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column mb-2">
                            <label class="fs-sm-8 fs-lg-6 fw-bolder mb-1">File Transkip</label>
                            <div id="detail_file_transkip_section">
                                <div class="d-flex align-items-center mb-3">
                                    <a href="#" id="detail_file_transkip_link" target="_blank" class="btn btn-sm btn-light-primary">
                                        Lihat File
                                    </a>
                                    <span id="detail_file_transkip_name" class="ms-3 text-muted"></span>
                                </div>
                            </div>
                            <div id="no_file_transkip_section" style="display: none;">
                                <div class="alert alert-warning">
                                    Tidak ada file Transkip yang diupload.
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