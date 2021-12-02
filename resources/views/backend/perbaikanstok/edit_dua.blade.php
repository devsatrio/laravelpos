@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
<link href="{{asset('assets/loadingjs/loading.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-1">
                <div class="col-sm-12 text-center">
                    <h1 class="m-0 text-dark">Edit Perbaikan Stok</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                @foreach($data_perbaikan as $row_data_perbaikan)
                <div class="col-md-12">
                    <div class="loading-div" id="paneldua">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Detail Perbaikan Stok</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kode</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{$row_data_perbaikan->kode}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Pembuat</label>
                                            <input type="text" class="form-control" id="kode" name="kode"
                                                value="{{Auth::user()->name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Tgl Buat</label>
                                            <input type="text" class="form-control" id="tgl_buat" name="tgl_buat"
                                                value="{{$row_data_perbaikan->tgl_buat}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-3 mb-5">
                                        <thead>
                                            <tr>
                                                <th>Barang</th>
                                                <th class="text-center">Stok Sekarang</th>
                                                <th class="text-center">Stok Baru</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tubuhnya">
                                            @foreach($detail as $row_detail)
                                            <tr>
                                                <td>{{$row_detail->kode_barang}} - {{$row_detail->namabarang}}</td>
                                                <td class="text-center">{{$row_detail->stok_lama}} Pcs</td>
                                                <td class="text-center">{{$row_detail->stok_baru}} Pcs</td>
                                                <td>{{$row_detail->keterangan}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control"
                                                rows="2">{{$row_data_perbaikan->keterangan}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                                <button type="button" id="updatebtn" class="btn btn-success float-right">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/loadingjs/loading.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/perbaikan_edit.js')}}"></script>
@endpush