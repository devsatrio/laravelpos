@extends('layouts/base')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Barang</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content">
    <div class="container">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>Info!</h4>
            {{ session('status') }}
        </div>
        @endif
        @if(Session::get('errorexcel'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Error Logs :</strong>
            <ul>
                @foreach (Session::get('errorexcel') as $failure)
                @foreach ($failure->errors() as $error)
                <li><b>Field {{$failure->attribute()}} error</b> {{ $error }} ( in Line
                    {{$failure->row()}} ) </li>
                @endforeach
                @endforeach
            </ul>
            <hr>
            <b>NB : Semua data tidak disimpan ketika error</b>
        </div>
        @endif
        @if(Session::get('errorexcel_satu'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>Oops</h4>
            {{ Session::get('errorexcel_satu') }}
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">List Data</h3>
                        <div class="card-tools">
                            <a href="{{url('/backend/barang/create')}}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah
                                    Data
                                </button>
                            </a>
                            @if(auth()->user()->can('cetak-barcode-barang'))
                            <a href="{{url('/backend/barang/cetak-barcode')}}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-barcode"></i>
                                    Cetak Barcode
                                </button>
                            </a>
                            @endif
                            @if(auth()->user()->can('import-export-barang'))
                            <a href="{{url('/backend/barang/export-excel')}}">
                                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-download"></i>
                                    Export Excel
                                </button>
                            </a>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                data-target="#importmodal"><i class="fas fa-upload"></i> Import Excel
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="list-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>kode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Harga Jual</th>
                                        <th>Harga Grosir</th>
                                        <th>Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>kode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Harga Jual</th>
                                        <th>Harga Grosir</th>
                                        <th>Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<div class="modal fade bd-example-modal-lg" id="importmodal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('backend/barang/import-excel')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Cara Pengisian : </b>
                            <ol>
                                <li>Download template excel dengan mengklik tombol <b>Download Template</b> dibawah.
                                </li>
                                <li><b>kode_qr</b> dapat dikosongi atau diisi sesuai kode yang ada di barcode barang.
                                </li>
                                <li><b>nama</b> harus diisi dengan format string atau text.</li>
                                <li><b>kategori</b> harus diisi dengan id kategori barang, data id kategori barang dapat
                                    dilihat dengan mendownload di tombol <b>Export kategori barang</b> dibawah.</li>
                                <li><b>harga_beli, harga_jual, harga_jual_customer</b> harus diisi dengan format numerik
                                    atau angka tanpa titik atau koma, contoh <b>200000</b>.</li>
                                <li><b>diskon & diskon_customer</b> harus diisi dengan format numerik atau angka tanpa
                                    titik atau koma dengan range 0 - 99, contoh <b>10</b>.</li>
                                <li><b>nama</b> dapat dikosongi atau diisi dengan format string atau text.</li>
                            </ol>
                            <a href="{{asset('/assets/template_import_barang.xlsx')}}">
                                <button type="button" class="btn btn-sm btn-info btn-sm"><i class="fas fa-file"></i>
                                    Download Template
                                </button>
                            </a>
                            <a href="{{url('/backend/kategori-barang/export-excel')}}">
                                <button type="button" class="btn btn-sm btn-info btn-sm"><i class="fas fa-file"></i>
                                    Export Kategori Barang
                                </button>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <div class="form-group">
                                <label for="exampleInputEmail1">File Excel</label>
                                <div class="input-group">
                                    <input type="file" id="excelfile" class="form-control mb-2" name="file_excel"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/barang.js')}}"></script>
@endpush