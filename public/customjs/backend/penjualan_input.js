const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

//========================================================================================
var harga_barang = document.getElementById("harga_barang");
var jumlah_barang = document.getElementById("jumlah_barang");
var biaya_tambahan = document.getElementById("biaya_tambahan");
var cari_barang_qr = document.getElementById("cari_barang_qr");
var potongan = document.getElementById("potongan");
var dibayar = document.getElementById("dibayar");
var total = document.getElementById("total");

//========================================================================================
$(function () {
    getdata();
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

    //========================================================================================
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

//===============================================================================================
$('#tgl_order').on('apply.daterangepicker', function (ev, picker) {
    $('#print_tgl_order').html($('#tgl_order').val());
});

//===============================================================================================
harga_barang.addEventListener("keyup", function (e) {
    harga_barang.value = formatRupiah(this.value);
    hitungsubtotalbarang();
});

jumlah_barang.addEventListener("keyup", function (e) {
    hitungsubtotalbarang();
});

potongan.addEventListener("keyup", function (e) {
    potongan.value = formatRupiah(this.value);
    carikekurangan();
});

dibayar.addEventListener("keyup", function (e) {
    dibayar.value = formatRupiah(this.value);
    carikekurangan();
});

biaya_tambahan.addEventListener("keyup", function (e) {
    biaya_tambahan.value = formatRupiah(this.value);
    carikekurangan();
});

cari_barang_qr.addEventListener("keyup", function (e) {
    var textnya = this.value;
    if (textnya.length >= 8) {
        tambahadetailbyqr(this.value);
    }
});

//========================================================================================
function tambahadetailbyqr(kodebarang) {
    if ($('#customer').val() == '' || $('#customer').val() == null) {
        var status = 'umum';
    } else {
        var status = 'customer';
    }
    $('#panelsatu').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-penjualan/add-detail-penjualan-qr',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': $('#kode').val(),
            'kode_barang': kodebarang,
            'status': status,
        },
        success: function (data) {
            if (data == false) {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Stok tidak mencukupi',
                    confirmButtonText: 'OK'
                });
            }
        }, complete: function () {
            getdata();
            $('#harga_barang').val('');
            $('#jumlah_barang').val('');
            $('#total_harga_barang').val('');
            $('#dikon_barang').val('');
            $('#stok_barang').val('');
            $('#barang').val(null).trigger('change');
            $('#cari_barang_qr').val('');
            $('#cari_barang_qr').trigger("focus");
            $('#panelsatu').loading('stop');
        }
    });
}

//========================================================================================
function addcustomer() {
    $('#nama_customer').val('');
    $('#telp_customer').val('');
    $('#alamat_customer').val('');
    $('#keterangan_customer').val('');
    $('#modaladdcustomer').modal('show');
}

//========================================================================================
function clearcustomer() {
    $('#customer').val(null).trigger('change');
    $('#print_customer').html('');
    gantiharga();
}

//========================================================================================
$('#customer').on('select2:select', function (e) {
    var datacus = $("#customer option:selected").text();
    $('#print_customer').html(datacus);
    gantiharga();
});

//========================================================================================
function gantiharga() {
    if ($('#customer').val() == '' || $('#customer').val() == null) {
        var status = 'umum';
    } else {
        var status = 'customer';
    }
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/ganti-harga/' + kode + '/' + status,
        success: function (data) {
        }, complete: function () {
            getdata();
            $('#paneldua').loading('stop');
        }
    });
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
                if ($('#customer').val() == '' || $('#customer').val() == null) {
                    var harga = value.harga_jual;
                    var diskon = value.diskon;
                } else {
                    var harga = value.harga_jual_customer;
                    var diskon = value.diskon_customer;
                }
                $('#harga_barang').val(rupiah(harga));
                $('#jumlah_barang').val('1');
                $('#diskon_barang').val(diskon);
                $('#stok_barang').val(value.stok);
            });
        }, complete: function () {
            document.getElementById("jumlah_barang").focus();
            hitungsubtotalbarang();
            $('#panelsatu').loading('stop');
        }
    });
});

