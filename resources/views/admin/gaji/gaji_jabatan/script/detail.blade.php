<script defer>
    $("#form_detail").on("show.bs.modal", function(e) {
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = "{{ route('admin.gaji.gaji_jabatan.show', ':id') }}";

        DataManager.fetchData(detail.replace(':id', id)).then(response => {
                if (response.success) {
                    const data = response.data;
                    $("#detail_gaji_master_id").text(response.data.gaji_master_id);
                    $("#detail_komponen_id").text(response.data.komponen_id);
                    $("#detail_id_jabatan").text(response.data.id_jabatan);
                    $("#detail_nominal").text(response.data.nominal);
                    
                    $('#null_data').hide();
                    $('#show_data').show();
                } else {
                    $('#null_data').show();
                    $('#show_data').hide();
                    Swal.fire('Peringatan', response.message, 'warning');
                }
            })
            .catch(error => {
                ErrorHandler.handleError(error);
        });
    });
</script>
