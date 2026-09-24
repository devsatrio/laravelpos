@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
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
            @foreach($data as $row)
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Detail Data</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kode</label>
                                    <p>{{$row->kode}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Daftar Barcode</label>
                                    <p>
                                        @if(isset($barcodes) && count($barcodes) > 0)
                                            @foreach($barcodes as $bc)
                                                <span class="badge badge-info mr-1 mb-1 p-2" style="font-size: 13px;"><i class="fas fa-barcode"></i> {{$bc->kode_barcode}}</span>
                                            @endforeach
                                        @elseif($row->kode_qr != '')
                                            <span class="badge badge-info p-2" style="font-size: 13px;"><i class="fas fa-barcode"></i> {{$row->kode_qr}}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama</label>
                                    <p>{{$row->nama}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Kategori</label>
                                    <p>{{$row->namakategori}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hitung Stok</label>
                                    <p>
                                        @if ($row->hitung_stok=='y')
                                            Ya, Stok akan dihitung
                                        @else
                                            Tidak, Stok akan diabaikan
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(auth()->user()->can('view-harga-beli-barang'))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Beli</label>
                                    <p>Rp. {{number_format($row->harga_beli,0,',','.')}}</p>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Jual</label>
                                    <p>Rp. {{number_format($row->harga_jual,0,',','.')}}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Harga Grosir (Khusus untuk
                                        customer)</label>
                                    <p>Rp. {{number_format($row->harga_jual_customer,0,',','.')}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Diskon</label>
                                    <p>{{$row->diskon}} %</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Diskon Grosir (Khusus untuk
                                        customer)</label>
                                    <p>{{$row->diskon_customer}} %</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <p>{{$row->keterangan}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="reset" onclick="history.go(-1)" class="btn btn-danger">Kembali</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">BarCode Barang</h3>
                    </div>
                    @php
                        $listBarcode = [];
                        if(isset($barcodes) && count($barcodes) > 0) {
                            foreach($barcodes as $b) {
                                $listBarcode[] = $b->kode_barcode;
                            }
                        } elseif(!empty($row->kode_qr)) {
                            $listBarcode[] = $row->kode_qr;
                        }
                    @endphp
                    @if(count($listBarcode) > 0)
                    <div class="card-body text-center" style="max-height: 500px; overflow-y: auto;">
                        @php
                        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                        @endphp
                        @foreach($listBarcode as $bcCode)
                        <div class="mb-3 p-2 border rounded">
                            <img class="img-thumbnail"
                                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($bcCode, $generator::TYPE_CODE_128, 2, 40)) }}">
                            <p class="font-weight-bold mb-0 mt-1">{{$bcCode}}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="cetakbarcode()" class="btn btn-info btn-block">Cetak Semua Barcode</button>
                    </div>
                    @else
                    <div class="card-body text-center text-muted">
                        <p>Belum ada barcode untuk barang ini.</p>
                    </div>
                    @endif
                </div>
            </div>
            @if(count($listBarcode) > 0)
            <div id="print_div" style="display:none;">
                @php
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                @endphp
                @foreach($listBarcode as $bcCode)
                <div style="border-style: solid;padding:15px;width:300px;margin-bottom:15px;display:inline-block;" align="center">
                    <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($bcCode, $generator::TYPE_CODE_128)) }}">
                    <br>
                    <span style="font-size:14px;font-weight:bold;">{{$row->nama}}</span><br>
                    <span>{{$bcCode}}</span>
                </div>
                @endforeach
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
function cetakbarcode() {
    var divToPrint = document.getElementById('print_div');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML +
        '</body></html>');
    newWin.document.close();
}
</script>
@endpush