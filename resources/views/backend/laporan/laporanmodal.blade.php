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
                <h1 class="m-0 text-dark"> Laporan Pengeluaran/Pemasukan Lain</h1>
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
                                        <select class="form-control select2" id="kategori_barang"
                                            name="kategori_barang">
                                            <option @if(Request::has('kategori_barang'))
                                                @if(Request::get('kategori_barang')=='Semua' ) selected @endif @endif
                                                value="Semua" selected>Semua Kategori</option>
                                            @foreach($kategori_barang as $row_kategori_barang)
                                            <option @if(Request::has('kategori_barang'))
                                                @if(Request::get('kategori_barang')==$row_kategori_barang->id) selected
                                                @endif
                                                @endif
                                                value="{{$row_kategori_barang->id}}">{{$row_kategori_barang->nama}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                            <a href="{{url('/backend/laporan-modal')}}" class="btn btn-secondary"><i
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
                        @if(Request::has('kategori_barang'))
                        Hasil Pencarian
                        @if(Request::get('customer')!='Semua')
                        @php
                        $cari_kategori =
                        DB::table('kategori_barang')->where('id',Request::get('kategori_barang'))->get();
                        @endphp
                        @foreach($cari_kategori as $row_cari_kategori)
                        Kategori <b>{{$row_cari_kategori->nama}}</b>
                        @endforeach
                        @else
                        Kategori <b>Semua</b>
                        @endif
                        @else
                        Hasil Pencarian Kategori <b>Semua</b>
                        @endif
                        <div class="table-responsive">
                            <table id="list-data" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>kode</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th class="text-right">Harga Beli</th>
                                        <th class="text-right">Harga Jual</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-right">Total Beli</th>
                                        <th class="text-right">Total Jual</th>
                                        <th class="text-right">Laba</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no=1;
                                    $total_stok=0;
                                    $grand_total_beli =0;
                                    $grand_total_jual =0;
                                    $total_laba =0;
                                    @endphp
                                    @foreach($data_modal as $row)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row->kode}}</td>
                                        <td>{{$row->nama}}</td>
                                        <td>{{$row->namakategori}}</td>
                                        <td class="text-right">Rp. {{number_format($row->harga_beli,0,',','.')}}</td>
                                        <td class="text-right">Rp. {{number_format($row->harga_jual,0,',','.')}}</td>
                                        <td class="text-center">{{$row->stok}} Pcs</td>
                                        <td class="text-right">Rp.
                                            {{number_format($row->harga_beli*$row->stok,0,',','.')}}</td>
                                        <td class="text-right">Rp.
                                            {{number_format($row->harga_jual*$row->stok,0,',','.')}}</td>
                                        <td class="text-right">Rp.
                                            {{number_format(($row->harga_jual*$row->stok)-($row->harga_beli*$row->stok),0,',','.')}}
                                        </td>
                                    </tr>
                                    @php
                                    $no++;
                                    $total_stok+=$row->stok;
                                    $grand_total_beli +=$row->harga_beli*$row->stok;
                                    $grand_total_jual +=$row->harga_jual*$row->stok;
                                    $total_laba +=($row->harga_jual*$row->stok)-($row->harga_beli*$row->stok);
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-center">Total</th>
                                        <th class="text-center">{{$total_stok}} Pcs</th>
                                        <th class="text-right">Rp. {{number_format($grand_total_beli,0,',','.')}}</th>
                                        <th class="text-right">Rp. {{number_format($grand_total_jual,0,',','.')}}</th>
                                        <th class="text-right">Rp. {{number_format($total_laba,0,',','.')}}</th>
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
    @if(Request::has('kategori_barang'))
    Laporan Modal
    @if(Request::get('customer')!='Semua')
    @php
    $cari_kategori = DB::table('kategori_barang')->where('id',Request::get('kategori_barang'))->get();
    @endphp
    @foreach($cari_kategori as $row_cari_kategori)
    Kategori <b>{{$row_cari_kategori->nama}}</b>
    @endforeach
    @else
    Kategori <b>Semua</b>
    @endif
    @else
    Laporan Modal Kategori <b>Semua</b>
    @endif
    <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data_penjualan">
        <thead>
            <tr>
                <th style="padding:3px;">No</th>
                <th style="padding:3px;">Kode</th>
                <th style="padding:3px;">Nama</th>
                <th style="padding:3px;">Kategori</th>
                <th style="padding:3px;" align="right">Harga Beli</th>
                <th style="padding:3px;" align="right">Harga Jual</th>
                <th style="padding:3px;" align="center">Stok</th>
                <th style="padding:3px;" align="right">Total Beli</th>
                <th style="padding:3px;" align="right">Total Jual</th>
                <th style="padding:3px;" align="right">Laba</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($data_modal as $row)
            <tr>
                <td style="padding:3px;">{{$no}}</td>
                <td style="padding:3px;">{{$row->kode}}</td>
                <td style="padding:3px;">{{$row->nama}}</td>
                <td style="padding:3px;">{{$row->namakategori}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->harga_beli,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp. {{number_format($row->harga_jual,0,',','.')}}</td>
                <td style="padding:3px;" align="center">{{$row->stok}} Pcs</td>
                <td style="padding:3px;" align="right">Rp.
                    {{number_format($row->harga_beli*$row->stok,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp.
                    {{number_format($row->harga_jual*$row->stok,0,',','.')}}</td>
                <td style="padding:3px;" align="right">Rp.
                    {{number_format(($row->harga_jual*$row->stok)-($row->harga_beli*$row->stok),0,',','.')}}
                </td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="padding:3px;" align="center">Total</th>
                <th style="padding:3px;" align="center">{{$total_stok}} Pcs</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($grand_total_beli,0,',','.')}}</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($grand_total_jual,0,',','.')}}</th>
                <th style="padding:3px;" align="right">Rp. {{number_format($total_laba,0,',','.')}}</th>
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