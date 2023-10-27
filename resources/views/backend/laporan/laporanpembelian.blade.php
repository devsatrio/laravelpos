@extends('layouts/base')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12 text-center">
                <h1 class="m-0 text-dark"> Laporan Pembelian</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluit">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">List Data</h3>
                    </div>
                    <div class="card-body">
                        <label class="mb-0">Cari Data Berdasarkan</label><br>
                        <form action="" method="get">
                            <div class="row mb-3">
                                <div class="col-md-3 mt-3">
                                    <div class="input-group">
                                        <select name="supplier" class="form-control">
                                            <option @if(Request::has('supplier')) @if(Request::get('supplier')=='Semua'
                                                ) selected @endif @endif value="Semua" selected>Semua Supplier</option>
                                            @foreach($datasupplier as $row_datasupplier)
                                            <option @if(Request::has('supplier'))
                                                @if(Request::get('supplier')==$row_datasupplier->kode) selected @endif
                                                @endif value="{{$row_datasupplier->kode}}">{{$row_datasupplier->kode}} -
                                                {{$row_datasupplier->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <div class="input-group">
                                        <select name="pembuat" class="form-control">
                                            <option @if(Request::has('pembuat')) @if(Request::get('pembuat')=='Semua' )
                                                selected @endif @endif value="Semua" selected>Semua Pembuat</option>
                                            @foreach($dataadmin as $row_dataadmin)
                                            <option @if(Request::has('pembuat'))
                                                @if(Request::get('pembuat')==$row_dataadmin->id) selected @endif @endif
                                                value="{{$row_dataadmin->id}}">{{$row_dataadmin->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-3">
                                    <div class="input-group">
                                        <select name="status" class="form-control">
                                            <option @if(Request::has('status')) @if(Request::get('status')=='Semua' )
                                                selected @endif @endif value="Semua" selected>Semua Status</option>
                                            <option @if(Request::has('status'))
                                                @if(Request::get('status')=='Telah Lunas' ) selected @endif @endif
                                                value="Telah Lunas">Telah Lunas</option>
                                            <option @if(Request::has('status'))
                                                @if(Request::get('status')=='Belum Lunas' ) selected @endif @endif
                                                value="Belum Lunas">Belum Lunas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="tanggal" id="tanggal"
                                            @if(Request::has('tanggal')) value="{{Request::get('tanggal')}}" @else
                                            value="{{date('Y-m-d')}} - {{date('Y-m-d')}}" @endif>
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                            <a href="{{url('/backend/laporan-pembelian')}}" class="btn btn-secondary"><i
                                                    class="fas fa-sync"></i></a>
                                            <button class="btn btn-secondary" onclick="cetak()" type="button"><i
                                                    class="fa fa-print"></i></button>
                                            <button class="btn btn-secondary" id="export_button" type="button"><i
                                                    class="fa fa-download"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        @if(Request::has('tanggal'))
                        Hasil Pencarian @if(Request::has('tanggal')) Tanggal <b>{{Request::get('tanggal')}}</b> @endif
                        @if(Request::has('supplier'))
                        @if(Request::get('supplier')!='Semua')
                        @php
                        $supplier_data = DB::table('master_supplier')->where('kode',Request::get('supplier'))->get();
                        @endphp
                        @foreach($supplier_data as $row_supplier_data)
                        Customer <b>{{$row_supplier_data->kode}} {{$row_supplier_data->nama}}</b>
                        @endforeach
                        @endif
                        @endif

                        @if(Request::has('pembuat'))
                        @if(Request::get('pembuat')!='Semua')
                        @php
                        $pembuat_data = DB::table('users')->where('id',Request::get('pembuat'))->get();
                        @endphp
                        @foreach($pembuat_data as $row_pembuat_data)
                        Pembuat <b>{{$row_pembuat_data->name}}</b>
                        @endforeach
                        @endif
                        @endif

                        @if(Request::has('status'))
                        @if(Request::get('status')!='Semua')
                        Status <b>{{Request::get('status')}}</b>
                        @endif
                        @endif

                        @else
                        Hasil Pencarian Tanggal <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
                        @endif
                        <div class="table-responsive">
                            <table id="list-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Supplier</th>
                                        <th>Pembuat</th>
                                        <th>Tgl Buat</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Terbayar</th>
                                        <th class="text-right">Kekurangan</th>
                                        <th>Status</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1; @endphp
                                    @foreach($data as $row)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->kode}}</td>
                                        <td>{{$row->namasupplier}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->tgl_buat}}</td>
                                        <td class="text-right">Rp. {{number_format($row->total,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row->terbayar,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row->kekurangan,0,',','.')}}</td>
                                        <td>{{$row->status}}</td>
                                        <td class="text-center"><a href="{{url('/backend/pembelian/'.$row->kode)}}"
                                                class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                    @php $no++; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Supplier</th>
                                        <th>Pembuat</th>
                                        <th>Tgl Buat</th>
                                        <th>Total</th>
                                        <th>Terbayar</th>
                                        <th>Kekurangan</th>
                                        <th>Status</th>
                                        <th class="text-center">#</th>
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
<div id="print_div" style="display:none;">
    @if(Request::has('tanggal'))
    Laporan Pembelian @if(Request::has('tanggal')) Tanggal <b>{{Request::get('tanggal')}}</b> @endif
    @if(Request::has('supplier'))
    @if(Request::get('supplier')!='Semua')
    @php
    $supplier_data = DB::table('master_supplier')->where('kode',Request::get('supplier'))->get();
    @endphp
    @foreach($supplier_data as $row_supplier_data)
    Customer <b>{{$row_supplier_data->kode}} {{$row_supplier_data->nama}}</b>
    @endforeach
    @endif
    @endif

    @if(Request::has('pembuat'))
    @if(Request::get('pembuat')!='Semua')
    @php
    $pembuat_data = DB::table('users')->where('id',Request::get('pembuat'))->get();
    @endphp
    @foreach($pembuat_data as $row_pembuat_data)
    Pembuat <b>{{$row_pembuat_data->name}}</b>
    @endforeach
    @endif
    @endif

    @if(Request::has('status'))
    @if(Request::get('status')!='Semua')
    Status <b>{{Request::get('status')}}</b>
    @endif
    @endif

    @else
    Laporan Pembelian Tanggal <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
    @endif
    <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data_penjualan">
        <thead>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Kode</th>
                <th style="padding:3px;">Supplier</th>
                <th style="padding:3px;">Pembuat</th>
                <th style="padding:3px;">Tgl Buat</th>
                <th style="padding:3px;" align="right">Total</th>
                <th style="padding:3px;" align="right">Terbayar</th>
                <th style="padding:3px;" align="right">Kekurangan</th>
                <th style="padding:3px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($data as $row)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row->kode}}</td>
                <td style="padding:3px;">{{$row->namasupplier}}</td>
                <td style="padding:3px;">{{$row->name}}</td>
                <td style="padding:3px;">{{$row->tgl_buat}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->total,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->terbayar,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->kekurangan,0,',','.')}}</td>
                <td style="padding:3px;">{{$row->status}}</td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('customjs/backend/xlsx.full.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$(function() {
    $('#tanggal').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
})

function cetak() {
    var divToPrint = document.getElementById('print_div');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML +
        '</body></html>');
    newWin.document.close();
}

function html_table_to_excel(type) {
    var data = document.getElementById('data_penjualan');

    var file = XLSX.utils.table_to_book(data, {
        sheet: "sheet1"
    });

    XLSX.write(file, {
        bookType: type,
        bookSST: true,
        type: 'base64'
    });

    XLSX.writeFile(file, 'file.' + type);
}

const export_button = document.getElementById('export_button');

export_button.addEventListener('click', () => {
    html_table_to_excel('xlsx');
});
</script>
@endpush