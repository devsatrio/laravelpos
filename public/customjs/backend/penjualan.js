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

    $('#pembuat').select2();

    $('#customer').select2();
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
                // url: '/backend/penjualan/' + kode,
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
    if (angka === null || angka === undefined) return '';
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
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
    if (bilangan === null || bilangan === undefined || isNaN(bilangan)) return '0';
    var number_string = Math.round(Number(bilangan)).toString(),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah || '0';
}

//===============================================================================================
function cetakulang(kode) {
    $('#panelsatu').loading('toggle');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/cetak-ulang/' + kode,
        // url: '/backend/data-penjualan/cetak-ulang/' + kode,
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
                if(value.keterangan==null){
                    $('#print_keterangan').html('');
                    $('#print_keterangan').hide();
                }else{
                    $('#print_keterangan').html('Ket. : '+value.keterangan);
                    $('#print_keterangan').show();
                }
                $('#print_total').html('Rp. ' + rupiah(parseInt(value.total)));
                $('#print_jenis_bayar').html(value.jenis_bayar ? value.jenis_bayar : 'Cash');
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
                    $('#tr_print_kembalian').show();
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
    var url = '/laravelpos/backend/data-penjualan/cetak-ulang/' + kode;
    // var url = '/backend/data-penjualan/cetak-ulang/' + kode;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            if (data.detail && data.detail.length > 0) {
                var value = data.detail[0];
                $('#edit_kode').val(value.kode);
                var custName = (value.namacustomer && value.namacustomer !== '') ? value.namacustomer : (value.customer ? value.customer : 'Umum');
                $('#edit_customer').val(value.customer ? (value.customer + ' - ' + custName) : custName);
                var sisaHutang = parseInt(value.kekurangan) || 0;
                $('#edit_hutang').val(rupiah(sisaHutang));
                $('#edit_kekurangan').val(rupiah(sisaHutang));
                $('#edit_dibayar').val('');
                $('#bayarhutangmodal').modal('show');
                setTimeout(function () {
                    $('#edit_dibayar').focus();
                }, 400);
            } else {
                Swal.fire('Oops!', 'Data transaksi tidak ditemukan', 'error');
            }
        },
        error: function () {
            Swal.fire('Oops!', 'Gagal mengambil data hutang', 'error');
        },
        complete: function () {
            $('#panelsatu').loading('stop');
        }
    });
}

function bayar_hutang_lunas() {
    var hutang = $('#edit_hutang').val();
    $('#edit_dibayar').val(hutang);
    hitungkekurangan();
    $('#edit_dibayar').focus();
}

//===============================================================================================
if (edit_dibayar) {
    edit_dibayar.addEventListener("keyup", function (e) {
        edit_dibayar.value = formatRupiah(this.value);
        hitungkekurangan();
    });
    edit_dibayar.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            $('#btnsimpanhutang').trigger('click');
        }
    });
}

//===============================================================================================
function hitungkekurangan() {
    var hutang = parseInt(String($('#edit_hutang').val() || '').replace(/\./g, '')) || 0;
    var dibayar = parseInt(String($('#edit_dibayar').val() || '').replace(/\./g, '')) || 0;

    if (dibayar > hutang) {
        $('#edit_kekurangan').val('0');
    } else {
        var kekurangan = hutang - dibayar;
        $('#edit_kekurangan').val(rupiah(kekurangan));
    }
}

//===============================================================================================
var is_saving_hutang = false;
$('#btnsimpanhutang').on('click', function (e) {
    if (is_saving_hutang) return;

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    var edit_kode = $('#edit_kode').val();
    var edit_hutang = parseInt(String($('#edit_hutang').val() || '').replace(/\./g, '')) || 0;
    var edit_dibayar = parseInt(String($('#edit_dibayar').val() || '').replace(/\./g, '')) || 0;

    if (!edit_kode) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Kode penjualan tidak ditemukan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (edit_dibayar <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Nominal pembayaran harus lebih dari 0',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        $('#edit_dibayar').focus();
        return;
    }

    if (edit_dibayar > edit_hutang) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Pembayaran melebihi sisa kekurangan hutang (Maks Rp ' + rupiah(edit_hutang) + ')',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    is_saving_hutang = true;
    $('#btnsimpanhutang').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-penjualan/bayar-hutang-penjualan',
        // url: '/backend/data-penjualan/bayar-hutang-penjualan',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': edit_kode,
            'hutang': $('#edit_hutang').val(),
            'dibayar': $('#edit_dibayar').val(),
            'kekurangan': $('#edit_kekurangan').val(),
            'tgl_bayar': $('#tgl_bayar').val(),
        },
        success: function (res) {
            $('#bayarhutangmodal').modal('hide');
            swalWithBootstrapButtons.fire(
                'Berhasil!',
                (res && res.message) ? res.message : 'Pembayaran hutang berhasil disimpan',
                'success'
            ).then(function () {
                location.reload();
            });
        },
        error: function (xhr) {
            var msg = 'Data gagal diperbarui';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            swalWithBootstrapButtons.fire(
                'Oops!',
                msg,
                'error'
            );
            is_saving_hutang = false;
            $('#btnsimpanhutang').prop('disabled', false).html('Simpan');
        }
    });
});

