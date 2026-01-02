<script defer>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('form_create');
    const modalInstance = new bootstrap.Modal(modalEl);
    document.querySelectorAll('[data-bs-target="#form_create"]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            modalInstance.show();
        });
    });

    $('#form_create').on('shown.bs.modal', function () {
        fetchDataDropdown(
            '{{ route('api.ref.jenisabsensi') }}',
            '#id_jenis_absensi',
            'id',
            'nama'
        );
    });

    $('#form_create').on('shown.bs.modal', function () {
        fetchDataDropdown(
            '{{ route('api.ref.personsdm') }}',
            '#id_sdm',
            'id',
            'nama_lengkap'
        );
    });

    
    $('#bt_submit_create').on('submit', function (e) {
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
            if (!result.isConfirmed) return;

            DataManager.openLoading();

            const input = {
                absensi_id: $('#absensi_id').val(),
                id_jenis_absensi: $('#id_jenis_absensi').val(),
                id_sdm: $('#id_sdm').val() || null,
                tanggal: $('#tanggal').val() || null,
                keterangan: $('#keterangan').val() || null
            };

            console.log('Data yang akan dikirim:', input);

            DataManager.postData(
                '{{ route('admin.absensi.absensi.store') }}',
                input
            ).then(response => {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else if (response.errors) {
                    const validationErrorFilter = new ValidationErrorFilter();
                    validationErrorFilter.filterValidationErrors(response);
                    Swal.fire('Warning', 'validasi bermasalah', 'warning');
                } else {
                    Swal.fire('Peringatan', response.message, 'warning');
                }
            }).catch(error => {
                ErrorHandler.handleError(error);
            });
        });
    });


    $('#form_create').on('hidden.bs.modal', function () {
        const $m = $(this);
        $m.find('form')[0].reset();
        $m.find('select, textarea').val('').trigger('change');
        $m.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $m.find('.invalid-feedback, .valid-feedback, .text-danger').remove();
    });

});
</script>
