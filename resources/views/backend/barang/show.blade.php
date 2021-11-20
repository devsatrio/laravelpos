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
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Detail Data</h3>
                    </div>
                    @foreach($data as $row)
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kode</label>
                                    <p>{{$row->kode}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama</label>
                                    <p>{{$row->nama}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kategori</label>
                                    <p>{{$row->namakategori}}</p>
                                </div>
                            </div>
                            @if(auth()->user()->can('view-harga-beli-barang'))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Beli</label>
                                    <p>Rp. {{number_format($row->harga_beli,0,',','.')}}</p>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Jual</label>
                                    <p>Rp. {{number_format($row->harga_jual,0,',','.')}}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Grosir (Khusus untuk
                                        customer)</label>
                                    <p>Rp. {{number_format($row->harga_jual_customer,0,',','.')}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Diskon</label>
                                    <p>{{$row->diskon}} %</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Diskon Grosir (Khusus untuk
                                        customer)</label>
                                    <p>{{$row->diskon_customer}} %</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <p>{{$row->keterangan}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">History Stok Barang</h3>
                    </div>
                    <div class="card-body"></div>
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