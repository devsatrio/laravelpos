const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

//========================================================================================
$(function () {
    getdata();
    $('#tgl_buat').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#barang').select2({
        placeholder: "Cari berdasarkan nama/kode",
        minimumInputLength: 2,
        ajax: {
            url: '/laravelpos/backend/data-barang/detail',
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
$('#barang').on('select2:select', function (e) {
    $('#panelsatu').loading('toggle');
    var kode = $(this).val();
    var url = '/laravelpos/backend/data-barang/cari-detail/' + kode;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            $.each(data, function (key, value) {
                $('#stok_barang').val(value.stok);
                $('#stok_baru_barang').val('0');
            });
        }, complete: function () {
            document.getElementById("stok_baru_barang").focus();
            $('#panelsatu').loading('stop');
        }
    });
});


//===============================================================================================
$('#tambahbtn').on('click', function (e) {
    if ($('#stok_baru_barang').val() == "" || $('#stok_baru_barang').val() == "0") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Stok Barang tidak boleh kosong',
            confirmButtonText: 'OK'
        });
    } else {
        $('#panelsatu').loading('toggle');
        $.ajax({
            type: 'POST',
            url: '/laravelpos/backend/perbaikan-stok/aksi/add-detail-perbaikan',
            data: {
                '_token': $('input[name=_token]').val(),
                'kode': $('#kode').val(),
                'kode_barang': $('#barang').val(),
                'stok': $('#stok_barang').val(),
                'stok_baru': $('#stok_baru_barang').val(),
                'keterangan_barang': $('#keterangan_barang').val(),
            },
            success: function (data) {
            }, complete: function () {
                getdata();
                $('#barang').val(null).trigger('change');
                $('#stok_barang').val('');
                $('#keterangan_barang').val('');
                $('#stok_baru_barang').val('');
                $('#panelsatu').loading('stop');
            }
        });
    }
});

//===============================================================================================
function getdata() {
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/perbaikan-stok/aksi/list-detail-perbaikan/' + kode,
        success: function (data) {
            var rows = '';
            var no = 0;
            $.each(data, function (key, value) {
                if(value.keterangan==null||value.keterangan==''){
                    var keterangan='';
                }else{
                    var keterangan=value.keterangan;
                }
                no += 1;
                rows = rows + '<tr>';
                rows = rows + '<td class="text-center">'+no+'</td>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + value.namabarang + '</td>';
                rows = rows + '<td class="text-center">' + value.stok_lama + ' Pcs</td>';
                rows = rows + '<td class="text-center">' + value.stok_baru + ' Pcs</td>';
                rows = rows + '<td>' + keterangan + '</td>';
                rows = rows + '<td class="text-center"><button type="button" onclick="hapusdetail(' + value.id + ')" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></button></td>';
                rows = rows + '</tr>';
            });
            $('#tubuhnya').html(rows);
        }, complete: function () {
            $('#paneldua').loading('stop');
        }
    });
}

//===============================================================================================
function hapusdetail(id) {
    $('#paneldua').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/perbaikan-stok/aksi/hapus-detail-perbaikan',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': id,
        },
        success: function () {
            getdata();
        }, complete: function () {
            $('#paneldua').loading('stop');
        }
    });
}

//===============================================================================================
$('#simpanbtn').on('click', function (e) {
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
                $('#panelsatu').loading('toggle');
                $('#paneldua').loading('toggle');
                $.ajax({
                    type: 'POST',
                    url: '/laravelpos/backend/perbaikan-stok',
                    data: {
                        '_token': $('input[name=_token]').val(),
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
                $('#panelsatu').loading('toggle');
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