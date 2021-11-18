@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Admin</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Edit Data</h3>
                    </div>
                    <form method="POST" onsubmit="return validasiinput();" role="form" enctype="multipart/form-data"
                        action="{{url('/backend/admin/'.$data->id)}}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nama</label>
                                        <input type="text" class="form-control" name="nama" value="{{$data->name}}"
                                            required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Username</label>
                                        <input type="hidden" name="oldusername" value="{{$data->username}}">
                                        <input type="text" class="form-control" name="username"
                                            value="{{$data->username}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input type="hidden" name="oldemail" value="{{$data->email}}">
                                        <input type="email" class="form-control" name="email" value="{{$data->email}}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">No. Telp</label>
                                        <input type="text" class="form-control" name="telp" value="{{$data->telp}}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Level</label>
                                        <select name="level" class="form-control">
                                            @foreach($roles as $row_roles)
                                            <option value="{{$row_roles->name}}" @if($data->level==$row_roles->name)
                                                selected
                                                @endif>{{$row_roles->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @if($data->gambar!="")
                                        <img src="{{asset('img/admin/'.$data->gambar)}}" alt="">
                                        <br>
                                        @endif
                                        <label for="exampleInputFile">Gambar Baru*</label>
                                        <input type="file" class="form-control" name="gambar" accept="image/*">
                                        <input type="hidden" name="gambar_lama" value="{{$data->gambar}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password Baru*</label>
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Konfirmasi Password Baru*</label>
                                        <input type="password" class="form-control" id="kpassword">
                                    </div>
                                </div>
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

@push('customscripts')
<!-- <script src="{{asset('customjs/backend/admin_input.js')}}"></script> -->
@endpush