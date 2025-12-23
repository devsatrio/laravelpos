
$(function () {

    $('#tgl_buat').daterangepicker({
        singleDatePicker: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#list-data').DataTable({
        "paging": false,
        "bPaginate": false,
        "info": false,
        "language": {
            "sSearch": "Cari Dihalaman ini:",
        }
    });

    $('#pembuat').select2();

});

function updatestatus(kode) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
    swalWithBootstrapButtons.fire({
        title: 'Ubah Status ?',
        text: "Ubah status data dari draft menjadi approve dan update stok barang",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Yakin!',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $('#panelsatu').loading('toggle');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '/laravelpos/backend/perbaikan-stok/aksi/update-status/' + kode,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function () {
                    swalWithBootstrapButtons.fire(
                        'Info!',
                        'Data berhasil diperbarui',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    })
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal diperbarui',
                        'error'
                    )
                }, complete: function () {
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}

function hapusdata(kode) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
    swalWithBootstrapButtons.fire({
        title: 'Hapus Data ?',
        text: "Data tidak dapat di pulihkan kembali!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $('#panelsatu').loading('toggle');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: '/laravelpos/backend/perbaikan-stok/' + kode,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function () {
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Data berhasil dihapus',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    })
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                }, complete: function () {
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}