//===============================================================================================
function rupiah(bilangan) {
    if (bilangan < 0) {
        bilangan = bilangan * -1;
        var number_string = bilangan.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return '-' + rupiah;
    } else {
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
function hitungsubtotalbarang() {
    var harga_barang = 0;
    var jumlah = 0;
    var subtotal = 0;
    var diskon = 0;

    if ($('#harga_barang').val() != '') {
        let str = document.getElementById("harga_barang").value;
        harga_barang = str.replace(/\./g, '');
    }

    if ($('#jumlah_barang').val() != '') {
        jumlah = document.getElementById("jumlah_barang").value;
    }

    if ($('#diskon_barang').val() != '') {
        diskon = document.getElementById("diskon_barang").value;
    }
    jumlahdiskon = harga_barang * diskon / 100;
    subtotal = parseInt(harga_barang - jumlahdiskon) * parseInt(jumlah);
    $('#total_harga_barang').val(rupiah(subtotal));
}

//===============================================================================================
$('#tambahbtn').on('click', function (e) {
    if ($('#barang').val() == "" || $('#harga_barang').val() == "" || $('#jumlah_barang').val() == "" || $('#total_harga_barang').val() == "") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Data tidak boleh kosong',
            confirmButtonText: 'OK'
        });
    } else {
        if (parseInt($('#jumlah_barang').val()) > parseInt($('#stok_barang').val())) {
            swalWithBootstrapButtons.fire({
                title: 'Oops',
                text: 'Stok tidak mencukupi',
                confirmButtonText: 'OK'
            });
            $('#jumlah_barang').val('');
        } else {
            $('#panelsatu').loading('toggle');
            $.ajax({
                type: 'POST',
                url: '/laravelpos/backend/data-penjualan/add-detail-penjualan',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'kode': $('#kode').val(),
                    'kode_barang': $('#barang').val(),
                    'harga_barang': $('#harga_barang').val(),
                    'diskon': $('#diskon_barang').val(),
                    'stok': $('#stok_barang').val(),
                    'jumlah_barang': $('#jumlah_barang').val(),
                    'total_harga_barang': $('#total_harga_barang').val(),
                },
                success: function (data) {
                    if (data == false) {
                        swalWithBootstrapButtons.fire({
                            title: 'Oops',
                            text: 'Stok tidak mencukupi',
                            confirmButtonText: 'OK'
                        });
                    }
                }, complete: function () {
                    getdata();
                    $('#harga_barang').val('');
                    $('#jumlah_barang').val('');
                    $('#diskon_barang').val('');
                    $('#stok_barang').val('');
                    $('#total_harga_barang').val('');
                    $('#barang').val(null).trigger('change');
                    $('#cari_barang_qr').val('');
                    $('#cari_barang_qr').trigger("focus");
                    $('#panelsatu').loading('stop');
                }
            });
        }
    }
});

//===============================================================================================
function getdata() {
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/list-detail-penjualan/' + kode,
        success: function (data) {
            var rows = '';
            var rows_print = '';
            var no = 0;
            var subtotal = 0;
            $.each(data, function (key, value) {
                no += 1;
                rows = rows + '<tr>';
                rows = rows + '<td class="text-center"><button type="button" onclick="editdetail(' + value.id + ')" class="btn btn-success btn-sm m-1"><i class="fas fa-edit"></i></button><button type="button" onclick="hapusdetail(' + value.id + ')" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></button></td>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + value.namabarang + '</td>';
                rows = rows + '<td class="text-center">' + value.diskon + ' %</td>';
                rows = rows + '<td class="text-center">' + value.jumlah + ' Pcs</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.harga) + '</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.total) + '</td>';
                rows = rows + '</tr>';

                rows_print = rows_print + '<tr>';
                rows_print = rows_print + '<td>' + value.namabarang + '</td>';
                rows_print = rows_print + '<td>' + value.diskon + ' %</td>';
                rows_print = rows_print + '<td>' + value.jumlah + ' Pcs</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.harga) + '</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.total) + '</td>';
                rows_print = rows_print + '</tr>';
                subtotal += parseInt(value.total);
                if ($('#customer').val() == '' || $('#customer').val() == null) {
                    var harga = value.harga_jual;
                } else {
                    var harga = value.harga_jual_customer;
                }
            });
            $('#tubuhnya').html(rows);
            $('#print_detail').html(rows_print);
            $('#subtotal').val(rupiah(subtotal));
        }, complete: function () {
            carikekurangan();
            $('#paneldua').loading('stop');
        }
    });
}

//===============================================================================================
function carikekurangan() {
    var subtotal = 0;
    var dibayar = 0;
    var potongan = 0;
    var kekurangan = 0;
    var biaya_tambahan = 0;

    if ($('#subtotal').val() != '') {
        let str = document.getElementById("subtotal").value;
        subtotal = str.replace(/\./g, '');
    }

    if ($('#dibayar').val() != '') {
        let str = document.getElementById("dibayar").value;
        dibayar = str.replace(/\./g, '');
    }

    if ($('#potongan').val() != '') {
        let str = document.getElementById("potongan").value;
        potongan = str.replace(/\./g, '');
    }

    if ($('#biaya_tambahan').val() != '') {
        let str = document.getElementById("biaya_tambahan").value;
        biaya_tambahan = str.replace(/\./g, '');
    }

    $('#total').val(rupiah(parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(potongan)));
    $('#print_biaya_tambahan').html('Rp. ' + rupiah(parseInt(biaya_tambahan)));
    $('#print_potongan').html('Rp. ' + rupiah(parseInt(potongan)));
    $('#print_dibayar').html('Rp. ' + rupiah(parseInt(dibayar)));
    $('#print_total').html('Rp. ' + rupiah(parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(potongan)));

    kekurangan = (parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(potongan)) - parseInt(dibayar);
    var kembalian = parseInt(dibayar) - (parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(potongan));

    if (kekurangan < 0) {
        $('#kekurangan').val(0);
        $('#print_kekurangan').html('Rp. 0');
    } else {
        $('#kekurangan').val(rupiah(kekurangan));
        $('#print_kekurangan').html('Rp. ' + rupiah(parseInt(kekurangan)));
    }

    if (kembalian < 0) {
        $('#kembalian').val(0);
        $('#print_kembalian').html('Rp. 0');
    } else {
        $('#kembalian').val(rupiah(kembalian));
        $('#print_kembalian').html('Rp. ' + rupiah(parseInt(kembalian)));
    }


}

