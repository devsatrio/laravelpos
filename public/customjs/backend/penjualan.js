var edit_dibayar = document.getElementById("edit_dibayar");
$(function () {
    $('#tgl_buat').daterangepicker({
        singleDatePicker: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#tgl_bayar').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#list-data').DataTable({
        "paging": false,
        "bPaginate": false,
        "info":false,
        "language": {
            "sSearch": "Cari Dihalaman ini:",
        }
    });
    // $('#list-data').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     order: [[0, "desc"]],
    //     //ajax: '/backend/data-pembelian',
    //     ajax: '/laravelpos/backend/data-penjualan',
    //     columns: [
    //         {
    //             data: 'id', render: function (data, type, row, meta) {
    //                 return meta.row + meta.settings._iDisplayStart + 1;
    //             }
    //         },
    //         { data: 'kode', name: 'kode' },
    //         { data: 'namacustomer', name: 'namacustomer' },
    //         { data: 'name', name: 'name' },
    //         { data: 'tgl_buat', name: 'tgl_buat' },
    //         {
    //             render: function (data, type, row) {
    //                 return 'Rp.' + rupiah(row['total'])
    //             },
    //             "className": 'text-right',
    //             "data": 'total',
    //         },
    //         {
    //             render: function (data, type, row) {
    //                 return 'Rp.' + rupiah(row['terbayar'])
    //             },
    //             "className": 'text-right',
    //             "data": 'terbayar',
    //         },
    //         {
    //             render: function (data, type, row) {
    //                 return 'Rp.' + rupiah(row['kekurangan'])
    //             },
    //             "className": 'text-right',
    //             "data": 'kekurangan',
    //         },
    //         {
    //             render: function (data, type, row) {
    //                 return 'Rp.' + rupiah(row['kembalian'])
    //             },
    //             "className": 'text-right',
    //             "data": 'kembalian',
    //         },
    //         {
    //             render: function (data, type, row) {
    //                 if (row['status'] == 'Belum Lunas') {
    //                     return '<span class="badge bg-danger">' + row['status'] + '</span>';
    //                 } else {
    //                     return '<span class="badge bg-success">' + row['status'] + '</span>';
    //                 }
    //             }, data: 'status', name: 'status', className: 'text-center'
    //         },
    //         {
    //             render: function (data, type, row) {
    //                 if (row['status'] == 'Belum Lunas') {
    //                     return `<a href="/laravelpos/backend/penjualan/` + row['kode'] + `" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>` +
    //                         `<button class="btn btn-sm m-1 btn-info" onclick="bayarhutang('` + row['kode'] + `')"><i class="fa fa-edit"></i></button>` +
    //                         `<button class="btn btn-sm m-1 btn-secondary" onclick="cetakulang('` + row['kode'] + `')"><i class="fa fa-print"></i></button>` +
    //                         `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata('` + row['kode'] + `')"><i class="fa fa-trash"></i></button>`;
    //                 } else {
    //                     return `<a href="/laravelpos/backend/penjualan/` + row['kode'] + `" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>` +
    //                         `<button class="btn btn-sm m-1 btn-secondary" onclick="cetakulang('` + row['kode'] + `')"><i class="fa fa-print"></i></button>` +
    //                         `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata('` + row['kode'] + `')"><i class="fa fa-trash"></i></button>`;
    //                 }

    //             },
    //             "className": 'text-center',
    //             "orderable": false,
    //             "data": null,
    //         },
    //     ],
    //     pageLength: 50,
    //     lengthMenu: [[50,100, 150, 200], [50,100, 150, 200]]
    // });

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
                url: '/laravelpos/backend/penjualan/' + kode,
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
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                    // $('#list-data').DataTable().ajax.reload();
                }, complete: function () {
                    // $('#list-data').DataTable().ajax.reload();
                    $('#panelsatu').loading('stop');
                }
            });
        }
    })
}

//===============================================================================================
function formatRupiah(angka) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
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

