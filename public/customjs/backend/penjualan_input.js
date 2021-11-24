const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

//========================================================================================
$(function () {
    $('#tgl_order').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    //========================================================================================
    $('#customer').select2({
        placeholder: "Cari berdasarkan nama/kode",
        minimumInputLength: 2,
        ajax: {
            url: '/laravelpos/backend/data-customer/detail',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.kode,
                            text: item.nama + ' (' + item.kode + ')',
                        }
                    })
                }
            },
            cache: true
        }
    });
});

//========================================================================================
function addcustomer() {
    $('#nama_customer').val('');
    $('#telp_customer').val('');
    $('#alamat_customer').val('');
    $('#keterangan_customer').val('');
    $('#modaladdcustomer').modal('show');
}

//========================================================================================
$('#simpancustomer').on('click', function (e) {
    $('#paneldua').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/customer',
        data: {
            '_token': $('input[name=_token]').val(),
            'nama': $('#nama_customer').val(),
            'telp': $('#telp_customer').val(),
            'alamat': $('#alamat_customer').val(),
            'keterangan': $('#keterangan_customer').val(),
        },
        success: function () {
        },
        error: function () {
            swalWithBootstrapButtons.fire(
                'Oops!',
                'Data gagal dihapus',
                'error'
            );
        }, complete: function () {
            $('#nama_customer').val('');
            $('#telp_customer').val('');
            $('#alamat_customer').val('');
            $('#keterangan_customer').val('');
            $('#modaladdcustomer').modal('hide');
            $('#paneldua').loading('stop');
        }
    });
});
