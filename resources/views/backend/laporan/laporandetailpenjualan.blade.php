@extends('layouts/base')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12 text-center">
                <h1 class="m-0 text-dark"> Laporan Detail Penjualan</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluit">
        <div class="row pr-5 pl-5">
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
                                        <select name="customer" class="form-control select2">
                                            <option @if(Request::has('customer')) @if(Request::get('customer')=='Semua'
                                                ) selected @endif @endif value="Semua" selected>Semua Customer</option>
                                            <option @if(Request::has('customer')) @if(Request::get('customer')=='' )
                                                selected @endif @endif value="">-</option>
                                            @foreach($datacustomer as $row_datacustomer)
                                            <option @if(Request::has('customer'))
                                                @if(Request::get('customer')==$row_datacustomer->kode) selected @endif
                                                @endif value="{{$row_datacustomer->kode}}">{{$row_datacustomer->kode}} -
                                                {{$row_datacustomer->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-3">
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
                                <div class="col-md-3 mt-3">
                                    <div class="input-group">
                                        <select class="form-control select2" id="barang" name="barang">
                                            <option @if(Request::has('barang')) @if(Request::get('barang')=='Semua' )
                                                selected @endif @endif value="Semua" selected>Semua Barang</option>
                                            @foreach($databarang as $row_databarang)
                                            <option @if(Request::has('barang'))
                                                @if(Request::get('barang')==$row_databarang->kode) selected @endif
                                                @endif
                                                value="{{$row_databarang->kode}}">{{$row_databarang->kode}} -
                                                {{$row_databarang->nama}}</option>
                                            @endforeach
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
                                            <a href="{{url('/backend/laporan-detail-penjualan')}}"
                                                class="btn btn-secondary"><i class="fas fa-sync"></i></a>
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

                        @if(Request::has('customer'))
                        @if(Request::get('customer')!='Semua')
                        @php
                        $customer_data = DB::table('master_customer')->where('kode',Request::get('customer'))->get();
                        @endphp
                        @foreach($customer_data as $row_customer_data)
                        Customer <b>{{$row_customer_data->nama}}</b>
                        @endforeach
                        @endif
                        @endif

                        @if(Request::has('barang'))
                        @if(Request::get('barang')!='Semua')
                        @php
                        $barang_data = DB::table('barang')->where('kode',Request::get('barang'))->get();
                        @endphp
                        @foreach($barang_data as $row_barang_data)
                        Barang <b>{{$row_barang_data->kode}} - {{$row_barang_data->nama}}</b>
                        @endforeach
                        @endif
                        @endif

                        @else
                        Hasil Pencarian <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
                        @endif
                        <div class="table-responsive">
                            <table id="list-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Penjualan</th>
                                        <th>Tgl Buat</th>
                                        <th>Pembuat</th>
                                        <th>Customer</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Diskon</th>
                                        <th class="text-right">Harga Beli</th>
                                        <th class="text-right">Harga Jual</th>
                                        <th class="text-right">Total Beli</th>
                                        <th class="text-right">Total Jual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    $total_jual=0;
                                    $total_beli=0;
                                    @endphp
                                    @foreach($data as $row)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$row->kode_penjualan}}</td>
                                        <td>{{$row->tgl_buat}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>@if($row->namacustomer=='') - @else {{$row->namacustomer}} @endif</td>
                                        <td>{{$row->kode_barang}} - {{$row->nama}}</td>
                                        <td>{{$row->jumlah}} Pcs</td>
                                        <td>{{$row->diskon}} %</td>
                                        <td class="text-right">Rp. {{number_format($row->harga_beli,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row->harga,0,',','.')}}</td>
                                        <td class="text-right">Rp.
                                            {{number_format($row->harga_beli*$row->jumlah,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row->total,0,',','.')}}</td>
                                    </tr>
                                    @php
                                    $i++;
                                    $total_jual+=$row->total;
                                    $total_beli+=$row->harga_beli*$row->jumlah;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11" class="text-right"><b>Total Beli</b></td>
                                        <td class="text-right"><b>Rp. {{number_format($total_beli,0,',','.')}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" class="text-right"><b>Total Jual</b></td>
                                        <td class="text-right"><b>Rp. {{number_format($total_jual,0,',','.')}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" class="text-right"><b>Total Laba</b></td>
                                        <td class="text-right"><b>Rp.
                                                {{number_format($total_jual - $total_beli,0,',','.')}}</b></td>
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
    Laporan Detail Penjualan @if(Request::has('tanggal')) Tanggal <b>{{Request::get('tanggal')}}</b> @endif

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

    @if(Request::has('customer'))
    @if(Request::get('customer')!='Semua')
    @php
    $customer_data = DB::table('master_customer')->where('kode',Request::get('customer'))->get();
    @endphp
    @foreach($customer_data as $row_customer_data)
    Customer <b>{{$row_customer_data->nama}}</b>
    @endforeach
    @endif
    @endif

    @if(Request::has('barang'))
    @if(Request::get('barang')!='Semua')
    @php
    $barang_data = DB::table('barang')->where('kode',Request::get('barang'))->get();
    @endphp
    @foreach($barang_data as $row_barang_data)
    Barang <b>{{$row_barang_data->kode}} - {{$row_barang_data->nama}}</b>
    @endforeach
    @endif
    @endif

    @else
    Laporan Detail Penjualan <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
    @endif
    <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data_penjualan">
        <thead>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Kode Penjualan</th>
                <th style="padding:3px;">Tgl Buat</th>
                <th style="padding:3px;">Pembuat</th>
                <th style="padding:3px;">Customer</th>
                <th style="padding:3px;">Barang</th>
                <th style="padding:3px;">Jumlah</th>
                <th style="padding:3px;">Diskon</th>
                <th style="padding:3px;" align="right">Harga</th>
                <th style="padding:3px;" align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($data as $row)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row->kode_penjualan}}</td>
                <td style="padding:3px;">{{$row->tgl_buat}}</td>
                <td style="padding:3px;">{{$row->name}}</td>
                <td style="padding:3px;">@if($row->namacustomer=='') - @else {{$row->namacustomer}} @endif</td>
                <td style="padding:3px;">{{$row->kode_barang}} - {{$row->nama}}</td>
                <td style="padding:3px;">{{$row->jumlah}} Pcs</td>
                <td style="padding:3px;">{{$row->diskon}} %</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->harga,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->total,0,',','.')}}</td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" style="padding:3px;" align="right"><b>Total Beli</b></td>
                <td style="padding:3px;" align="right"><b>Rp. {{number_format($total_beli,0,',','.')}}</b></td>
            </tr>
            <tr>
                <td colspan="9" style="padding:3px;" align="right"><b>Total Jual</b></td>
                <td style="padding:3px;" align="right"><b>Rp. {{number_format($total_jual,0,',','.')}}</b></td>
            </tr>
            <tr>
                <td colspan="9" style="padding:3px;" align="right"><b>Total Laba</b></td>
                <td  style="padding:3px;" align="right"><b>Rp. {{number_format($total_jual - $total_beli,0,',','.')}}</b></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@push('customjs')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('customjs/backend/xlsx.full.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$(function() {
    $('.select2').select2();
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