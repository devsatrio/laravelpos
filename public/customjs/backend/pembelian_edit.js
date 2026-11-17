const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

var harga_barang = document.getElementById("harga_barang");
var jumlah_barang = document.getElementById("jumlah_barang");
var potongan = document.getElementById("potongan");
var dibayar = document.getElementById("dibayar");
var biaya_tambahan = document.getElementById("biaya_tambahan");
var cari_barang_qr = document.getElementById("cari_barang_qr");
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
    $('#barang').select2({
        placeholder: "Cari berdasarkan nama/kode",
        minimumInputLength: 2,
        ajax: {
            url: '/laravelpos/backend/data-barang-nonstok/detail',
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
harga_barang.addEventListener("keyup", function (e) {
    harga_barang.value = formatRupiah(this.value);
    hitungsubtotalbarang();
});

jumlah_barang.addEventListener("keyup", function (e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    hitungsubtotalbarang();
});

jumlah_barang.addEventListener("keydown", function (e) {
    if (e.key === "Enter" || e.keyCode === 13 || e.which === 13) {
        e.preventDefault();
        $('#tambahbtn').trigger('click');
    }
});

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

var scanQueue = [];
var is_processing_queue = false;

cari_barang_qr.addEventListener("keydown", function (e) {
    if (e.key === "Enter" || e.keyCode === 13 || e.which === 13) {
        e.preventDefault();
        var textnya = this.value.trim();
        if (textnya !== '') {
            this.value = '';
            scanQueue.push(textnya);
            processScanQueue();
        }
    }
});

//========================================================================================
function processScanQueue() {
    if (is_processing_queue || scanQueue.length === 0) {
        return;
    }
    is_processing_queue = true;
    var kodebarang = scanQueue.shift();

    $('#panelsatu').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-pembelian/add-detail-pembelian-qr',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': $('#kode').val(),
            'kode_barang': kodebarang,
        },
        success: function (data) {
            if (data == false) {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Barang tidak ditemukan (' + kodebarang + ')',
                    confirmButtonText: 'OK'
                });
            }
        }, complete: function () {
            getdata(function () {
                $('#harga_barang').val('');
                $('#jumlah_barang').val('');
                $('#total_harga_barang').val('');
                $('#barang').val(null).trigger('change');
                $('#cari_barang_qr').val('');
                $('#panelsatu').loading('stop');
                if ($('#is_use_scan').val() == 'y') {
                    $('#cari_barang_qr').trigger("focus");
                }
                is_processing_queue = false;
                if (scanQueue.length > 0) {
                    processScanQueue();
                }
            });
        }
    });
}

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
                $('#harga_barang').val(rupiah(value.harga_beli));
                $('#jumlah_barang').val('1');
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
                rows = rows + '<td class="text-center"><button type="button" onclick="editdetail(' + value.id + ')" class="btn btn-success btn-sm m-1"><i class="fas fa-edit"></i></button><button type="button" onclick="hapusdetail(' + value.id + ')" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></button></td>';
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
function editdetail(id) {
    $('#paneldua').loading('toggle');
    $('#edit_kode_barang').val('');
    $('#edit_nama_barang').val('');
    $('#edit_jumlah_barang').val('');
    $('#edit_id').val('');

    var url = '/laravelpos/backend/data-pembelian/detail-pembelian/' + id;
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
            });
            $('#editdetailmodal').modal('show');
        }, complete: function () {
            $('#paneldua').loading('stop');
        }
    });
}

$('#editdetailmodal').on('shown.bs.modal', function () {
    $('#edit_jumlah_barang').trigger('focus').select();
});

$('#edit_jumlah_barang').on('keydown', function (e) {
    if (e.key === "Enter" || e.keyCode === 13 || e.which === 13) {
        e.preventDefault();
        $('#editjumlahdetail').trigger('click');
    }
});

