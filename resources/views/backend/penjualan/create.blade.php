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
                    <h1 class="m-0 text-dark">Input Penjualan</h1>
                </div>
            </div>
        </div>
    </div>
    @php
        $view_input_scan=false;
    @endphp
    @foreach ($web_set as $row_web_set)
        <input type="hidden" id="is_use_scan" value="{{$row_web_set->gunakan_scanner}}">
        @php
            if($row_web_set->gunakan_scanner=='y'){
                $view_input_scan=true;
            }
        @endphp
    @endforeach
    <div class="content">
        <div class="container-fluit">
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
                <div class="col-md-4">
                    <div class="loading-div" id="panelsatu">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Cari Barang</h3>
                            </div>
                            <form method="POST" role="form" enctype="multipart/form-data"
                                action="{{url('/peralatan')}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div @if ($view_input_scan) class="col-md-12" @else class="col-md-12 d-none" @endif>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Cari Barang By Barcode</label>
                                                <input type="text" class="form-control" name="cari_barang_qr"
                                                    id="cari_barang_qr" @if ($view_input_scan) autofocus @endif required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Cari Barang Manual</label>
                                                <select class="form-control select2" id="barang" name="barang" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Harga</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="harga_barang"
                                                        id="harga_barang" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Diskon</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="diskon_barang"
                                                        id="diskon_barang" readonly required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Stok</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="stok_barang"
                                                        id="stok_barang" readonly required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Pcs</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="hitung_stok_barang"
                                                id="hitung_stok_barang" readonly required>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Jumlah</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="jumlah_barang"
                                                        id="jumlah_barang" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Pcs</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Total Harga</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="total_harga_barang"
                                                        id="total_harga_barang" readonly required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" id="tambahbtn" class="btn btn-block btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="loading-div" id="paneldua">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Detail Penjualan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kode Penjualan</label>
                                            <input type="text" class="form-control" name="kode" id="kode"
                                                value="{{$kode}}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Tgl. Order</label>
                                            <input type="text" class="form-control float-right" id="tgl_order"
                                                name="tgl_order">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Customer</label>
                                            <div class="input-group mb-3">
                                                <select id="customer" name="customer" class="form-control select2">
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button"
                                                        onclick="addcustomer()"><i class="fas fa-plus"></i></button>
                                                    <button class="btn btn-danger" type="button"
                                                        onclick="clearcustomer()"><i class="fas fa-times"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-3 mb-5">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Barang</th>
                                                <th class="text-center">Diskon</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tubuhnya">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5">Subtotal</th>
                                                <th>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right" id="subtotal"
                                                            name="subtotal" required readonly>
                                                    </div>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Biaya Tambahan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="biaya_tambahan"
                                                    id="biaya_tambahan" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Potongan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="potongan"
                                                    id="potongan" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Total</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="total"
                                                    id="total" value="0" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Dibayar</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="dibayar"
                                                    id="dibayar" value="0" required>
                                                
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button"
                                                        onclick="dibayar_lunas()"><i class="fas fa-check"></i></button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kekurangan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="kekurangan"
                                                    id="kekurangan" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kembalian</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control text-right" name="kembalian"
                                                    id="kembalian" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control"
                                                rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                                <button type="button" id="simpanbtn" class="btn btn-success float-right">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editdetailmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Jumlah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Kode Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="edit_kode_barang" id="edit_kode_barang" readonly
                            required>
                        <input type="hidden" name="edit_id" id="edit_id">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="edit_nama_barang" id="edit_nama_barang" readonly
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Diskon</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="edit_diskon_barang"
                                    id="edit_diskon_barang" readonly required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Harga Barang</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control text-right" name="edit_harga_barang"
                                    id="edit_harga_barang" readonly required>
                            </div>
                            <div class="input-group">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Stok</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="edit_stok_barang" id="edit_stok_barang"
                                    readonly required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Pcs</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="edit_hitung_stok_barang" id="edit_hitung_stok_barang">
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jumlah Barang</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="edit_jumlah_barang"
                                    id="edit_jumlah_barang" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Pcs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="editjumlahdetail" class="btn btn-primary">Edit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modaladdcustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="nama_customer" id="nama_customer" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Telp</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="telp_customer" id="telp_customer" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Alamat</label>
                    <div class="input-group">
                        <textarea name="alamat_customer" id="alamat_customer" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Keterangan</label>
                    <div class="input-group">
                        <textarea name="keterangan_customer" id="keterangan_customer" class="form-control"
                            rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="simpancustomer" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@php
$datasetting = DB::table('settings')->orderby('id','desc')->limit(1)->get();
@endphp
@foreach($datasetting as $row_setting)
<div id="print_div" style="display:none;font-size:11px;">
    <b>{{$row_setting->instansi}}</b><br>
    {{$row_setting->alamat}}
    <hr style="margin:0px;border-top: 1px dashed black;">
    <table width="100%">
        <thead>
            <tr>
                <td>Nama</td>
                <td><span class="print_nota_diskon">Disk</span></td>
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
        <tr id="tr_print_biaya_tambahan">
            <td>Biaya Tambahan</td>
            <td align="right"><span id="print_biaya_tambahan">-</span></td>
        </tr>
        <tr id="tr_print_potongan">
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
        <tr id="tr_print_kekurangan">
            <td>Kekurangan</td>
            <td align="right"><span id="print_kekurangan">-</span></td>
        </tr>
        <tr id="tr_print_kembalian">
            <td>Kembalian</td>
            <td align="right"><span id="print_kembalian">-</span></td>
        </tr>
    </table>
    <hr style="margin:0px;border-top: 1px dashed black;">
    <span>{{$kode}}</span> || <span id="print_tgl_order">{{date('Y-m-d')}}</span><br>
    <span>{{Auth::user()->name}}</span> || <span id="print_customer">-</span><br>
    <hr style="margin:0px;border-top: 1px dashed black;">
    {{$row_setting->note}}
</div>
@endforeach
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/loadingjs/loading.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/penjualan_input.js')}}"></script>
@endpush