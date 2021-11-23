
$(function () {
    $('#list-data').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        //ajax: '/backend/data-pembelian',
        ajax: '/laravelpos/backend/data-pembelian',
        columns: [
            {
                data: 'id', render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'kode', name: 'kode' },
            { data: 'name', name: 'name' },
            { data: 'namasupplier', name: 'namasupplier' },
            { data: 'tgl_buat', name: 'tgl_buat' },
            {
                render: function (data, type, row) {
                    return 'Rp.'+rupiah(row['total'])
                },
                "className": 'text-right',
                "data": 'total',
            },
            {
                render: function (data, type, row) {
                    return 'Rp.'+rupiah(row['terbayar'])
                },
                "className": 'text-right',
                "data": 'terbayar',
            },
            {
                render: function (data, type, row) {
                    return 'Rp.'+rupiah(row['kekurangan'])
                },
                "className": 'text-right',
                "data": 'kekurangan',
            },
            { data: 'status', name: 'status',
            "className": 'text-center', },
            {
                render: function (data, type, row) {
                    return '<a href="/laravelpos/backend/pembelian/' + row['kode'] + '/edit" class="btn btn-sm btn-success"><i class="fa fa-wrench"></i></a><button class="btn btn-sm m-1 btn-danger" onclick="hapusdata(' + row['id'] + ')"><i class="fa fa-trash"></i></button>'
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
                url: '/laravelpos/backend/pembelian/' + kode,
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