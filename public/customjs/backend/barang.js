
$(function () {
    $('#kategori').select2();
    $('#list-data').DataTable({
        "paging": false,
        "bPaginate": false,
        "info":false,
        "language": {
            "sSearch": "Cari Dihalaman ini:",
        }
    });
});

//===============================================================================================
function rupiah(bilangan) {
    var number_string = bilangan.toString(),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah;
}

//===============================================================================================
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: '/laravelpos/backend/barang/' + kode,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function () {
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Data berhasil dihapus',
                        'success'
                    )
                    // $('#list-data').DataTable().ajax.reload();
                    location.reload();
                },error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                    // $('#list-data').DataTable().ajax.reload();
                }
            });
        }
    })
}
window.hapusdata = hapusdata;

//===============================================================================================
function cetaklabel(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
    Swal.fire({
        title: 'Ganti Status ?',
        text: "Apakah anda yakin mengganti status pembelian dari Draft ke Approve dan otomatis mengupdate status stok barang, item pada pembelian tidak dapat di rubah setelah aksi ini",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, saya yakin',
        cancelButtonText: 'Tidak'
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
                url: '/laravelpos/backend/pembelian/update-status/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function () {
                    swalWithBootstrapButtons.fire(
                        'Updated!',
                        'Status data berhasil diperbarui',
                        'success'
                    )
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Status data gagal diperbarui',
                        'error'
                    )
                }, complete: function () {
                    // $('#list-data').DataTable().ajax.reload();
                    location.reload();
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}