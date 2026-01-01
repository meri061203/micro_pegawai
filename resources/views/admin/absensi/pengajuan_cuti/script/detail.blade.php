<script defer>
    $('#form_detail').on('show.bs.modal', function (e) {
        $(this).attr('aria-hidden', 'false');

        const button = $(e.relatedTarget);
        const id = button.data('id');
        const detail = '{{ route('admin.absensi.pengajuan_cuti.show', [':id']) }}';

        DataManager.fetchData(detail.replace(':id', id))
            .then(function (response) {
                if (response.success) {

                    $('#detail_cuti_id').text(response.data.cuti_id ?? '-');
                    $('#detail_nama_lengkap').text(response.data.nama_lengkap ?? '-');
                    $('#detail_jenis_cuti').text(response.data.jenis_cuti ?? '-');
                    $('#detail_keterangan').text(response.data.keterangan ?? '-');
                    $('#detail_tanggal_mulai').text(response.data.tanggal_mulai ?? '-');
                    $('#detail_tanggal_selesai').text(response.data.tanggal_selesai ?? '-');
                    $('#detail_total_hari').text(response.data.total_hari ?? '-');
                    $('#detail_status').text(response.data.status ?? '-');
                    $('#detail_disetujui_pada').text(response.data.disetujui_pada ?? '-');

                } else {
                    Swal.fire('Peringatan', response.message, 'warning');
                }
            })
            .catch(function (error) {
                ErrorHandler.handleError(error);
            });
    });
</script>