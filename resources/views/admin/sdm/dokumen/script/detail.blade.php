<script defer>
    $("#form_detail").on("show.bs.modal", function(e) {
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = "{{ route('admin.sdm.dokumen.show', ':id') }}";

        DataManager.fetchData(detail.replace(':id', id)).then(response => {
                if (response.success) {
                    const data = response.data;

                    $("#detail_jenis_dokumen").text(data.jenis_dokumen);
                    $("#detail_nama_file").text(data.nama_file);
                    $("#detail_file").text(data.file);
        
                    if (data.file) {
                        $('#detail_file_name').text(data.file);
                        const fileUrl = '{{ route('admin.view-file', [':folder', ':filename']) }}'
                            .replace(':folder', 'dokumen')
                            .replace(':filename', data.file);
                        $('#detail_file_link').attr('href', fileUrl);
                        $('#detail_file_section').show();
                        $('#no_file_section').hide();
                    } else {
                        $('#detail_file_section').hide();
                        $('#no_file_section').show();
                    }
                    
                } else {
                    Swal.fire("Warning", response.message, "warning");
                }
            })
            .catch(error => {
                ErrorHandler.handleError(error);
            });
    });
</script>
