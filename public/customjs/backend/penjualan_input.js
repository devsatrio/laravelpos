const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true
});

var harga_barang = document.getElementById("harga_barang");
var jumlah_barang = document.getElementById("jumlah_barang");
var biaya_tambahan = document.getElementById("biaya_tambahan");
var cari_barang_qr = document.getElementById("cari_barang_qr");

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
harga_barang.addEventListener("keyup", function (e) {
    harga_barang.value = formatRupiah(this.value);
    hitungsubtotalbarang();
});

jumlah_barang.addEventListener("keyup", function (e) {
    hitungsubtotalbarang();
});

cari_barang_qr.addEventListener("keyup", function (e) {
    var textnya =this.value;
    if(textnya.length >= 8){
        tambahadetailbyqr(this.value);
    }
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
function clearcustomer() {
    $('#customer').val(null).trigger('change');
    gantiharga();
}

//========================================================================================
function gantiharga() {
    if($('#customer').val()=='' || $('#customer').val()==null){
        var status = 'umum';
    }else{
        var status = 'customer';
    }
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-penjualan/ganti-harga/' + kode+'/'+status,
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
                if($('#customer').val()=='' || $('#customer').val()==null){
                    var harga = value.harga_jual;
                }else{
                    var harga = value.harga_jual_customer;
                }
                $('#harga_barang').val(rupiah(harga));
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

    if ($('#harga_barang').val() != '') {
        let str = document.getElementById("harga_barang").value;
        harga_barang = str.replace(/\./g, '');
    }

    if ($('#jumlah_barang').val() != '') {
        jumlah = document.getElementById("jumlah_barang").value;
    }

    subtotal = parseInt(harga_barang) * parseInt(jumlah);
    $('#total_harga_barang').val(rupiah(subtotal));
}

//===============================================================================================
$('#tambahbtn').on('click', function (e) {
    if ($('#barang').val() == "" || $('#harga_barang').val() == "" || $('#jumlah_barang').val() == "" || $('#total_harga_barang').val() == "") {
        swalWithBootstrapButtons.fire({
            title: 'Oops',
            text: 'Data tidak boleh kosog',
            confirmButtonText: 'OK'
        });
    } else {
        var kekurangan = 0; 
        if ($('#kekurangan').val() != '') {
            let str = document.getElementById("kekurangan").value;
            kekurangan = str.replace(/\./g, '');
        }
        if(parseInt(kekurangan)<0){
            swalWithBootstrapButtons.fire({
                title: 'Oops',
                text: 'Pembayaran melebihi kekurangan',
                confirmButtonText: 'OK'
            });
        }else{
            $('#panelsatu').loading('toggle');
            $.ajax({
                type: 'POST',
                url: '/laravelpos/backend/data-penjualan/add-detail-penjualan',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'kode': $('#kode').val(),
                    'kode_barang': $('#barang').val(),
                    'harga_barang': $('#harga_barang').val(),
                    'jumlah_barang': $('#jumlah_barang').val(),
                    'total_harga_barang': $('#total_harga_barang').val(),
                },
                success: function () {
                }, complete: function () {
                    getdata();
                    $('#harga_barang').val('');
                    $('#jumlah_barang').val('');
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
            var no = 0;
            var subtotal = 0;
            $.each(data, function (key, value) {
                no += 1;
                rows = rows + '<tr>';
                rows = rows + '<td class="text-center"><button type="button" onclick="editdetail(' + value.id + ')" class="btn btn-success btn-sm m-1"><i class="fas fa-edit"></i></button><button type="button" onclick="hapusdetail(' + value.id + ')" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></button></td>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + value.namabarang + '</td>';
                rows = rows + '<td class="text-center">' + value.jumlah + ' Pcs</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.harga) + '</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.total) + '</td>';
                rows = rows + '</tr>';
                subtotal += parseInt(value.total);
                if($('#customer').val()=='' || $('#customer').val()==null){
                    var harga = value.harga_jual;
                }else{
                    var harga = value.harga_jual_customer;
                }
            });
            $('#tubuhnya').html(rows);
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
    kekurangan = parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(dibayar) - parseInt(potongan);
    $('#kekurangan').val(rupiah(kekurangan));
}