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
                <h1 class="m-0 text-dark"> Laporan Laba Rugi</h1>
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
                                <div class="col-md-4 mt-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="tanggal" id="tanggal"
                                            @if(Request::has('tanggal')) value="{{Request::get('tanggal')}}" @else
                                            value="{{date('Y-m-d')}} - {{date('Y-m-d')}}" @endif>
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                            <a href="{{url('/backend/laporan-laba-rugi')}}" class="btn btn-secondary"><i
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
                        @else
                        Hasil Pencarian <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
                        @endif
                        <div class="table-responsive">
                            <table id="list-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="9" class="text-center">Data Penjualan</th>
                                    </tr>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Customer</th>
                                        <th>Pembuat</th>
                                        <th>Tgl Buat</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Kekurangan</th>
                                        <th>Status</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $total_pemasukan=0;
                                    $total_pengeluaran=0;
                                    $total_piutang=0;
                                    $total_hutang=0;
                                    $no=1;
                                    @endphp
                                    @foreach($pemasukan_penjualan as $row_satu)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row_satu->kode}}</td>
                                        <td>@if($row_satu->namacustomer=='') - @else {{$row_satu->namacustomer}} @endif
                                        </td>
                                        <td>{{$row_satu->name}}</td>
                                        <td>{{$row_satu->tgl_buat}}</td>
                                        <td class="text-right">Rp. {{number_format($row_satu->total,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row_satu->kekurangan,0,',','.')}}
                                        </td>
                                        <td>{{$row_satu->status}}</td>
                                        <td class="text-center"><a href="{{url('/backend/penjualan/'.$row_satu->kode)}}"
                                                class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></a></td>
                                    </tr>

                                    @if($row_satu->status=='Telah Lunas')
                                    @php
                                    $total_pemasukan+=$row_satu->total;
                                    @endphp
                                    @else
                                    @php
                                    $total_pemasukan+=($row_satu->total-$row_satu->kekurangan);
                                    $total_piutang+=$row_satu->kekurangan;
                                    @endphp
                                    @endif

                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="9" class="text-center"><br> Data Pembelian</th>
                                    </tr>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Supplier</th>
                                        <th>Pembuat</th>
                                        <th>Tgl Buat</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Kekurangan</th>
                                        <th>Status</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1; @endphp
                                    @foreach($pengeluaran_pembelian as $row_tiga)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row_tiga->kode}}</td>
                                        <td>{{$row_tiga->namasupplier}}</td>
                                        <td>{{$row_tiga->name}}</td>
                                        <td>{{$row_tiga->tgl_buat}}</td>
                                        <td class="text-right">Rp. {{number_format($row_tiga->total,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row_tiga->kekurangan,0,',','.')}}
                                        </td>
                                        <td>{{$row_tiga->status}}</td>
                                        <td class="text-center"><a href="{{url('/backend/pembelian/'.$row_tiga->kode)}}"
                                                class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                    @if($row_tiga->status=='Telah Lunas')
                                    @php
                                    $total_pengeluaran+=$row_tiga->total;
                                    @endphp
                                    @else
                                    @php
                                    $total_pengeluaran+=($row_tiga->total-$row_tiga->kekurangan);
                                    $total_hutang+=$row_tiga->kekurangan;
                                    @endphp
                                    @endif

                                    @php $no++; @endphp
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="9" class="text-center"><br> Data Transaksi Lain</th>
                                    </tr>
                                    <tr>
                                        <th>No</th>
                                        <th>Pembuat</th>
                                        <th>Tgl Buat</th>
                                        <th>Status</th>
                                        <th colspan="2">Keterangan</th>
                                        <th colspan="3" class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1; @endphp
                                    @foreach($transaksi_lain as $row)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->tgl_buat}}</td>
                                        <td>{{$row->status}}</td>
                                        <td colspan="2">{{$row->keterangan}}</td>
                                        <td colspan="3" class="text-right">Rp. {{number_format($row->jumlah,0,',','.')}}
                                        </td>
                                    </tr>
                                    @if($row->status=='Pemasukan')
                                    @php
                                    $total_pemasukan+=$row->jumlah;
                                    @endphp
                                    @else
                                    @php
                                    $total_pengeluaran+=$row->jumlah;
                                    @endphp
                                    @endif

                                    @php $no++; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" class="text-center">Total</th>
                                    </tr>

                                    <tr>
                                        <th colspan="8" class="text-right">Total Pemasukan</th>
                                        <th class="text-right">Rp. {{number_format($total_pemasukan,0,',','.')}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-right">Total Pengeluaran</th>
                                        <th class="text-right">Rp. {{number_format($total_pengeluaran,0,',','.')}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-right">Total Piutang</th>
                                        <th class="text-right">Rp. {{number_format($total_piutang,0,',','.')}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-right">Total Hutang</th>
                                        <th class="text-right">Rp. {{number_format($total_hutang,0,',','.')}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-right">Total Kotor</th>
                                        <th class="text-right">Rp.
                                            {{number_format($total_pemasukan+$total_piutang-$total_pengeluaran-$total_hutang,0,',','.')}}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="8" class="text-right">Total Bersih</th>
                                        <th class="text-right">Rp.
                                            {{number_format($total_pemasukan-$total_pengeluaran,0,',','.')}}</th>
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
    @else
    Laporan Detail Penjualan <b>{{date('Y-m-d')}} - {{date('Y-m-d')}}</b>
    @endif
    <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data_penjualan">
        <thead>
            <tr>
                <th colspan="8" align="center">Data Penjualan</th>
            </tr>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Kode</th>
                <th style="padding:3px;">Customer</th>
                <th style="padding:3px;">Pembuat</th>
                <th style="padding:3px;">Tgl Buat</th>
                <th style="padding:3px;" align="right">Total</th>
                <th style="padding:3px;" align="right">Kekurangan</th>
                <th style="padding:3px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($pemasukan_penjualan as $row_satu)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row_satu->kode}}</td>
                <td style="padding:3px;">@if($row_satu->namacustomer=='') - @else {{$row_satu->namacustomer}} @endif
                </td>
                <td style="padding:3px;">{{$row_satu->name}}</td>
                <td style="padding:3px;">{{$row_satu->tgl_buat}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row_satu->total,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row_satu->kekurangan,0,',','.')}}
                </td>
                <td style="padding:3px;">{{$row_satu->status}}</td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>

        <thead>
            <tr>
                <th colspan="8" align="center"><br> Data Pembelian</th>
            </tr>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Kode</th>
                <th style="padding:3px;">Supplier</th>
                <th style="padding:3px;">Pembuat</th>
                <th style="padding:3px;">Tgl Buat</th>
                <th style="padding:3px;" align="right">Total</th>
                <th style="padding:3px;" align="right">Kekurangan</th>
                <th style="padding:3px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($pengeluaran_pembelian as $row_tiga)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row_tiga->kode}}</td>
                <td style="padding:3px;">{{$row_tiga->namasupplier}}</td>
                <td style="padding:3px;">{{$row_tiga->name}}</td>
                <td style="padding:3px;">{{$row_tiga->tgl_buat}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row_tiga->total,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row_tiga->kekurangan,0,',','.')}}
                </td>
                <td style="padding:3px;">{{$row_tiga->status}}</td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>

        <thead>
            <tr>
                <th colspan="8" align="center"><br> Data Transaksi Lain</th>
            </tr>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Pembuat</th>
                <th style="padding:3px;">Tgl Buat</th>
                <th style="padding:3px;">Status</th>
                <th style="padding:3px;" colspan="2">Keterangan</th>
                <th style="padding:3px;" colspan="2" align="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($transaksi_lain as $row)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row->name}}</td>
                <td style="padding:3px;">{{$row->tgl_buat}}</td>
                <td style="padding:3px;">{{$row->status}}</td>
                <td style="padding:3px;" colspan="2">{{$row->keterangan}}</td>
                <td style="padding:3px;" colspan="2" align="right">Rp. {{number_format($row->jumlah,0,',','.')}}
                </td>
            </tr>

            @php $no++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="padding:3px;" colspan="8" align="center">Total</th>
            </tr>

            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Pemasukan</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($total_pemasukan,0,',','.')}}</th>
            </tr>
            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Pengeluaran</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($total_pengeluaran,0,',','.')}}</th>
            </tr>
            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Piutang</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($total_piutang,0,',','.')}}</th>
            </tr>
            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Hutang</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($total_hutang,0,',','.')}}</th>
            </tr>
            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Kotor</th>
                <th style="padding:3px;" align="right">Rp.
                    {{number_format($total_pemasukan+$total_piutang-$total_pengeluaran-$total_hutang,0,',','.')}}
                </th>
            </tr>
            <tr>
                <th style="padding:3px;" colspan="7" align="right">Total Bersih</th>
                <th style="padding:3px;" align="right">Rp.
                    {{number_format($total_pemasukan-$total_pengeluaran,0,',','.')}}</th>
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