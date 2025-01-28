@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Web Setting</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Edit Setting Website</h3>
                    </div>
                    @foreach($data as $row)
                    <form method="POST" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nama Program</label>
                                        <input type="text" name="nama_program" class="form-control"
                                            value="{{$row->nama_program}}" required>
                                        <input type="hidden" name="kode" value="{{$row->id}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Singkatan Nama Program</label>
                                        <input type="text" name="singkatan_nama_program" class="form-control"
                                            value="{{$row->singkatan_nama_program}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Instansi</label>
                                        <input type="text" name="instansi" class="form-control"
                                            value="{{$row->instansi}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Menggunakan Scanner</label>
                                        <select name="menggunakan_scan" id="menggunakan_scan" class="form-control">
                                            <option value="y" @if($row->gunakan_scanner=='y') selected @endif>Ya</option>
                                            <option value="n" @if($row->gunakan_scanner=='n') selected @endif>Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Alamat Instansi</label>
                                        <textarea name="alamat" class="form-control"
                                            rows="4">{{$row->alamat}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Deskripsi Program</label>
                                        <textarea name="deskripsi" class="form-control"
                                            rows="4">{{$row->deskripsi_program}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Note Transaksi</label>
                                        <textarea name="note" class="form-control" rows="4">{{$row->note}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Note Dashboard</label>
                                        <textarea name="note_program" class="form-control"
                                            rows="4">{{$row->note_program}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    @if($row->logo!='')
                                    <img src="{{asset('img/setting/'.$row->logo)}}" alt="" class="img-thumb" width="50%">
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Logo</label>
                                        <input type="file" name="filenya" id="filenya" class="form-control" accept="image/*">
                                        <input type="hidden" name="logo_lama" value="{{$row->logo}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$(function() {
    $('#filenya').on('change', function() {
        var imageSizeArr = 0;
        var imageSize = document.getElementById('filenya');
        var jumlah = 0;
        for (var i = 0; i < imageSize.files.length; i++) {
            jumlah += 1;
            var imageSiz = imageSize.files[i].size;
            var imagename = imageSize.files[i].name;
            if (imageSiz > 2000000) {
                var imageSizeArr = 1;
            }
            if (imageSizeArr == 1) {
                Swal.fire({
                    title: 'Maaf',
                    text: 'Maaf, File "' + imagename +
                        '" terlalu besar / memiliki ukuran lebih dari 2MB'
                })
                $('#filenya').val('');
            }
        }
    });
});
</script>
@endpush