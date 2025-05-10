@extends('layouts/base')

@section('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link href="{{ asset('assets/loadingjs/loading.css') }}" rel="stylesheet">
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

                    <div id="accordion">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4 class="card-title w-100">
                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree"
                                        aria-expanded="false">
                                        Cari Data
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree"
                                @if (Request::has('kode') || Request::has('kategori')) class="collapse show" @else class="collapse" @endif
                                data-parent="#accordion" style="">
                                <div class="card-body">
                                    <form action="" method="get">
                                        <div class="row">
                                            <div class="col-md-3 mt-0">
                                                <label>Kode</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="kode"
                                                        @if (Request::has('kode')) value="{{ Request::get('kode') }}" @endif>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-0">
                                                <label>Tgl. Buat</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="tgl_buat" id="tgl_buat" @if (Request::has('tgl_buat')) value="{{ Request::get('tgl_buat') }}" @endif readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-0">
                                                <label>Status</label>
                                                <div class="input-group">
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="Semua Status" @if(Request::has('status')) @if(Request::get('status')=='Semua Status') selected @endif @endif>Semua Status</option>
                                                        <option value="Telah Lunas" @if(Request::has('status')) @if(Request::get('status')=='Telah Lunas') selected @endif @endif>Telah Lunas</option>
                                                        <option value="Belum Lunas" @if(Request::has('status')) @if(Request::get('status')=='Belum Lunas') selected @endif @endif>Belum Lunas</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary" type="submit"><i
                                                                class="fa fa-search"></i></button>
                                                        <a href="{{ url('/backend/penjualan') }}"
                                                            class="btn btn-secondary"><i class="fa fa-sync"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loading-div" id="panelsatu">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">List Data</h3>
                                <div class="card-tools">
                                    <a href="{{ url('/backend/penjualan/create') }}">
                                        <button type="button" class="btn btn-default btn-sm"><i class="fas fa-plus"></i>
                                            Tambah
                                            Data
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                {{ $data->appends($_GET)->links() }}
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
                                            @php
                                                $i = ($data->currentpage() - 1) * $data->perpage() + 1;
                                            @endphp
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{$row->kode}}</td>
                                                    <td>{{$row->namacustomer}}</td>
                                                    <td>{{$row->name}}</td>
                                                    <td>{{$row->tgl_buat}}</td>
                                                    <td class="text-right">{{ 'Rp ' . number_format(round($row->total), 0, ',', '.') }}</td>
                                                    <td class="text-right">{{ 'Rp ' . number_format(round($row->terbayar), 0, ',', '.') }}</td>
                                                    <td class="text-right">{{ 'Rp ' . number_format(round($row->kekurangan), 0, ',', '.') }}</td>
                                                    <td class="text-right">{{ 'Rp ' . number_format(round($row->kembalian), 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($row->status=='Belum Lunas')
                                                            <span class="badge bg-danger">{{$row->status}}</span>
                                                        @else
                                                            <span class="badge bg-success">{{$row->status}}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{url('/backend/penjualan/'.$row->kode)}}" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>
                                                        @if ($row->status=='Belum Lunas')
                                                            <button class="btn btn-sm m-1 btn-info" onclick="bayarhutang('{{$row->kode}}')"><i class="fa fa-edit"></i></button>
                                                        @endif
                                                        <button class="btn btn-sm m-1 btn-secondary" onclick="cetakulang('{{$row->kode}}')"><i class="fa fa-print"></i></button>
                                                        <button class="btn btn-sm m-1 btn-danger" onclick="hapusdata('{{$row->kode}}')"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
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
                                {{ $data->appends($_GET)->links() }}
                                Menampilkan Data {{ ($data->currentpage() - 1) * $data->perpage() + 1 }} -
                                @if (($data->currentpage() - 1) * $data->perpage() + $data->perpage() > $data->total())
                                    {{ $data->total() }}
                                @else
                                    {{ ($data->currentpage() - 1) * $data->perpage() + $data->perpage() }}
                                @endif
                                Dari {{ $data->total() }} Data
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bayarhutangmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bayar Kekurangan / Hutang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Kode Penjualan</label>
                                <input type="text" class="form-control" name="edit_kode" id="edit_kode" readonly
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Customer</label>
                                <input type="text" class="form-control" name="edit_customer" id="edit_customer"
                                    readonly required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Kekurangan Pembayaran / Hutang</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control text-right" name="edit_hutang"
                                        id="edit_hutang" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tgl Bayar</label>
                                <input type="text" class="form-control" name="tgl_bayar" id="tgl_bayar"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Dibayar</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control text-right" name="edit_dibayar"
                                        id="edit_dibayar" required>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Kekurangan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control text-right" name="edit_kekurangan"
                                        id="edit_kekurangan" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btnsimpanhutang" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @php
        $datasetting = DB::table('settings')->orderby('id', 'desc')->limit(1)->get();
    @endphp
    @foreach ($datasetting as $row_setting)
        <div id="print_div" style="display:none;font-size:11px;">
            <b>{{ $row_setting->instansi }}</b><br>
            {{ $row_setting->alamat }}
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
            <span id="print_kode"></span> || <span id="print_tgl_order"></span><br>
            <span id="print_pembuat"></span> || <span id="print_customer"></span><br>
            <hr style="margin:0px;border-top: 1px dashed black;">
            {{ $row_setting->note }}
        </div>
    @endforeach
@endsection

@push('customjs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/loadingjs/loading.js') }}"></script>
@endpush

@push('customscripts')
    <script src="{{ asset('customjs/backend/penjualan.js') }}"></script>
@endpush
