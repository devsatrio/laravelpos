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
    $('#print_customer').html('Umum');

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

    var status = ($('#customer').val() == '' || $('#customer').val() == null) ? 'umum' : 'customer';

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
            if (data.statusbarang == false) {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Barang tidak ditemukan (' + kodebarang + ')',
                    confirmButtonText: 'OK'
                });
            } else if (data.status == false) {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Stok tidak mencukupi',
                    confirmButtonText: 'OK'
                });
            }
        }, complete: function () {
            getdata(function () {
                $('#harga_barang').val('');
                $('#jumlah_barang').val('');
                $('#total_harga_barang').val('');
                $('#diskon_barang').val('');
                $('#stok_barang').val('');
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
    $('#print_customer').html('Umum');
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
    var status = ($('#customer').val() == '' || $('#customer').val() == null) ? 'umum' : 'customer';
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/ganti-harga/' + kode + '/' + status,
        success: function (data) {
        }, complete: function () {
            getdata(function () {
                $('#paneldua').loading('stop');
            });
        }
    });
}

//========================================================================================
$('#simpancustomer').on('click', function (e) {
    var namaCust = $('#nama_customer').val().trim();
    if (namaCust === '') {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Nama customer tidak boleh kosong',
            confirmButtonText: 'OK'
        });
        return;
    }

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
            swalWithBootstrapButtons.fire(
                'Sukses!',
                'Data customer berhasil ditambahkan. Silakan cari customer pada kolom pencarian.',
                'success'
            );
        },
        error: function () {
            swalWithBootstrapButtons.fire(
                'Oops!',
                'Data customer gagal disimpan',
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
                var harga, diskon;
                if ($('#customer').val() == '' || $('#customer').val() == null) {
                    harga = value.harga_jual;
                    diskon = value.diskon;
                } else {
                    harga = value.harga_jual_customer;
                    diskon = value.diskon_customer;
                }
                $('#harga_barang').val(rupiah(harga));
                $('#jumlah_barang').val('1');
                $('#diskon_barang').val(diskon || 0);
                $('#stok_barang').val(value.stok || 0);
                $('#hitung_stok_barang').val(value.hitung_stok);
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
function hitungsubtotalbarang() {
    var harga_barang = 0;
    var jumlah = 0;
    var subtotal = 0;
    var diskon = 0;

    if ($('#harga_barang').val() != '') {
        let str = document.getElementById("harga_barang").value;
        harga_barang = parseInt(str.replace(/\./g, '')) || 0;
    }

    if ($('#jumlah_barang').val() != '') {
        jumlah = parseInt(document.getElementById("jumlah_barang").value.replace(/\./g, '')) || 0;
    }

    if ($('#diskon_barang').val() != '') {
        diskon = parseFloat(document.getElementById("diskon_barang").value) || 0;
    }
    var jumlahdiskon = Math.round(harga_barang * diskon / 100);
    subtotal = (harga_barang - jumlahdiskon) * jumlah;
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
    var stok = parseInt($('#stok_barang').val());

    if (isNaN(jumlah) || jumlah <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Jumlah barang harus lebih dari 0',
            confirmButtonText: 'OK'
        });
        return;
    }

    if ($('#hitung_stok_barang').val() == 'y') {
        if (jumlah > stok) {
            swalWithBootstrapButtons.fire({
                title: 'Oops',
                text: 'Stok tidak mencukupi',
                confirmButtonText: 'OK'
            });
            $('#jumlah_barang').val('');
            return;
        }
    }

    simpan_detail();
});

//===============================================================================================
function simpan_detail(){
    $('#panelsatu').loading('toggle');
    $('#tambahbtn').prop('disabled', true);
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
            'hitung_stok': $('#hitung_stok_barang').val(),
        },
        success: function (data) {
            if (data == false || data == "false") {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Stok tidak mencukupi',
                    confirmButtonText: 'OK'
                });
                $('#jumlah_barang').val('');
            } else {
                $('#harga_barang').val('');
                $('#jumlah_barang').val('');
                $('#diskon_barang').val('');
                $('#stok_barang').val('');
                $('#total_harga_barang').val('');
                $('#barang').val(null).trigger('change');
                $('#cari_barang_qr').val('');
                $('#hitung_stok_barang').val('');
            }
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
}

