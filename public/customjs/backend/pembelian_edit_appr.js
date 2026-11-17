const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

var potongan = document.getElementById("potongan");
var dibayar = document.getElementById("dibayar");
var biaya_tambahan = document.getElementById("biaya_tambahan");
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
    $('#supplier').select2();
});

//===============================================================================================
potongan.addEventListener("keyup", function (e) {
    potongan.value = formatRupiah(this.value);
    carikekurangan();
});

potongan.addEventListener("blur", function (e) {
    if (this.value.trim() === '') this.value = '0';
    carikekurangan();
});

dibayar.addEventListener("keyup", function (e) {
    dibayar.value = formatRupiah(this.value);
    carikekurangan();
});

dibayar.addEventListener("blur", function (e) {
    if (this.value.trim() === '') this.value = '0';
    carikekurangan();
});

biaya_tambahan.addEventListener("keyup", function (e) {
    biaya_tambahan.value = formatRupiah(this.value);
    carikekurangan();
});

biaya_tambahan.addEventListener("blur", function (e) {
    if (this.value.trim() === '') this.value = '0';
    carikekurangan();
});

//===============================================================================================
function rupiah(bilangan) {
    if (bilangan === undefined || bilangan === null || bilangan === '' || isNaN(bilangan)) {
        return '0';
    }
    bilangan = parseInt(bilangan) || 0;
    if (bilangan < 0) {
        bilangan = bilangan * -1;
        var number_string = bilangan.toString(),
            sisa = number_string.length % 3,
            rupiahStr = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiahStr += separator + ribuan.join('.');
        }
        return '-' + rupiahStr;
    } else {
        var number_string = bilangan.toString(),
            sisa = number_string.length % 3,
            rupiahStr = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiahStr += separator + ribuan.join('.');
        }
        return rupiahStr || '0';
    }
}

//===============================================================================================
function formatRupiah(angka) {
    if (angka === undefined || angka === null || angka === '') {
        return '0';
    }
    var number_string = angka.toString().replace(/[^,\d]/g, ""),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiahStr = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? "." : "";
        rupiahStr += separator + ribuan.join(".");
    }

    rupiahStr = split[1] != undefined ? rupiahStr + "," + split[1] : rupiahStr;
    return rupiahStr || '0';
}

//===============================================================================================
function getdata(callback) {
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-pembelian/list-detail-pembelian/' + kode,
        success: function (data) {
            var rows = '';
            var subtotal = 0;
            $.each(data, function (key, value) {
                var nama = value.namabarang ? value.namabarang : '-';
                rows = rows + '<tr>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + nama + '</td>';
                rows = rows + '<td class="text-center">' + value.jumlah + ' Pcs</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.harga) + '</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.total) + '</td>';
                rows = rows + '</tr>';
                subtotal += parseInt(value.total) || 0;
            });
            $('#tubuhnya').html(rows);
            $('#subtotal').val(rupiah(subtotal));
        }, complete: function () {
            carikekurangan();
            $('#paneldua').loading('stop');
            if (typeof callback === 'function') {
                callback();
            }
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
        subtotal = parseInt(str.replace(/\./g, '')) || 0;
    }

    if ($('#dibayar').val() != '') {
        let str = document.getElementById("dibayar").value;
        dibayar = parseInt(str.replace(/\./g, '')) || 0;
    }

    if ($('#potongan').val() != '') {
        let str = document.getElementById("potongan").value;
        potongan = parseInt(str.replace(/\./g, '')) || 0;
    }

    if ($('#biaya_tambahan').val() != '') {
        let str = document.getElementById("biaya_tambahan").value;
        biaya_tambahan = parseInt(str.replace(/\./g, '')) || 0;
    }

    var totalNilai = subtotal + biaya_tambahan - potongan;
    if (totalNilai < 0) totalNilai = 0;
    $('#total').val(rupiah(totalNilai));

    kekurangan = totalNilai - dibayar;
    if (kekurangan <= 0) {
        $('#kekurangan').val('0');
    } else {
        $('#kekurangan').val(rupiah(kekurangan));
    }
}

//===============================================================================================
var is_saving_pembelian = false;
$('#simpanbtn').on('click', function (e) {
    if (is_saving_pembelian) return;

    if ($('#biaya_tambahan').val().trim() === '') $('#biaya_tambahan').val('0');
    if ($('#potongan').val().trim() === '') $('#potongan').val('0');
    if ($('#dibayar').val().trim() === '') $('#dibayar').val('0');

    if ($('#kode').val() == "" || $('#supplier').val() == "" || $('#tgl_order').val() == "") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Data kode, supplier, atau tanggal order belum terisi',
            confirmButtonText: 'OK'
        });
        return;
    }

    var subtotal = parseInt($('#subtotal').val().replace(/\./g, '')) || 0;
    if (subtotal <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Subtotal pembelian tidak boleh 0',
            confirmButtonText: 'OK'
        });
        return;
    }

    var biaya_tambahan = parseInt($('#biaya_tambahan').val().replace(/\./g, '')) || 0;
    var potongan = parseInt($('#potongan').val().replace(/\./g, '')) || 0;
    var dibayar = parseInt($('#dibayar').val().replace(/\./g, '')) || 0;

    if (potongan > (subtotal + biaya_tambahan)) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Potongan (diskon) tidak boleh melebihi subtotal + biaya tambahan',
            confirmButtonText: 'OK'
        });
        return;
    }

    var totalNilai = subtotal + biaya_tambahan - potongan;
    if (totalNilai < 0) totalNilai = 0;

    if (dibayar > totalNilai) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Pembayaran melebihi total tagihan pembelian',
            confirmButtonText: 'OK'
        });
        return;
    }

    swalWithBootstrapButtons.fire({
        title: 'Simpan Perubahan Pembelian ?',
        text: "Pastikan data pembayaran telah benar",
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            if (is_saving_pembelian) return;
            is_saving_pembelian = true;
            $('#simpanbtn').prop('disabled', true);
            $('#paneldua').loading('toggle');

            $.ajax({
                type: 'POST',
                url: '/laravelpos/backend/pembelian/' + $('#kode').val(),
                data: {
                    '_token': $('input[name=_token]').val(),
                    '_method': 'PUT',
                    'kode': $('#kode').val(),
                    'supplier': $('#supplier').val(),
                    'tgl_order': $('#tgl_order').val(),
                    'subtotal': $('#subtotal').val(),
                    'biaya_tambahan': $('#biaya_tambahan').val(),
                    'dibayar': $('#dibayar').val(),
                    'potongan': $('#potongan').val(),
                    'kekurangan': $('#kekurangan').val(),
                    'keterangan': $('#keterangan').val(),
                },
                success: function (res) {
                    if (res && res.status === false) {
                        is_saving_pembelian = false;
                        $('#simpanbtn').prop('disabled', false);
                        $('#paneldua').loading('stop');
                        swalWithBootstrapButtons.fire({
                            title: 'Oops',
                            text: res.message || 'Pembelian gagal diperbarui',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    window.location.replace('/laravelpos/backend/pembelian');
                },
                error: function (xhr) {
                    is_saving_pembelian = false;
                    $('#simpanbtn').prop('disabled', false);
                    $('#paneldua').loading('stop');
                    var errMsg = 'Terjadi kesalahan sistem saat memperbarui pembelian';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    swalWithBootstrapButtons.fire({
                        title: 'Oops',
                        text: errMsg,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});
