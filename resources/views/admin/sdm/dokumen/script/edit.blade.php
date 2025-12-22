<script defer>

    $("#form_edit").on("show.bs.modal", function (e) {
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = "{{ route('admin.sdm.dokumen.show', ':id') }}";

        DataManager.fetchData(detail.replace(':id', id)).then(response => {
            if (response.success) {
                const data = response.data;
                $("#edit_jenis_dokumen").val(data.jenis_dokumen);
                $("#edit_nama_file").val(data.nama_file).trigger('change');
    
                if (data.file) {
                    $('#current_filename').text(data.file);
                    const fileUrl = '{{ route('admin.view-file', [':folder', ':filename']) }}'
                        .replace(':folder', 'dokumen')
                        .replace(':filename', data.file);
                    $('#current_file_link').attr('href', fileUrl);
                    $('#current_file_info').show();
                } else {
                    $('#current_file_info').hide();
                }

            } else {
                Swal.fire("Warning", response.message, "warning");
            }
        })
            .catch(error => {
                ErrorHandler.handleError(error);
            });

        $("#bt_submit_edit").on("submit", function (e) {
            e.preventDefault();
            const fileInput = document.getElementById('edit_file');
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
                    formData.append('jenis_dokumen', $('#edit_jenis_dokumen').val());
                    formData.append('nama_file', $('#edit_nama_file').val());
                    
                    
                    if (file) {
                        formData.append('file', file);
                    }
        
                    const updateUrl = "{{ route('admin.sdm.dokumen.update', ':id') }}";
                    DataManager.formData(updateUrl.replace(":id", id), formData).then(response => {

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

                            console.error('Error creating dokumen:', error);
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