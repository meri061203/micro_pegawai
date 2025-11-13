<script defer>
    $("#form_detail").on("show.bs.modal", function(e) {
        const button = $(e.relatedTarget);
        const id = button.data("id");
        const detail = "{{ route('admin.sdm.riwayat-pendidikan.show', ':id') }}";

        DataManager.fetchData(detail.replace(':id', id)).then(response => {
                if (response.success) {
                    const data = response.data;

                    $("#detail_jenjang").text(data.jenjang_pendidikan);
                    $("#detail_institusi").text(data.institusi);
                    $("#detail_jenis_nilai").text(data.jenis_nilai);
                    $("#detail_jurusan").text(data.jurusan);
                    $("#detail_tahun_masuk").text(data.tahun_masuk);
                    $("#detail_tahun_lulus").text(data.tahun_lulus);
                    $("#detail_sks").text(data.sks);
                    $("#detail_sumber_biaya").text(data.sumber_biaya);
        
                    if (data.file_ijazah) {
                        $('#detail_file_ijazah_name').text(data.file_ijazah);
                        const fileUrl = '{{ route('admin.view-file', [':folder', ':filename']) }}'
                            .replace(':folder', 'pendidikan')
                            .replace(':filename', data.file_ijazah);
                        $('#detail_file_ijazah_link').attr('href', fileUrl);
                        $('#detail_file_ijazah_section').show();
                        $('#no_file_ijazah_section').hide();
                    } else {
                        $('#detail_file_ijazah_section').hide();
                        $('#no_file_ijazah_section').show();
                    }
                    if (data.file_transkip) {
                        $('#detail_file_transkip_name').text(data.file_transkip);
                        const fileUrl = '{{ route('admin.view-file', [':folder', ':filename']) }}'
                            .replace(':folder', 'pendidikan')
                            .replace(':filename', data.file_transkip);
                        $('#detail_file_transkip_link').attr('href', fileUrl);
                        $('#detail_file_transkip_section').show();
                        $('#no_file_transkip_section').hide();
                    } else {
                        $('#detail_file_transkip_section').hide();
                        $('#no_file_transkip_section').show();
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
