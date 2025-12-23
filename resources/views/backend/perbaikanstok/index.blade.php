@extends('layouts/base')

@section('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link href="{{ asset('assets/loadingjs/loading.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark"> Perbaikan Stok</h1>
                </div>
            </div>
        </div>
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
                                                <label>Pembuat</label>
                                                <div class="input-group">
                                                    <select name="pembuat" id="pembuat" class="form-control" style="width: 100%;">
                                                        <option value="Semua Pembuat" @if(Request::has('pembuat')) @if(Request::get('pembuat')=='Semua Pembuat') selected @endif @endif>Semua Pembuat</option>
                                                        @foreach ($user as $row_user)
                                                            <option value="{{ $row_user->id }}" @if(Request::has('pembuat')) @if(Request::get('pembuat')==$row_user->id) selected @endif @endif>{{ $row_user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-0">
                                                <label>Status</label>
                                                <div class="input-group">
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="Semua Status"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Semua Status') selected @endif
                                                            @endif>Semua Status</option>
                                                        <option value="Draft"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Draft') selected @endif
                                                            @endif>Draft</option>
                                                        <option value="Approve"
                                                            @if (Request::has('status')) @if (Request::get('status') == 'Approve') selected @endif
                                                            @endif>Approve</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary" type="submit"><i
                                                                class="fa fa-search"></i></button>
                                                        <a href="{{ url('/backend/perbaikan-stok') }}"
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">List Data</h3>
                            <div class="card-tools">
                                <a href="{{ url('/backend/perbaikan-stok/create') }}">
                                    <button type="button" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> Tambah
                                        Data
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table id="list-data" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Pembuat</th>
                                                <th>Tgl Buat</th>
                                                <th>Keterangan</th>
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
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $row->kode }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->tgl_buat }}</td>
                                                    <td>{{ $row->keterangan }}</td>
                                                    <td>
                                                        @if ($row->status=='Draft')
                                                            <span class="badge bg-warning">{{ $row->status }}</span>
                                                        @else
                                                            <span class="badge bg-primary">{{ $row->status }}</span>
                                                        @endif
                                                        </td>
                                                    <td class="text-center">
                                                        <a href="{{ url('/backend/perbaikan-stok/' . $row->kode) }}"
                                                            class="btn btn-sm m-1 btn-warning"><i class="fa fa-eye"></i></a>

                                                        @if ($row->status != 'Approve')
                                                            <a href="{{ url('/backend/perbaikan-stok/' . $row->id . '/edit') }}"
                                                                class="btn btn-sm m-1 btn-success"><i
                                                                    class="fa fa-wrench"></i></a>
                                                            <button class="btn btn-sm m-1 btn-info"
                                                                onclick="updatestatus('{{ $row->kode }}')"><i
                                                                    class="fa fa-check"></i></button>
                                                            <button class="btn btn-sm m-1 btn-danger"
                                                                onclick="hapusdata('{{ $row->id }}')"><i
                                                                    class="fa fa-trash"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Pembuat</th>
                                                <th>Tgl Buat</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
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
        </div>
    </div>
@endsection

@push('customjs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/loadingjs/loading.js') }}"></script>
@endpush

@push('customscripts')
    <script src="{{ asset('customjs/backend/perbaikanstok.js') }}"></script>
@endpush