//===============================================================================================
function getdata(callback) {
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/list-detail-penjualan/' + kode,
        success: function (data) {
            var rows = '';
            var rows_print = '';
            var subtotal = 0;
            var total_diskon = 0;
            $.each(data, function (key, value) {
                var nama = value.namabarang ? value.namabarang : '-';
                rows = rows + '<tr>';
                rows = rows + '<td class="text-center"><button type="button" onclick="editdetail(' + value.id + ')" class="btn btn-success btn-sm m-1"><i class="fas fa-edit"></i></button><button type="button" onclick="hapusdetail(' + value.id + ')" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></button></td>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + nama + '</td>';
                rows = rows + '<td class="text-center">' + value.diskon + ' %</td>';
                rows = rows + '<td class="text-center">' + value.jumlah + ' Pcs</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.harga) + '</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.total) + '</td>';
                rows = rows + '</tr>';

                rows_print = rows_print + '<tr>';
                rows_print = rows_print + '<td>' + nama + '</td>';
                if (value.diskon == 0) {
                    rows_print = rows_print + '<td></td>';
                } else {
                    rows_print = rows_print + '<td>' + value.diskon + ' %</td>';
                }
                rows_print = rows_print + '<td>' + value.jumlah + ' Pcs</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.harga) + '</td>';
                rows_print = rows_print + '<td align="right"> Rp ' + rupiah(value.total) + '</td>';
                rows_print = rows_print + '</tr>';
                subtotal += parseInt(value.total) || 0;
                total_diskon += parseFloat(value.diskon) || 0;
            });
            $('#tubuhnya').html(rows);
            $('#print_detail').html(rows_print);
            $('#subtotal').val(rupiah(subtotal));
            if (total_diskon == 0) {
                $('.print_nota_diskon').hide();
            } else {
                $('.print_nota_diskon').show();
            }
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
    if (biaya_tambahan == 0) {
        $('#tr_print_biaya_tambahan').hide();
    } else {
        $('#tr_print_biaya_tambahan').show();
    }
    if (potongan == 0) {
        $('#tr_print_potongan').hide();
    } else {
        $('#tr_print_potongan').show();
    }
    $('#print_biaya_tambahan').html('Rp. ' + rupiah(biaya_tambahan));
    $('#print_potongan').html('Rp. ' + rupiah(potongan));
    $('#print_dibayar').html('Rp. ' + rupiah(dibayar));
    $('#print_total').html('Rp. ' + rupiah(totalNilai));

    kekurangan = totalNilai - dibayar;
    var kembalian = dibayar - totalNilai;

    if (kekurangan <= 0) {
        $('#kekurangan').val('0');
        $('#print_kekurangan').html('Rp. 0');
        $('#tr_print_kekurangan').hide();
    } else {
        $('#tr_print_kekurangan').show();
        $('#kekurangan').val(rupiah(kekurangan));
        $('#print_kekurangan').html('Rp. ' + rupiah(kekurangan));
    }

    if (kembalian <= 0) {
        $('#kembalian').val('0');
        $('#print_kembalian').html('Rp. 0');
        $('#tr_print_kembalian').hide();
    } else {
        $('#tr_print_kembalian').show();
        $('#kembalian').val(rupiah(kembalian));
        $('#print_kembalian').html('Rp. ' + rupiah(kembalian));
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
function editdetail(id) {
    $('#paneldua').loading('toggle');
    $('#edit_kode_barang').val('');
    $('#edit_nama_barang').val('');
    $('#edit_jumlah_barang').val('');
    $('#edit_hitung_stok_barang').val('');
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
                $('#edit_diskon_barang').val(value.diskon || 0);
                $('#edit_stok_barang').val(value.stok || 0);
                $('#edit_hitung_stok_barang').val(value.hitung_stok);
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
    var editStok = parseInt($('#edit_stok_barang').val());

    if (isNaN(editJumlah) || editJumlah <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Jumlah barang harus lebih dari 0',
            confirmButtonText: 'OK'
        });
        return;
    }

    if ($('#edit_hitung_stok_barang').val() == 'y') {
        if (editJumlah > editStok) {
            swalWithBootstrapButtons.fire({
                title: 'Oops',
                text: 'Stok tidak mencukupi',
                confirmButtonText: 'OK'
            });
            $('#edit_jumlah_barang').val('');
            return;
        }
    }
    edit_detail();
});