//===============================================================================================
function cetakulang(kode) {
    $('#panelsatu').loading('toggle');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/cetak-ulang/' + kode,
        success: function (data) {
            var rows_print = '';
            var subtotal = 0;
            var total_diskon=0;
            $.each(data.detail, function (key, value) {
                $('#print_kode').html(value.kode);
                $('#print_tgl_order').html(value.tgl_buat);
                $('#print_pembuat').html(value.name);
                if(value.namacustomer==null){
                    $('#print_customer').html(' - ');
                }else{
                    $('#print_customer').html(value.namacustomer+' - '+value.customer);
                }
                $('#print_total').html('Rp. ' + rupiah(parseInt(value.total)));
                if(value.biaya_tambahan==0){
                    $('#tr_print_biaya_tambahan').hide();
                }else{
                    $('#tr_print_biaya_tambahan').show();
                }
                if(value.potongan==0){
                    $('#tr_print_potongan').hide();
                }else{
                    $('#tr_print_potongan').show();
                }
                $('#print_biaya_tambahan').html('Rp. ' + rupiah(parseInt(value.biaya_tambahan)));
                $('#print_potongan').html('Rp. ' + rupiah(parseInt(value.potongan)));
                $('#print_dibayar').html('Rp. ' + rupiah(parseInt(value.terbayar)));
                if(value.kekurangan==0){
                    $('#tr_print_kekurangan').hide();
                }else{
                    $('#tr_print_kekurangan').show();
                    $('#print_kekurangan').html('Rp. ' + rupiah(parseInt(value.kekurangan)));
                }

                if(value.kembalian==0){
                    $('#tr_print_kembalian').hide();
                }else{
                    $('#print_kembalian').html('Rp. ' + rupiah(parseInt(value.kembalian)));
                }
            });
            $.each(data.item, function (key, value) {
                rows_print = rows_print + '<tr>';
                rows_print = rows_print + '<td>' + value.namabarang + '</td>';
                if(value.diskon ==0){
                    rows_print = rows_print + '<td></td>';
                }else{
                    rows_print = rows_print + '<td>' + value.diskon + ' %</td>';
                }
                rows_print = rows_print + '<td>' + value.jumlah + ' Pcs</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.harga) + '</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.total) + '</td>';
                rows_print = rows_print + '</tr>';
                subtotal += parseInt(value.total);
                total_diskon += parseInt(value.diskon);
            });
            if(total_diskon==0){
                $('.print_nota_diskon').hide();
            }
            $('#print_detail').html(rows_print);

            var divToPrint = document.getElementById('print_div');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML + '</body></html>');
            newWin.document.close();
        }, complete: function () {
            $('#panelsatu').loading('stop');
        }
    });
}

//===============================================================================================
function bayarhutang(kode) {
    $('#panelsatu').loading('toggle');
    $('#edit_kode').val('');
    $('#edit_customer').val('');
    $('#edit_hutang').val('');
    $('#edit_kekurangan').val('');
    $('#edit_dibayar').val('');
    $('#bayarhutangmodal').modal('show');
    var url = '/laravelpos/backend/data-penjualan/cetak-ulang/' + kode;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            $.each(data.detail, function (key, value) {
                $('#edit_kode').val(value.kode);
                $('#edit_customer').val(value.customer + ' - ' + value.namacustomer);
                $('#edit_hutang').val(rupiah(parseInt(value.kekurangan)));
                $('#edit_kekurangan').val(rupiah(parseInt(value.kekurangan)));
            });
            $('#bayarhutangmodal').modal('show');
        }, complete: function () {
            $('#panelsatu').loading('stop');
        }
    });
}


//===============================================================================================
edit_dibayar.addEventListener("keyup", function (e) {
    edit_dibayar.value = formatRupiah(this.value);
    hitungkekurangan();
});

//===============================================================================================
function hitungkekurangan() {
    var kekurangan = 0;
    var dibayar = 0;
    var hutang = 0;

    if ($('#edit_hutang').val() != '') {
        let str = document.getElementById("edit_hutang").value;
        hutang = str.replace(/\./g, '');
    }

    if ($('#edit_dibayar').val() != '') {
        let str = document.getElementById("edit_dibayar").value;
        dibayar = str.replace(/\./g, '');
    }
    if (parseInt(dibayar) > parseInt(hutang)) {
        $('#edit_kekurangan').val('0');
    } else {
        kekurangan = parseInt(hutang) - parseInt(dibayar);
        $('#edit_kekurangan').val(rupiah(kekurangan));
    }

}

//===============================================================================================
$('#btnsimpanhutang').on('click', function (e) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })
    let strsatu = document.getElementById("edit_hutang").value;
    var edit_hutang = strsatu.replace(/\./g, '');
    let strdua = document.getElementById("edit_dibayar").value;
    var edit_dibayar = strdua.replace(/\./g, '');

    if (parseInt(edit_hutang) < parseInt(edit_dibayar)) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Pembayaran melebihi kekurangan',
            confirmButtonText: 'OK'
        });
    } else {
        $('#bayarhutangmodal').modal('hide');
        $('#panelsatu').loading('toggle');
        $.ajax({
            type: 'POST',
            url: '/laravelpos/backend/data-penjualan/bayar-hutang-penjualan',
            data: {
                '_token': $('input[name=_token]').val(),
                'kode': $('#edit_kode').val(),
                'hutang': $('#edit_hutang').val(),
                'dibayar': $('#edit_dibayar').val(),
                'kekurangan': $('#edit_kekurangan').val(),
                'tgl_bayar': $('#tgl_bayar').val(),
            },
            success: function () {
                swalWithBootstrapButtons.fire(
                    'Info',
                    'Data berhasil diperbarui',
                    'success'
                )
                location.reload();
            }, error: function () {
                swalWithBootstrapButtons.fire(
                    'Oops!',
                    'Data gagal diperbarui',
                    'error'
                )
                // $('#list-data').DataTable().ajax.reload();
            }, complete: function () {
                $('#panelsatu').loading('stop');
                // $('#list-data').DataTable().ajax.reload();
            }
        });
    }
});

