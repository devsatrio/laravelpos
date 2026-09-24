var harga_beli = document.getElementById("harga_beli");
var harga_jual = document.getElementById("harga_jual");
var harga_grosir = document.getElementById("harga_grosir");

if (harga_beli) {
    harga_beli.addEventListener("keyup", function(e) {
        harga_beli.value = formatRupiah(this.value);
    });
}
if (harga_jual) {
    harga_jual.addEventListener("keyup", function(e) {
        harga_jual.value = formatRupiah(this.value);
    });
}
if (harga_grosir) {
    harga_grosir.addEventListener("keyup", function(e) {
        harga_grosir.value = formatRupiah(this.value);
    });
}

$(function () {
    $('#kategori').select2();

    $('#btn-add-barcode').on('click', function() {
        var row = `
        <div class="input-group mb-2 barcode-row">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
            </div>
            <input type="text" class="form-control" name="barcode[]" placeholder="Scan atau ketik kode barcode">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger btn-remove-barcode"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
        $('#barcode-container').append(row);
    });

    $(document).on('click', '.btn-remove-barcode', function() {
        if ($('#barcode-container .barcode-row').length > 1) {
            $(this).closest('.barcode-row').remove();
        } else {
            $(this).closest('.barcode-row').find('input').val('');
        }
    });
});

function formatRupiah(angka) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
}