//===============================================================================================
function edit_detail() {
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
        success: function (data) {
            if (data == false || data == "false") {
                swalWithBootstrapButtons.fire({
                    title: 'Oops',
                    text: 'Stok tidak mencukupi',
                    confirmButtonText: 'OK'
                });
                $('#edit_jumlah_barang').val('');
            } else {
                $('#editdetailmodal').modal('hide');
                $('#edit_kode_barang').val('');
                $('#edit_nama_barang').val('');
                $('#edit_hitung_stok_barang').val('');
                $('#edit_jumlah_barang').val('');
                $('#edit_id').val('');
                $('#cari_barang_qr').val('');
            }
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
function dibayar_lunas() {
    $('#dibayar').val($('#total').val());
    carikekurangan();
}

//===============================================================================================
var is_saving_transaksi = false;
$('#simpanbtn').on('click', function (e) {
    if (is_saving_transaksi) return;

    // Pastikan nilai kosong pada biaya/potongan/dibayar diisi default '0'
    if ($('#biaya_tambahan').val().trim() === '') $('#biaya_tambahan').val('0');
    if ($('#potongan').val().trim() === '') $('#potongan').val('0');
    if ($('#dibayar').val().trim() === '') $('#dibayar').val('0');

    if ($('#kode').val() == "" || $('#tgl_order').val() == "") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Kode transaksi atau tanggal order belum terisi',
            confirmButtonText: 'OK'
        });
        return;
    }

    var subtotal = parseInt($('#subtotal').val().replace(/\./g, '')) || 0;
    if (subtotal <= 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Keranjang penjualan masih kosong',
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

    var kekurangan = 0;
    if ($('#kekurangan').val() != '') {
        kekurangan = parseInt($('#kekurangan').val().replace(/\./g, '')) || 0;
    }

    var customerVal = $('#customer').val();
    if ((!customerVal || customerVal === '') && kekurangan > 0) {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Pelanggan umum (non customer) tidak dapat bertransaksi hutang/tempo. Pembayaran harus lunas.',
            confirmButtonText: 'OK'
        });
        return;
    }

    swalWithBootstrapButtons.fire({
        title: 'Transaksi Selesai ?',
        text: "Yakin semua data telah benar ?",
        showCancelButton: true,
        confirmButtonText: 'Ya, Yakin!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            if (is_saving_transaksi) return;

            // Kunci proses simpan agar tidak bisa diklik dua kali
            is_saving_transaksi = true;
            $('#simpanbtn').prop('disabled', true);
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
                    'jenis_bayar': $('#jenis_bayar').val(),
                    'biaya_tambahan': $('#biaya_tambahan').val(),
                    'dibayar': $('#dibayar').val(),
                    'potongan': $('#potongan').val(),
                    'kekurangan': $('#kekurangan').val(),
                    'kembalian': $('#kembalian').val(),
                    'keterangan': $('#keterangan').val(),
                },
                success: function (res) {
                    if (res && res.status === false) {
                        is_saving_transaksi = false;
                        $('#simpanbtn').prop('disabled', false);
                        $('#panelsatu').loading('stop');
                        $('#paneldua').loading('stop');
                        swalWithBootstrapButtons.fire({
                            title: 'Info',
                            text: res.message || 'Transaksi gagal disimpan',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (res.message && res.message.includes('sudah tersimpan')) {
                                window.location.replace('/laravelpos/backend/penjualan');
                            }
                        });
                        return;
                    }

                    $('#print_jenis_bayar').html($('#jenis_bayar').val());
                    if ($('#keterangan').val() != '') {
                        $('#print_keterangan').html('Ket. : ' + $('#keterangan').val());
                        $('#print_keterangan').show();
                    } else {
                        $('#print_keterangan').hide();
                    }

                    var divToPrint = document.getElementById('print_div');
                    if (divToPrint) {
                        try {
                            var newWin = window.open('', 'Print-Window');
                            if (newWin) {
                                newWin.document.open();
                                newWin.document.write('<html><head><title>Cetak Nota</title></head><body onload="window.print();window.close()">' + divToPrint.innerHTML + '</body></html>');
                                newWin.document.close();
                            }
                        } catch (e) {
                            console.error('Popup cetak diblokir', e);
                        }
                    }

                    window.location.replace('/laravelpos/backend/penjualan');
                },
                error: function (xhr) {
                    is_saving_transaksi = false;
                    $('#simpanbtn').prop('disabled', false);
                    $('#panelsatu').loading('stop');
                    $('#paneldua').loading('stop');
                    var errMsg = 'Terjadi kesalahan sistem saat menyimpan transaksi';
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
