<script defer>
$('#form_edit').on('show.bs.modal', function (e) {

    const button = $(e.relatedTarget);
    const id = button.data('id');

    const showUrl   = '{{ route('admin.gaji.gaji_jabatan.show', [':id']) }}';
    const updateUrl = '{{ route('admin.gaji.gaji_jabatan.update', [':id']) }}';

    // ===============================
    // 1️⃣ Load dropdown dulu
    // ===============================
    fetchDataDropdown('{{ route('api.gaji.komponengaji') }}', '#edit_komponen_id', 'komponen_id', 'nama_komponen');
    fetchDataDropdown('{{ route('api.master.jabatan') }}', '#edit_id_jabatan', 'id_jabatan', 'jabatan');

    // ===============================
    // 2️⃣ Ambil data record yg mau diedit
    // ===============================
    DataManager.fetchData(showUrl.replace(':id', id))
        .then(function (response) {
            if (response.success) {

                // isi input biasa
                $('#edit_gaji_master_id').val(response.data.gaji_master_id);
                $('#edit_nominal').val(response.data.nominal);

                // set dropdown setelah option ter-load
                setTimeout(function () {
                    $('#edit_komponen_id').val(response.data.komponen_id).trigger('change');
                    $('#edit_id_jabatan').val(response.data.id_jabatan).trigger('change');
                }, 300);

            } else {
                Swal.fire('Warning', response.message, 'warning');
            }
        }).catch(function (error) {
            ErrorHandler.handleError(error);
        });

    // ===============================
    // 3️⃣ Submit EDIT (FIX double event)
    // ===============================
    $('#form_edit').off('submit').on('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Kamu yakin?',
            text: 'Apakah datanya sudah benar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            allowOutsideClick: false
        }).then((result) => {

            if (result.isConfirmed) {

                DataManager.openLoading();

                const updateUrl = '{{ route('admin.gaji.gaji_jabatan.update', [':id']) }}';

                const input = {
                    gaji_master_id: $('#edit_gaji_master_id').val(),
                    komponen_id: $('#edit_komponen_id').val(),
                    id_jabatan: $('#edit_id_jabatan').val(),
                    nominal: $('#edit_nominal').val(),
                };

                DataManager.putData(updateUrl.replace(':id', id), input)
                    .then(response => {

                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            setTimeout(() => location.reload(), 800);
                        }

                        if (!response.success && response.errors) {
                            const validationErrorFilter = new ValidationErrorFilter('edit_');
                            validationErrorFilter.filterValidationErrors(response);
                            Swal.fire('Peringatan', 'Isian belum lengkap atau tidak valid.', 'warning');
                        }

                        if (!response.success && !response.errors) {
                            Swal.fire('Warning', response.message, 'warning');
                        }
                    })
                    .catch(error => {
                        ErrorHandler.handleError(error);
                    });
            }
        });
    });

}).on('hidden.bs.modal', function () {
    const $m = $(this);
    $m.find('form').trigger('reset');
    $m.find('select').val('').trigger('change');
    $m.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
    $m.find('.invalid-feedback').remove();
});
</script>
