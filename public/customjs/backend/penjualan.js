
$(function () {
    $('#list-data').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        //ajax: '/backend/data-pembelian',
        ajax: '/laravelpos/backend/data-penjualan',
        columns: [
            {
                data: 'id', render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'kode', name: 'kode' },
            { data: 'namacustomer', name: 'namacustomer' },
            { data: 'name', name: 'name' },
            { data: 'tgl_buat', name: 'tgl_buat' },
            {
                render: function (data, type, row) {
                    return 'Rp.' + rupiah(row['total'])
                },
                "className": 'text-right',
                "data": 'total',
            },
            {
                render: function (data, type, row) {
                    return 'Rp.' + rupiah(row['terbayar'])
                },
                "className": 'text-right',
                "data": 'terbayar',
            },
            {
                render: function (data, type, row) {
                    return 'Rp.' + rupiah(row['kekurangan'])
                },
                "className": 'text-right',
                "data": 'kekurangan',
            },
            {
                render: function (data, type, row) {
                    return 'Rp.' + rupiah(row['kembalian'])
                },
                "className": 'text-right',
                "data": 'kembalian',
            },
            {
                render: function (data, type, row) {
                    if (row['status'] == 'Belum Lunas') {
                        return '<span class="badge bg-danger">' + row['status'] + '</span>';
                    } else {
                        return '<span class="badge bg-success">' + row['status'] + '</span>';
                    }
                }, data: 'status', name: 'status', className: 'text-center'
            },
            {
                render: function (data, type, row) {
                    if (row['status'] == 'Belum Lunas') {
                        return `<a href="/laravelpos/backend/penjualan/` + row['kode'] + `" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>` +
                        `<button class="btn btn-sm m-1 btn-info" onclick="bayarhutang(` + row['id'] + `)"><i class="fa fa-edit"></i></button>` +
                        `<button class="btn btn-sm m-1 btn-success" onclick="cetakulang('` + row['kode'] + `')"><i class="fa fa-print"></i></button>` +
                        `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata('` + row['kode'] + `')"><i class="fa fa-trash"></i></button>`;
                    }else{
                        return `<a href="/laravelpos/backend/penjualan/` + row['kode'] + `" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>` +
                        `<button class="btn btn-sm m-1 btn-success" onclick="cetakulang('` + row['kode'] + `')"><i class="fa fa-print"></i></button>` +
                        `<button class="btn btn-sm m-1 btn-danger" onclick="hapusdata('` + row['kode'] + `')"><i class="fa fa-trash"></i></button>`;
                    }

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
function updatestatus(id) {
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
                    $('#list-data').DataTable().ajax.reload();
                }, error: function () {
                    swalWithBootstrapButtons.fire(
                        'Oops!',
                        'Data gagal dihapus',
                        'error'
                    )
                    $('#list-data').DataTable().ajax.reload();
                }, complete: function () {
                    $('#list-data').DataTable().ajax.reload();
                    $('#panelsatu').loading('stop');
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

//===============================================================================================
function cetakulang(kode) {
    $('#panelsatu').loading('toggle');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/cetak-ulang/' + kode,
        success: function (data) {
            var rows_print = '';
            var subtotal = 0;
            $.each(data.detail, function (key, value) {
                $('#print_kode').html(value.kode);
                $('#print_tgl_order').html(value.tgl_buat);
                $('#print_pembuat').html(value.name);
                $('#print_customer').html(value.namacustomer);
                $('#print_total').html('Rp. ' + rupiah(parseInt(value.total)));
                $('#print_biaya_tambahan').html('Rp. ' + rupiah(parseInt(value.biaya_tambahan)));
                $('#print_potongan').html('Rp. ' + rupiah(parseInt(value.potongan)));
                $('#print_dibayar').html('Rp. ' + rupiah(parseInt(value.dibayar)));
                $('#print_kekurangan').html('Rp. ' + rupiah(parseInt(value.kekurangan)));
                $('#print_kembalian').html('Rp. ' + rupiah(parseInt(value.kembalian)));
            });
            $.each(data.item, function (key, value) {
                rows_print = rows_print + '<tr>';
                rows_print = rows_print + '<td>' + value.namabarang + '</td>';
                rows_print = rows_print + '<td>' + value.diskon + ' %</td>';
                rows_print = rows_print + '<td>' + value.jumlah + ' Pcs</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.harga) + '</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.total) + '</td>';
                rows_print = rows_print + '</tr>';
                subtotal += parseInt(value.total);
            });
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