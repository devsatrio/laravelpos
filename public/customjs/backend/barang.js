
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
                    return '<a href="/backend/barang/' + row['id'] + '" class="btn btn-warning m-1"><i class="fa fa-eye"></i></a><a href="/backend/barang/' + row['id'] + '/edit" class="btn btn-success m-1"><i class="fa fa-wrench"></i></a><button class="btn btn-danger m-1" onclick="hapusdata(' + row['id'] + ')"><i class="fa fa-trash"></i></button>'
                },
                "className": 'text-center',
                "orderable": false,
                "data": null,
            },
        ],
        pageLength: 10,
        lengthMenu: [[5, 10, 20], [5, 10, 20]]
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
                url: '/backend/barang/' + kode,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function () {
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                    $('#list-data').DataTable().ajax.reload();
                }
            });
        }
    })
}
window.hapusdata = hapusdata;