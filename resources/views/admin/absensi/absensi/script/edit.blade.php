<script defer>
$('#form_edit')

    // ✅ GANTI KE shown.bs.modal
    .on('shown.bs.modal', function (e) {

        const $modal = $(this);
        const button = $(e.relatedTarget);
        const id = button.data('id');

        const detailUrl =
            '{{ route('admin.absensi.absensi.show', [':id']) }}'
                .replace(':id', id);

        // 🔄 reset validasi saja (JANGAN EMPTY SELECT)
        $modal.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $modal.find('.invalid-feedback, .text-danger').remove();

        // 🔥 FETCH DETAIL
        DataManager.fetchData(detailUrl)
            .then(response => {

                if (!response.success) {
                    Swal.fire('Warning', response.message, 'warning');
                    return;
                }

                const data = response.data;

                // FIELD BIASA
                $('#edit_absensi_id').val(data.absensi_id);
                $('#edit_tanggal').val(data.tanggal);
                $('#edit_keterangan').val(data.keterangan);

                // 🔽 JENIS ABSENSI
                fetchDataDropdown(
                    '{{ route('api.ref.jenisabsensi') }}',
                    '#edit_id_jenis_absensi',
                    'id',
                    'nama',
                    function () {
                        $('#edit_id_jenis_absensi')
                            .val(data.id_jenis_absensi)
                            .trigger('change');
                    }
                );


                // 🔽 SDM
                fetchDataDropdown(
                    '{{ route('api.ref.personsdm') }}',
                    '#edit_id_sdm',
                    'id',
                    'nama_lengkap',
                    function () {
                        $('#edit_id_sdm')
                            .val(data.id_sdm)
                            .trigger('change');
                    }
                );
            })
            .catch(ErrorHandler.handleError);

        // 🧠 submit aman
        $('#bt_submit_edit')
            .off('submit')
            .on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Kamu yakin?',
                    text: 'Apakah datanya benar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then(result => {

                    if (!result.value) return;

                    DataManager.openLoading();

                    const input = {
                        absensi_id: $('#edit_absensi_id').val(),
                        id_jenis_absensi: $('#edit_id_jenis_absensi').val(),
                        id_sdm: $('#edit_id_sdm').val(),
                        tanggal: $('#edit_tanggal').val(),
                        keterangan: $('#edit_keterangan').val(),
                    };

                    const updateUrl =
                        '{{ route('admin.absensi.absensi.update', [':id']) }}'
                            .replace(':id', id);

                    DataManager.putData(updateUrl, input)
                        .then(res => {
                            if (res.success) {
                                Swal.fire('Success', res.message, 'success');
                                setTimeout(() => location.reload(), 800);
                                return;
                            }

                            if (res.errors) {
                                new ValidationErrorFilter('edit_')
                                    .filterValidationErrors(res);
                                Swal.fire('Peringatan', 'Data tidak valid', 'warning');
                                return;
                            }

                            Swal.fire('Warning', res.message, 'warning');
                        })
                        .catch(ErrorHandler.handleError);
                });
            });
    })

    // ❌ JANGAN EMPTY SELECT
    .on('hidden.bs.modal', function () {
        const $m = $(this);
        $m.find('form')[0].reset();
        $m.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $m.find('.invalid-feedback, .text-danger').remove();
    });
</script>