$('#edit_jumlah_barang').on('keyup', function (e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

//===============================================================================================
$('#editjumlahdetail').on('click', function (e) {
    var editJumlah = parseInt($('#edit_jumlah_barang').val());
    if (isNaN(editJumlah) || editJumlah <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Jumlah barang harus lebih dari 0',
            confirmButtonText: 'OK'
        });
        return;
    }

    $('#editdetailmodal').modal('hide');
    $('#paneldua').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-pembelian/edit-detail-pembelian',
        data: {
            '_token': $('input[name=_token]').val(),
            'edit_id': $('#edit_id').val(),
            'edit_kode_barang': $('#edit_kode_barang').val(),
            'edit_jumlah_barang': $('#edit_jumlah_barang').val(),
            'edit_harga_barang': $('#edit_harga_barang').val(),
        },
        success: function () {
        }, complete: function () {
            getdata(function () {
                $('#paneldua').loading('stop');
                if ($('#is_use_scan').val() == 'y') {
                    $('#cari_barang_qr').trigger("focus");
                }
            });
            $('#edit_kode_barang').val('');
            $('#edit_nama_barang').val('');
            $('#edit_jumlah_barang').val('');
            $('#edit_id').val('');
            $('#cari_barang_qr').val('');
        }
    });
});

//===============================================================================================
function hitungsubtotalbarang() {
    var harga_barang = 0;
    var jumlah = 0;
    var subtotal = 0;

    if ($('#harga_barang').val() != '') {
        let str = document.getElementById("harga_barang").value;
        harga_barang = parseInt(str.replace(/\./g, '')) || 0;
    }

    if ($('#jumlah_barang').val() != '') {
        jumlah = parseInt(document.getElementById("jumlah_barang").value.replace(/\./g, '')) || 0;
    }

    subtotal = harga_barang * jumlah;
    $('#total_harga_barang').val(rupiah(subtotal));
}

//===============================================================================================
$('#tambahbtn').on('click', function (e) {
    e.preventDefault();
    if ($('#barang').val() == "" || $('#harga_barang').val() == "" || $('#jumlah_barang').val() == "" || $('#total_harga_barang').val() == "") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Data tidak boleh kosong',
            confirmButtonText: 'OK'
        });
        return;
    }

    var jumlah = parseInt($('#jumlah_barang').val());
    if (isNaN(jumlah) || jumlah <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Jumlah barang harus lebih dari 0',
            confirmButtonText: 'OK'
        });
        return;
    }

    $('#panelsatu').loading('toggle');
    $('#tambahbtn').prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-pembelian/add-detail-pembelian',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': $('#kode').val(),
            'kode_barang': $('#barang').val(),
            'harga_barang': $('#harga_barang').val(),
            'jumlah_barang': $('#jumlah_barang').val(),
            'total_harga_barang': $('#total_harga_barang').val(),
        },
        success: function () {
            $('#harga_barang').val('');
            $('#jumlah_barang').val('');
            $('#total_harga_barang').val('');
            $('#barang').val(null).trigger('change');
            $('#cari_barang_qr').val('');
        }, complete: function () {
            getdata(function () {
                $('#tambahbtn').prop('disabled', false);
                $('#panelsatu').loading('stop');
                if ($('#is_use_scan').val() == 'y') {
                    $('#cari_barang_qr').trigger("focus");
                }
            });
        }
    });
});

//===============================================================================================
function hapusdetail(id) {
    $('#paneldua').loading('toggle');
    $.ajax({
        type: 'POST',
        url: '/laravelpos/backend/data-pembelian/hapus-detail-pembelian',
        data: {
            '_token': $('input[name=_token]').val(),
            'kode': id,
        },
        success: function () {
        }, complete: function () {
            getdata(function () {
                $('#paneldua').loading('stop');
                if ($('#is_use_scan').val() == 'y') {
                    $('#cari_barang_qr').trigger("focus");
                }
            });
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
            text: 'Keranjang pembelian masih kosong',
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
        text: "Pastikan data pembelian telah benar",
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            if (is_saving_pembelian) return;
            is_saving_pembelian = true;
            $('#simpanbtn').prop('disabled', true);
            $('#panelsatu').loading('toggle');
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
                        $('#panelsatu').loading('stop');
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
                    $('#panelsatu').loading('stop');
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
