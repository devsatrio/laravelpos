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
                    <h1 class="m-0 text-dark"> Pembelian</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
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
                                                    <input type="text" class="form-control" name="tgl_buat"
                                                        id="tgl_buat"
                                                        @if (Request::has('tgl_buat')) value="{{ Request::get('tgl_buat') }}" @endif
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-0">
                                                <label>Status</label>
                                                <div class="input-group">
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="Semua Status"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Semua Status') selected @endif
                                                            @endif>Semua Status</option>
                                                        <option value="Telah Lunas"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Telah Lunas') selected @endif
                                                            @endif>Telah Lunas</option>
                                                        <option value="Belum Lunas"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Belum Lunas') selected @endif
                                                            @endif>Belum Lunas</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary" type="submit"><i
                                                                class="fa fa-search"></i></button>
                                                        <a href="{{ url('/backend/pembelian') }}"
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
                                    <a href="{{ url('/backend/pembelian/create') }}">
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
                                                <th>Supplier</th>
                                                <th>Tgl Buat</th>
                                                <th>Total</th>
                                                <th>Terbayar</th>
                                                <th>Kekurangan</th>
                                                <th>Status</th>
                                                <th>Status Pembelian</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = ($data->currentpage() - 1) * $data->perpage() + 1;
                                            @endphp
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $row->kode }}</td>
                                                    <td>{{ $row->namasupplier }}</td>
                                                    <td>{{ $row->tgl_buat }}</td>
                                                    <td class="text-right">
                                                        {{ 'Rp ' . number_format(round($row->total), 0, ',', '.') }}</td>
                                                    <td class="text-right">
                                                        {{ 'Rp ' . number_format(round($row->terbayar), 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ 'Rp ' . number_format(round($row->kekurangan), 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 'Belum Lunas')
                                                            <span class="badge bg-danger">{{ $row->status }}</span>
                                                        @else
                                                            <span class="badge bg-success">{{ $row->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->status_pembelian == 'Approve')
                                                            <span
                                                                class="badge bg-primary">{{ $row->status_pembelian }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-danger">{{ $row->status_pembelian }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ url('/backend/pembelian/' . $row->kode) }}"
                                                            class="btn btn-sm btn-warning m-1"><i class="fa fa-eye"></i></a>
                                                        @if ($row->status_pembelian != 'Approve')
                                                            <button class="btn btn-sm m-1 btn-info"
                                                                onclick="updatestatus('{{ $row->id }}')"><i
                                                                    class="fa fa-check"></i></button>
                                                        @endif
                                                        <a href="{{ url('/backend/pembelian/' . $row->kode . '/edit') }}"
                                                            class="btn btn-sm m-1 btn-success"><i
                                                                class="fa fa-wrench"></i></a>
                                                        <button class="btn btn-sm m-1 btn-danger"
                                                            onclick="hapusdata('{{ $row->id }}')"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Supplier</th>
                                                <th>Tgl Buat</th>
                                                <th>Total</th>
                                                <th>Terbayar</th>
                                                <th>Kekurangan</th>
                                                <th>Status</th>
                                                <th>Status Pembelian</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('customjs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/loadingjs/loading.js') }}"></script>
@endpush

@push('customscripts')
    <script src="{{ asset('customjs/backend/pembelian.js') }}"></script>
@endpush
