<script defer>
    $('#form_edit').on('show.bs.modal', function (e) {
        const button = $(e.relatedTarget);
        const id = button.data('id');
        const detail = '{{ route('admin.absensi.jadwal_kerja.show', [':id']) }}';

        DataManager.fetchData(detail.replace(':id', id))
            .then(function (response) {
                if (response.success) {
                    $('#edit_jadwal_kerja_id').val(response.data.jadwal_kerja_id);
                    $('#edit_hari').val(response.data.hari);
                    $('#edit_jam_masuk').val(response.data.jam_masuk.substring(0, 5));
                    $('#edit_jam_pulang').val(response.data.jam_pulang.substring(0, 5));
                    $('#edit_toleransi_menit').val(response.data.toleransi_menit);
                } else {
                    Swal.fire('Warning', response.message, 'warning');
                }
            }).catch(function (error) {
            ErrorHandler.handleError(error);
        });

    $('#bt_submit_edit').off('submit').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Kamu yakin?',
                text: 'Apakah datanya benar dan apa yang anda inginkan?',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true,
                cancelButtonColor: '#dd3333',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                focusCancel: true
            }).then((result) => {
                if (result.value) {
                    DataManager.openLoading();
                    const input = {
                        jadwal_kerja_id: $('#edit_jadwal_kerja_id').val(),
                        hari: $('#edit_hari').val(),
                        jam_masuk: $('#edit_jam_masuk').val(),
                        jam_pulang: $('#edit_jam_pulang').val(),
                        toleransi_menit: $('#edit_toleransi_menit').val(),

                    };
                    const update = '{{ route('admin.absensi.jadwal_kerja.update', [':id']) }}';
                    DataManager.putData(update.replace(':id', id), input).then(response => {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }
                        if (!response.success && response.errors) {
                            const validationErrorFilter = new ValidationErrorFilter('edit_');
                            validationErrorFilter.filterValidationErrors(response);
                            Swal.fire('Peringatan', 'Isian Anda belum lengkap atau tidak valid.', 'warning');
                        }
                        if (!response.success && !response.errors) {
                            Swal.fire('Warning', response.message, 'warning');
                        }
                    }).catch(error => {
                        ErrorHandler.handleError(error);
                    });
                }
            });
        });
    }).on('hidden.bs.modal', function () {
        const $m = $(this);
        $m.find('form').trigger('reset');
        $m.find('select, textarea').val('').trigger('change');
        $m.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $m.find('.invalid-feedback, .valid-feedback, .text-danger').remove();
    });
</script>