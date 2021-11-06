@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark"> Edit Profile</h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="content">
      <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Edit Profile Anda</h3>
                    </div>
                    <form method="POST" onsubmit="return validasiinput();" role="form" enctype="multipart/form-data"
                        action="{{url('/backend/edit-profile/'.$data->id)}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama</label>
                                <input type="text" class="form-control" name="nama" value="{{$data->name}}" required
                                    autofocus>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username</label>
                                <input type="text" class="form-control" name="username" value="{{$data->username}}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" class="form-control" name="email" value="{{$data->email}}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">No. Telp</label>
                                <input type="text" class="form-control" name="telp" value="{{$data->telp}}" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <img src="{{asset('img/admin/'.$data->gambar)}}" alt="">
                                <br>
                                <label for="exampleInputFile">Gambar Baru*</label>
                                <input type="file" class="form-control" name="gambar" accept="image/*">
                                <input type="hidden" name="gambar_lama" value="{{$data->gambar}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Password Baru*</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Konfirmasi Password Baru*</label>
                                <input type="password" class="form-control" id="kpassword">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush