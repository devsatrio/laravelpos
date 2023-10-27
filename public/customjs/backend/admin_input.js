function validasiinput(){
    if($('#password').val()==$('#kpassword').val()){
        return true;
    }else{
        Swal.fire({
            title: 'Maaf',
            text: 'Konfirmasi password salah!'
          })
        $('#kpassword').val('');
        return false;
    }
}