<script defer>
    $("#form_detail").on("show.bs.modal", function(e) {
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = "{{ route('admin.absensi.absensi.show', ':id') }}";

        DataManager.fetchData(detail.replace(':id', id)).then(response => {
                if (response.success) {
                    const data = response.data;
                    $("#detail_absensi_id").text(response.data.absensi_id);
                    $("#detail_id_jenis_absensi").text(response.data.id_jenis_absensi);
                    $("#detail_id_sdm").text(response.data.id_sdm);
                    $("#detail_tanggal").text(response.data.tanggal);
                    $("#detail_keterangan").text(response.data.keterangan);
                    
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
