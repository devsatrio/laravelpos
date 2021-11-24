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

dibayar.addEventListener("keyup", function (e) {
    dibayar.value = formatRupiah(this.value);
    carikekurangan();
});

biaya_tambahan.addEventListener("keyup", function (e) {
    biaya_tambahan.value = formatRupiah(this.value);
    carikekurangan();
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
function getdata() {
    $('#paneldua').loading('toggle');
    var kode = $('#kode').val();
    $('#tubuhnya').html('');
    $.ajax({
        type: 'GET',
        url: '/laravelpos/backend/data-pembelian/list-detail-pembelian/' + kode,
        success: function (data) {
            var rows = '';
            var no = 0;
            var subtotal = 0;
            $.each(data, function (key, value) {
                no += 1;
                rows = rows + '<tr>';
                rows = rows + '<td>' + value.kode_barang + ' - ' + value.namabarang + '</td>';
                rows = rows + '<td class="text-center">' + value.jumlah + ' Pcs</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.harga) + '</td>';
                rows = rows + '<td class="text-right"> Rp ' + rupiah(value.total) + '</td>';
                rows = rows + '</tr>';
                subtotal += parseInt(value.total);
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

    kekurangan = parseInt(subtotal) + parseInt(biaya_tambahan) - parseInt(dibayar) - parseInt(potongan);

    $('#kekurangan').val(rupiah(kekurangan));
}

//===============================================================================================
$('#simpanbtn').on('click', function (e) {
    if ($('#kode').val() == "" || $('#supplier').val() == "" || $('#tgl_order').val() == "" || $('#subtotal').val() == "" || $('#subtotal').val() == "0") {
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
        if(parseInt(kekurangan)<0){
            swalWithBootstrapButtons.fire({
                title: 'Oops',
                text: 'Pembayaran melebihi kekurangan',
                confirmButtonText: 'OK'
            });
        }else{
            $('#panelsatu').loading('toggle');
            $('#paneldua').loading('toggle');
            $.ajax({
                type: 'PUT',
                url: '/laravelpos/backend/pembelian/'+$('#kode').val(),
                data: {
                    '_token': $('input[name=_token]').val(),
                    '_mehtod':'PUT',
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
                success: function () {
                    window.location.replace('/laravelpos/backend/pembelian');
                }
            });
        }
    }
});