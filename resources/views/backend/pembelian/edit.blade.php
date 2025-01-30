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
                    <h1 class="m-0 text-dark">Edit Pembelian</h1>
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
                @foreach($data_pembelian as $row_data_pembelian)
                <div class="col-md-4">
                    <div class="loading-div" id="panelsatu">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Cari Barang</h3>
                            </div>
                            <form>
                                <div class="card-body">
                                    <div class="row">
                                        <div @if($view_input_scan) class="col-md-12" @else class="col-md-12 d-none" @endif>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Cari Barang By Barcode</label>
                                                <input type="text" class="form-control" name="cari_barang_qr"
                                                    id="cari_barang_qr" @if($view_input_scan) autofocus @endif required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Cari Barang Manual</label>
                                                <select class="form-control select2" id="barang" name="barang" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
                                        <div class="col-md-12">
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
                                    <button type="reset" onclick="history.go(-1)"
                                        class="btn btn-danger">Kembali</button>
                                    <button type="button" id="tambahbtn"
                                        class="btn btn-primary float-right">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="loading-div" id="paneldua">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Detail Pembelian</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kode Pembelian</label>
                                            <input type="text" class="form-control" name="kode" id="kode"
                                                value="{{$kode}}" required readonly>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Supplier</label>
                                            <select id="supplier" name="supplier" class="form-control select2">
                                                @foreach($supplier as $row_supplier)
                                                <option value="{{$row_supplier->kode}}" @if($row_supplier->kode==$row_data_pembelian->supplier) selected @endif>{{$row_supplier->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Tgl. Order</label>
                                            <input type="text" class="form-control float-right" id="tgl_order"
                                                name="tgl_order" value="{{$row_data_pembelian->tgl_buat}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mt-3 mb-5">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Barang</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tubuhnya">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">Total</th>
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
                                                    id="biaya_tambahan" value="{{number_format($row_data_pembelian->biaya_tambahan,0,',','.')}}" required>
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
                                                    id="potongan" value="{{number_format($row_data_pembelian->potongan,0,',','.')}}" required>
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
                                                    id="total" value="{{number_format($row_data_pembelian->total,0,',','.')}}" readonly required>
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
                                                    id="dibayar" value="{{number_format($row_data_pembelian->terbayar,0,',','.')}}" required>
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
                                                    id="kekurangan" value="{{number_format($row_data_pembelian->kekurangan,0,',','.')}}" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control"
                                                rows="2">{{$row_data_pembelian->keterangan}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" id="simpanbtn" class="btn btn-success float-right">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
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
                <div class="form-group">
                    <label for="exampleInputEmail1">Harga Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="edit_harga_barang" id="edit_harga_barang" readonly
                            required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Jumlah Barang</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="edit_jumlah_barang" id="edit_jumlah_barang"
                            required>
                        <div class="input-group-append">
                            <span class="input-group-text">Pcs</span>
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
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/loadingjs/loading.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/pembelian_edit.js')}}"></script>
<!-- <script src="{{asset('customjs/backend/
    ')}}"></script> -->
@endpush