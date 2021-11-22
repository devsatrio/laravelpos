
$(function () {
    $('#list-data').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        //ajax: '/backend/data-kategori-barang',
        ajax: '/laravelpos/backend/data-kategori-barang',
        columns: [
            {
                data: 'id', render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'nama', name: 'nama' },
            {
                render: function (data, type, row) {
                    return '<button class="btn btn-success m-1" type="button" onclick="editdata('+row['id']+')"><i class="fa fa-wrench"></i></button><button class="btn m-1 btn-danger" onclick="hapusdata(' + row['id'] + ')"><i class="fa fa-trash"></i></button>'
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

function editdata(kode) {
    $('#panel').loading('toggle');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/kategori-barang/' + kode,
        success: function (data) {
            $.each(data, function (key, value) {
                $('#nama').val(value.nama);
                $('#editform').attr('action', '/backend/kategori-barang/'+value.id);
            });
            $('#modaledit').modal('show');
        },
        complete:function(){
            $('#panel').loading('stop');
        }
    });
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'DELETE',
                url: '/laravelpos/backend/kategori-barang/' + kode,
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