//===============================================================================================
function hapusdetail(id) {
    $('#paneldua').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-penjualan/hapus-detail-penjualan',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': id,
        },
        success: function () {
            getdata();
        }, complete: function () {
            $('#cari_barang_qr').trigger("focus");
            $('#paneldua').loading('stop');
        }
    });
}

//===============================================================================================
function editdetail(id) {
    $('#paneldua').loading('toggle');
    $('#edit_kode_barang').val('');
    $('#edit_nama_barang').val('');
    $('#edit_jumlah_barang').val('');
    $('#edit_id').val('');
    var url = '/laravelpos/backend/data-penjualan/detail-penjualan/' + id;
    $.ajax({
        type: 'GET',
        url: url,
        success: function (data) {
            $.each(data, function (key, value) {
                $('#edit_kode_barang').val(value.kode_barang);
                $('#edit_nama_barang').val(value.namabarang);
                $('#edit_jumlah_barang').val(value.jumlah);
                $('#edit_harga_barang').val(rupiah(value.harga));
                $('#edit_id').val(value.id);
                $('#edit_diskon_barang').val(value.diskon);
                $('#edit_stok_barang').val(value.stok);
            });
            $('#editdetailmodal').modal('show');
        }, complete: function () {
            $('#paneldua').loading('stop');
        }
    });
}

//===============================================================================================
$('#editjumlahdetail').on('click', function (e) {
    if (parseInt($('#edit_jumlah_barang').val()) > parseInt($('#edit_stok_barang').val())) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Stok tidak mencukupi',
            confirmButtonText: 'OK'
        });
        $('#jumlah_barang').val('');
    } else {
        $('#editdetailmodal').modal('hide');
        $('#paneldua').loading('toggle');
        $.ajax({
            type: 'POST',
            url: '/laravelpos/backend/data-penjualan/edit-detail-penjualan',
            data: {
                '_token': $('input[name=_token]').val(),
                'edit_id': $('#edit_id').val(),
                'edit_kode_barang': $('#edit_kode_barang').val(),
                'edit_jumlah_barang': $('#edit_jumlah_barang').val(),
                'edit_harga_barang': $('#edit_harga_barang').val(),
            },
            success: function () {
            }, complete: function () {
                getdata();
                $('#edit_kode_barang').val('');
                $('#edit_nama_barang').val('');
                $('#edit_jumlah_barang').val('');
                $('#edit_id').val('');
                $('#cari_barang_qr').val('');
                $('#cari_barang_qr').trigger("focus");
                $('#paneldua').loading('stop');
            }
        });
    }
});

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
            if ($('#kode').val() == "" || $('#tgl_order').val() == "" || $('#subtotal').val() == "" || $('#subtotal').val() == "0" || $('#dibayar').val() == "" || $('#potongan').val() == "" || $('#biaya_tambahan').val() == "") {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Data tidak boleh kosong',
                    confirmButtonText: 'OK'
                });
            } else {
                var kekurangan = 0;
                if ($('#kekurangan').val() != '') {
                    let str = document.getElementById("kekurangan").value;
                    kekurangan = str.replace(/\./g, '');
                }
                if (parseInt(kekurangan) < 0) {
                    swalWithBootstrapButtons.fire({
                        title: 'Oops',
                        text: 'Pembayaran melebihi kekurangan',
                        confirmButtonText: 'OK'
                    });
                } else {
                    if ($('#customer').val() == null && parseInt($('#kekurangan').val()) > 0) {
                        swalWithBootstrapButtons.fire({
                            title: 'Oops',
                            text: 'Tidak bisa hutang untuk non customer',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        $('#panelsatu').loading('toggle');
                        $('#paneldua').loading('toggle');
                        $.ajax({
                            type: 'POST',
                            url: '/laravelpos/backend/penjualan',
                            data: {
                                '_token': $('input[name=_token]').val(),
                                'kode': $('#kode').val(),
                                'customer': $('#customer').val(),
                                'tgl_order': $('#tgl_order').val(),
                                'subtotal': $('#subtotal').val(),
                                'biaya_tambahan': $('#biaya_tambahan').val(),
                                'dibayar': $('#dibayar').val(),
                                'potongan': $('#potongan').val(),
                                'kekurangan': $('#kekurangan').val(),
                                'kembalian': $('#kembalian').val(),
                                'keterangan': $('#keterangan').val(),
                            },
                            success: function () {
                                var divToPrint = document.getElementById('print_div');
                                var newWin = window.open('', 'Print-Window');
                                newWin.document.open();
                                newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML + '</body></html>');
                                newWin.document.close();
                                window.location.replace('/laravelpos/backend/penjualan');
                            }
                        });
                    }
                }
            }
        }
    });

});