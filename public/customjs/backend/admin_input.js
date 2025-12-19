function validasiinput() {
    if ($('#password').val() == $('#kpassword').val()) {
        return true;
    } else {
        Swal.fire({
            title: 'Maaf',
            text: 'Konfirmasi password salah!'
        })
        $('#kpassword').val('');
        return false;
    }
}

Filevalidation = () => {
    const fi = document.getElementById('gambar');
    // Check if any file is selected.
    if (fi.files.length > 0) {
        for (const i = 0; i <= fi.files.length - 1; i++) {

            const fsize = fi.files.item(i).size;
            const file = Math.round((fsize / 1024));
            // The size of the file.
            if (file >= 2048) {

                Swal.fire({
                    title: 'Maaf',
                    text: 'File too Big, please select a file less than 2mb'
                })
                fi.value = '';
            }
        }
    }
}