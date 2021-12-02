
$(function () {
    $('#list-data').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        //ajax: '/backend/data-admin',
        ajax: '/laravelpos/backend/data-perbaikan-stok',
        columns: [
            {
                data: 'id', render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'tgl_buat', name: 'tgl_buat' },
            { data: 'keterangan', name: 'keterangan' },
            {
                render: function (data, type, row) {
                    if (row['status'] == 'Draft') {
                        return '<span class="badge bg-warning">' + row['status'] + '</span>';
                    } else {
                        return '<span class="badge bg-primary">' + row['status'] + '</span>';
                    }
                }, data: 'status', name: 'status', className: 'text-center'
            },
            {
                render: function (data, type, row) {
                    if (row['status'] == 'Draft') {
                        return `<a href="/laravelpos/backend/perbaikan-stok/` + row['kode'] + `" class="btn btn-sm m-1 btn-warning"><i class="fa fa-eye"></i></a>` +
                        `<a href="/laravelpos/backend/perbaikan-stok/` + row['id'] + `/edit" class="btn btn-sm m-1 btn-success"><i class="fa fa-wrench"></i></a>` +
                        `<button class="btn btn-sm m-1 btn-info" onclick="updatestatus('` + row['kode'] + `')"><i class="fa fa-check"></i></button>` +
                        `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata(` + row['id'] + `)"><i class="fa fa-trash"></i></button>`;
                    } else {
                        return `<a href="/laravelpos/backend/perbaikan-stok/` + row['kode'] + `" class="btn btn-sm m-1 btn-warning"><i class="fa fa-eye"></i></a>` +
                        `<a href="/laravelpos/backend/perbaikan-stok/` + row['id'] + `/edit" class="btn btn-sm m-1 btn-success"><i class="fa fa-wrench"></i></a>` +
                       `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata(` + row['id'] + `)"><i class="fa fa-trash"></i></button>`;
                    }
                    
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
        text: "Ubah status data dari draft menjadi approve dan update stok baran",
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
                    )
                    $('#list-data').DataTable().ajax.reload();
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal diperbarui',
                        'error'
                    )
                    $('#list-data').DataTable().ajax.reload();
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
                    )
                    $('#list-data').DataTable().ajax.reload();
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                    $('#list-data').DataTable().ajax.reload();
                }, complete: function () {
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}