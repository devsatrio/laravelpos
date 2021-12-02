const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

//========================================================================================
$(function () {
    $('#tgl_buat').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
});


//===============================================================================================
$('#updatebtn').on('click', function (e) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
    swalWithBootstrapButtons.fire({
        title: 'Transaksi Selesai ?',
        text: "Yakin semua data telah benar ?",
        showCancelButton: true,
        confirmButtonText: 'Ya, Yakin!',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            if ($('#kode').val() == "" || $('#tgl_buat').val() == "") {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Data tidak boleh kosong',
                    confirmButtonText: 'OK'
                });
            } else {
                $('#paneldua').loading('toggle');
                $.ajax({
                    type: 'POST',
                    url: '/laravelpos/backend/perbaikan-stok/'+$('#kode').val(),
                    data: {
                        '_token': $('input[name=_token]').val(),
                        '_method': 'put',
                        'kode': $('#kode').val(),
                        'tgl_buat': $('#tgl_buat').val(),
                        'keterangan': $('#keterangan').val(),
                    },
                    success: function () {
                        window.location.replace('/laravelpos/backend/perbaikan-stok');
                    }
                });
            }
        }
    });

});