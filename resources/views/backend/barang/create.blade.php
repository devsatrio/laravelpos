@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Barang</h1>
            </div>
        </div>
    </div>
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
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Data</h3>
                    </div>
                    <form method="POST" role="form" enctype="multipart/form-data" action="{{url('/backend/barang')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nama</label>
                                        <input type="text" class="form-control" name="nama" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Kategori</label>
                                        <select name="kategori" class="form-control">
                                            @foreach($kategori as $row_kategori)
                                            <option value="{{$row_kategori->id}}">{{$row_kategori->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Harga Beli</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga_beli" required @if(auth()->user()->can('view-harga-beli-barang')) @else value="0" readonly @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Harga Jual</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga_jual" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Harga Grosir (Khusus untuk customer)</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" name="harga_grosir" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Diskon</label>
                                        <div class="input-group">
                                            <input type="number" min="0" max="99" name="diskon" required class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Diskon Grosir (Khusus untuk customer)</label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="0" max="99" name="diskon_grosir" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Keterangan</label>
                                        <div class="input-group">
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                                        </div>
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
    </div>
</div>
@endsection
@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush

@push('customscripts')
<!-- <script src="{{asset('customjs/backend/admin_input.js')}}"></script> -->
@endpush