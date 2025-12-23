@extends('layouts/base')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12 text-center">
                <h1 class="m-0 text-dark"> Barang</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content pl-5 pr-5">
    <div class="container-fluit">
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
                        <div id="collapseThree" @if (Request::has('nama') || Request::has('kategori')) class="collapse show" @else class="collapse" @endif data-parent="#accordion" style="">
                            <div class="card-body">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="col-md-3 mt-0">
                                            <label>Nama atau Kode</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nama" @if(Request::has('nama')) value="{{Request::get('nama')}}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-0">
                                            <label>Hitung Stok</label>
                                                <select name="stok" id="stok" style="width:100%;"
                                                    class="form-control">
                                                    <option value="semua" @if(Request::has('stok'))
                                                        @if(Request::get('stok')=='semua' ) selected @endif
                                                        @endif>Semua</option>
                                                    <option value="y" @if(Request::has('stok'))
                                                        @if(Request::get('stok')=='y' ) selected @endif
                                                        @endif>Ya</option>
                                                    <option value="n" @if(Request::has('stok'))
                                                        @if(Request::get('stok')=='n' ) selected @endif
                                                        @endif>Tidak</option>
                                                </select>
                                        </div>
                                        <div class="col-md-4 mt-0">
                                            <label>Kategori</label>
                                            <div class="input-group">
                                                <select name="kategori" id="kategori" style="width:80%;"
                                                    class="form-control">
                                                    <option value="semua" @if(Request::has('kategori'))
                                                        @if(Request::get('kategori')=='semua' ) selected @endif
                                                        @endif>Semua Kategori</option>
                                                    @foreach($kategoribarang as $row_kategoribarang)
                                                    <option value="{{$row_kategoribarang->id}}"
                                                        @if(Request::has('kategori'))
                                                        @if(Request::get('kategori')==$row_kategoribarang->id)
                                                        selected @endif
                                                        @endif>
                                                        {{$row_kategoribarang->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary" type="submit"><i
                                                            class="fa fa-search"></i></button>
                                                    <a href="{{url('/backend/barang')}}" class="btn btn-secondary"><i class="fa fa-sync"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                        @if(Request::has('kategori'))
                        <input type="hidden" id="kat_barang" value="{{Request::get('kategori')}}">
                        @else
                        <input type="hidden" id="kat_barang" value="semua">
                        @endif
                        {{ $data->appends($_GET)->links() }}
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
                                        <th>Hitung Stok</th>
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
                                            <td>{{$row->nama}}</td>
                                            <td>{{$row->namakategori}}</td>
                                            <td class="text-right">{{ 'Rp ' . number_format(round($row->harga_jual), 0, ',', '.') }}</td>
                                            <td class="text-right">{{ 'Rp ' . number_format(round($row->harga_jual_customer), 0, ',', '.') }}</td>
                                            <td class="text-right">
                                                 @if ($row->hitung_stok=='y')
                                                {{$row->stok}} Pcs
                                                @else
                                                -
                                                @endif</td>
                                            <td class="text-center">
                                                @if ($row->hitung_stok=='y')
                                                <span class="badge bg-success">Ya</span>
                                                @else
                                                <span class="badge bg-danger">Tidak</span>
                                                @endif
                                            </td>
                                            <td class="text-center"><a href="{{url('/backend/barang/'.$row->id)}}" class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a><a href="{{url('/backend/barang/'.$row->id.'/edit')}}" class="btn btn-sm btn-success m-1"><i class="fa fa-wrench"></i></a><button class="btn btn-sm btn-danger m-1" onclick="hapusdata('{{$row->id}}')"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
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
                                        <th>Hitung Stok</th>
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
                                <li><b>hitung_stok</b> harus diisi dengan format string, huruf kecil dan harus memilih antara <b>y</b> atau <b>n</b>.</li>
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
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
@endpush

@push('customscripts')
<script src="{{asset('customjs/backend/barang.js')}}"></script>
@endpush