<script defer>
    $("#form_detail").on("show.bs.modal", function (e) {
        $(this).attr('aria-hidden', 'false');
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = '{{ route('admin.ref.jenis-asuransi.show', [':id']) }}';
        DataManager.fetchData(detail.replace(':id', id))
            .then(function (response) {
                if (response.success) {
                    $("#detail_kode_asuransi").text(response.data.kode_asuransi);
                    $("#detail_nama_asuransi").text(response.data.nama_asuransi);
                    $("#detail_penyelenggara").text(response.data.penyelenggara);
                    $("#detail_tipe_asuransi").text(response.data.tipe_asuransi);
                    $("#null_data").hide();
                    $("#show_data").show();
                } else {
                    $("#null_data").show();
                    $("#show_data").hide();
                    Swal.fire('Peringatan', response.message, 'warning');
                }
            }).catch(function (error) {
            ErrorHandler.handleError(error);
        });
    });
</script>