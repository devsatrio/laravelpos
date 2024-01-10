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
                    <h1 class="m-0 text-dark">Detail Pembelian</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row pl-5 pr-5">
                @if (session('status'))
                <div class="col-lg-12">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4>Info!</h4>
                        {{ session('status') }}
                    </div>
                </div>
                @endif
                @foreach($data_pembelian as $row_data_pembelian)
                <div class="col-md-12">
                    <div class="loading-div" id="paneldua">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Detail Pembelian</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kode Pembelian</label>
                                            <p>{{$row_data_pembelian->kode}}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Supplier</label>
                                            <p>{{$row_data_pembelian->namasupplier}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Tgl. Order</label>
                                            <p>{{$row_data_pembelian->tgl_buat}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Status</label>
                                            <p>{{$row_data_pembelian->status}} - {{$row_data_pembelian->status_pembelian}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-3 mb-5">
                                        <thead>
                                            <tr>
                                                <th>Barang</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tubuhnya">
                                            @foreach($data_detail_pembelian as $row_data_detail_pembelian)
                                            <tr>
                                                <td>{{$row_data_detail_pembelian->kode_barang}} - {{$row_data_detail_pembelian->namabarang}}</td>
                                                <td class="text-center">{{$row_data_detail_pembelian->jumlah}} Pcs</td>
                                                <td class="text-right">Rp. {{number_format($row_data_detail_pembelian->harga,0,',','.')}}</td>
                                                <td class="text-right">Rp. {{number_format($row_data_detail_pembelian->total,0,',','.')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right">Subtotal</td>
                                                <td class="text-right">
                                                Rp. {{number_format($row_data_pembelian->subtotal,0,',','.')}}
                                                </td>
                                            </tr>
                                            <tr>
                                            <td colspan="3" class="text-right">Biaya Tambahan</td>
                                                <td class="text-right">
                                                Rp. {{number_format($row_data_pembelian->biaya_tambahan,0,',','.')}}
                                                </td>
                                            </tr>
                                            <tr>
                                            <td colspan="3" class="text-right">Potongan</td>
                                                <td class="text-right">
                                                Rp. {{number_format($row_data_pembelian->potongan,0,',','.')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Total</th>
                                                <th class="text-right">
                                                Rp. {{number_format($row_data_pembelian->total,0,',','.')}}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Dibayar</th>
                                                <th class="text-right">
                                                Rp. {{number_format($row_data_pembelian->terbayar,0,',','.')}}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Kekurangan</th>
                                                <th class="text-right">
                                                Rp. {{number_format($row_data_pembelian->kekurangan,0,',','.')}}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Keterangan</label>
                                            <p>{{$row_data_pembelian->keterangan}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Pembuat</label>
                                            @php
                                            $datapembuat = DB::table('users')->where('id',$row_data_pembelian->created_by)->get();
                                            @endphp
                                            @foreach($datapembuat as $row_datapembuat)
                                            <p>{{$row_datapembuat->name}} - {{$row_data_pembelian->created_at}}</p>
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Pengedit Terakhir</label>
                                            @php
                                            $datapengedit = DB::table('users')->where('id',$row_data_pembelian->updated_by)->get();
                                            @endphp
                                            @foreach($datapengedit as $row_datapengedit)
                                            <p>{{$row_datapengedit->name}} - {{$row_data_pembelian->updated_at}}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
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
@endpush