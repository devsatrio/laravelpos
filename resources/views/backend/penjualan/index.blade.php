@extends('layouts/base')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<link href="{{asset('assets/loadingjs/loading.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12 text-center">
                <h1 class="m-0 text-dark"> Penjualan</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="pr-5 pl-5">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>Info!</h4>
            {{ session('status') }}
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="loading-div" id="panelsatu">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">List Data</h3>
                            <div class="card-tools">
                                <a href="{{url('/backend/penjualan/create')}}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-plus"></i>
                                        Tambah
                                        Data
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list-data" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Customer</th>
                                            <th>Pembuat</th>
                                            <th>Tgl Buat</th>
                                            <th>Total</th>
                                            <th>Terbayar</th>
                                            <th>Kekurangan</th>
                                            <th>Kembalian</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Customer</th>
                                            <th>Pembuat</th>
                                            <th>Tgl Buat</th>
                                            <th>Total</th>
                                            <th>Terbayar</th>
                                            <th>Kekurangan</th>
                                            <th>Kembalian</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="print_div" style="display:none;font-size:11px;">
    <b>Nama Toko Anda</b><br>
    Alamat Toko Anda
    <hr style="margin:0px;border-top: 1px dashed black;">
    <table width="100%">
        <thead>
            <tr>
                <td>Nama</td>
                <td>Disk</td>
                <td>Qty</td>
                <td align="right">Harga</td>
                <td align="right">Total</td>
            </tr>
            <tr>
                <td colspan="5">
                    <hr style="margin:0px;border-top: 1px dashed black;">
                </td>
            </tr>
        </thead>
        <tbody id="print_detail">

        </tbody>
    </table>
    <hr style="margin:0px;border-top: 1px dashed black;">
    <table width="100%">
        <tr>
            <td>Biaya Tambahan</td>
            <td align="right"><span id="print_biaya_tambahan">-</span></td>
        </tr>
        <tr>
            <td>Potongan</td>
            <td align="right"><span id="print_potongan">-</span></td>
        </tr>
        <tr>
            <td>Total</td>
            <td align="right"><span id="print_total">-</span></td>
        </tr>
        <tr>
            <td>Tunai</td>
            <td align="right"><span id="print_dibayar">-</span></td>
        </tr>
        <tr>
            <td>Kekurangan</td>
            <td align="right"><span id="print_kekurangan">-</span></td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td align="right"><span id="print_kembalian">-</span></td>
        </tr>
    </table>
    <hr style="margin:0px;border-top: 1px dashed black;">
    <span id="print_kode"></span> || <span id="print_tgl_order"></span><br>
    <span id="print_pembuat"></span> || <span id="print_customer"></span><br>
    <hr style="margin:0px;border-top: 1px dashed black;">
    Barang yang sudah dibeli tidak bisa ditukar atau dikembalikan
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/loadingjs/loading.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/penjualan.js')}}"></script>
@endpush