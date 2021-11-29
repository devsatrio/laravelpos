
$(function () {
    $('#list-data').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        //ajax: '/backend/data-barang',
        ajax: '/laravelpos/backend/data-barang',
        columns: [
            {
                data: 'id', render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'kode', name: 'kode' },
            { data: 'nama', name: 'nama' },
            { data: 'namakategori', name: 'namakategori' },
            {
                render: function (data, type, row) {
                    return 'Rp. '+rupiah(row['harga_jual']);
                },
                "className": 'text-right',
                "data": 'harga_jual',
            },
            {
                render: function (data, type, row) {
                    return 'Rp. '+rupiah(row['harga_jual_customer']);
                },
                "className": 'text-right',
                "data": 'harga_jual_customer',
            },
            {
                render: function (data, type, row) {
                    return row['stok']+' Pcs';
                },
                "className": 'text-center',
                "data": 'stok',
            },
            {
                render: function (data, type, row) {
                    return '<a href="/laravelpos/backend/barang/' + row['id'] + '" class="btn btn-warning m-1"><i class="fa fa-eye"></i></a><a href="/laravelpos/backend/barang/' + row['id'] + '/edit" class="btn btn-success m-1"><i class="fa fa-wrench"></i></a><button class="btn btn-danger m-1" onclick="hapusdata(' + row['id'] + ')"><i class="fa fa-trash"></i></button>'
                },
                "className": 'text-center',
                "orderable": false,
                "data": null,
            },
        ],
        pageLength: 100,
        lengthMenu: [[100, 300, 500, 900], [100, 300, 500, 900]]
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
                    $('#list-data').DataTable().ajax.reload();
                },error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                    $('#list-data').DataTable().ajax.reload();
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
                    $('#list-data').DataTable().ajax.reload();
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}