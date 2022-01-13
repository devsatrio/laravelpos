@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Barang</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Cetak Barcode Barang</h3>
                    </div>
                    <form method="get" role="form">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Pilih Barang Yang Akan Dicetak</label>
                                        <select class="form-control select2" id="barang" name="barang[]" required
                                            multiple="multiple">
                                            @foreach($barang as $row_barang)
                                            <option value="{{$row_barang->kode}}">{{$row_barang->kode}} -
                                                {{$row_barang->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @if(Request::get('barang')!='')
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Hasil Barcode Barang</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach(Request::get('barang') as $kode_barang)
                            @php
                            $databarang = DB::table('barang')->where('kode',$kode_barang)->get();
                            @endphp
                            @foreach($databarang as $row_databarang)

                            <div class="col-md-3 text-center">
                                @php
                                $redColor = [255, 0, 0];
                                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                echo '<img class="img-thumbnail"
                                    src="data:image/png;base64,' . base64_encode($generator->getBarcode($row_databarang->kode_qr, $generator::TYPE_CODE_128)) . '">';
                                @endphp
                                <br>
                                <p>{{$row_databarang->nama}} - {{$row_databarang->kode_qr}}</p>
                                @endforeach

                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="cetakbarcode()" class="btn btn-info float-right m-1">Cetak Versi 1</button>
                        <button type="button" onclick="cetakbarcodedua()" class="btn btn-info float-right m-1">Cetak Versi 2</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


@if(Request::get('barang')!='')
@php
$nomorrow=1;
@endphp
<div id="print_div" style="display:none;">
    <table width="100%" border="1">
        <tr>
            @foreach(Request::get('barang') as $kode_barang)
            @php
            $databarang = DB::table('barang')->where('kode',$kode_barang)->get();
            @endphp
            @foreach($databarang as $row_databarang)

            <td width="25%" align="center" style="padding-top:20px;">
                @php
                $redColor = [255, 0, 0];
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                echo '<img class="img-thumbnail"
                    src="data:image/png;base64,' . base64_encode($generator->getBarcode($row_databarang->kode_qr, $generator::TYPE_CODE_128)) . '">';
                @endphp
                <br>
                <p>{{$row_databarang->kode_qr}}</p>


                @endforeach

                @if($nomorrow==4)
            </td>
        </tr>
        <tr>
            @else
            </td>
            @endif
            @php
            $nomorrow++;
            @endphp
            @endforeach
    </table>
</div>

<div id="print_div_dua" style="display:none;">
    @foreach(Request::get('barang') as $kode_barang)
    @php
    $databarang = DB::table('barang')->where('kode',$kode_barang)->get();
    @endphp
    @foreach($databarang as $row_databarang)
    <table width="100%" border="0">
        <tr>
            <td width="25%" align="center" style="padding-top:10px;">
                @php
                $redColor = [255, 0, 0];
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                echo '<img class="img-thumbnail"
                    src="data:image/png;base64,' . base64_encode($generator->getBarcode($row_databarang->kode_qr, $generator::TYPE_CODE_128)) . '">';
                @endphp
                <span>{{$row_databarang->kode_qr}}</span>
            </td>
        </tr>
    </table>
    <div style='break-after:always'></div>
    @endforeach
    @endforeach
</div>
@endif
@endsection
@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$(function() {
    $('#barang').select2();
});

function cetakbarcode() {
    var divToPrint = document.getElementById('print_div');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML +
        '</body></html>');
    newWin.document.close();
}

function cetakbarcodedua() {
    var divToPrint = document.getElementById('print_div_dua');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML +
        '</body></html>');
    newWin.document.close();
}
</script>
@endpush