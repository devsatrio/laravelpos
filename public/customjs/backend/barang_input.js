var harga_beli = document.getElementById("harga_beli");
var harga_jual = document.getElementById("harga_jual");
var harga_grosir = document.getElementById("harga_grosir");

harga_beli.addEventListener("keyup", function(e) {
    harga_beli.value = formatRupiah(this.value);
});
harga_jual.addEventListener("keyup", function(e) {
    harga_jual.value = formatRupiah(this.value);
});
harga_grosir.addEventListener("keyup", function(e) {
    harga_grosir.value = formatRupiah(this.value);
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