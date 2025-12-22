<script defer>
    $("#form_create").on("show.bs.modal", function (e) {
         fetchDataDropdown("{{ route('api.ref.jenjang-pendidikan') }}", '#id_jenjang_pendidikan', 'jenjang_pendidikan', 'jenjang_pendidikan');

        $("#bt_submit_create").on("submit", function (e) {
            e.preventDefault();
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire("Warning", "Ukuran file tidak boleh lebih dari 10MB", "warning");
                    return;
                }
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire("Warning", "Format file harus PDF, JPG, JPEG, atau PNG", "warning");
                    return;
                }

            }

            Swal.fire({
                title: 'Kamu yakin?',
                text: "Apakah datanya benar dan apa yang anda inginkan?",
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dd3333',
                showCancelButton: true,
                allowOutsideClick: false, allowEscapeKey: false,
                confirmButtonText: 'Ya, Simpan', cancelButtonText: 'Batal', focusCancel: true,
            }).then(result => {
                if (result.value) {
                    DataManager.openLoading();

                    const formData = new FormData();
                    formData.append('uuid_person', '{{ $id }}');
                    formData.append('jenis_dokumen', $('#jenis_dokumen').val());
                    formData.append('nama_file', $('#nama_file').val());
                    formData.append('file', $('#file').val()); 

                    if (file) {     
                        formData.append('file', file);
                    }
                    
                    const createUrl = "{{ route('admin.sdm.dokumen.store') }}";

                    DataManager.formData(createUrl, formData).then(response => {
                        if (response.success) {
                            Swal.fire("Success", response.message, "success");
                            setTimeout(() => location.reload(), 1000);
                        }
                        if (!response.success && response.errors) {
                            const validationErrorFilter = new ValidationErrorFilter();
                            validationErrorFilter.filterValidationErrors(response);
                            Swal.fire("Warning", "Validasi bermasalah", "warning");
                        }
                        if (!response.success && !response.errors) {
                            Swal.fire('Peringatan', response.message, 'warning');
                        }
                    })
                        .catch(error => {
                            ErrorHandler.handleError(error);
                        });
                }
            });
        });
    })
        .on("hidden.bs.modal", function () {
            const $m = $(this);
            $m.find('form').trigger('reset');
            $m.find('select, textarea').val('').trigger('change');
            $m.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            $m.find('.invalid-feedback, .valid-feedback, .text-danger').remove();
        });
</script>