@extends('layouts/base')

@section('customcss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link href="{{ asset('assets/loadingjs/loading.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-1">
                    <div class="col-sm-12 text-center">
                        <h1 class="m-0 text-dark">Detail Data Penjualan</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container">
                @foreach ($detail as $row_detail)
                    <div class="row">
                        <div class="col-md-8">
                            <div class="loading-div" id="paneldua">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Detail Data</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Kode Pembelian</label>
                                                    <p>{{ $row_detail->kode }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Tgl. Order</label>
                                                    <p>{{ $row_detail->tgl_buat }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Customer</label>
                                                    <p>{{ $row_detail->kodecustomer }} - {{ $row_detail->namacustomer }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Barang</th>
                                                        <th class="text-center">Diskon</th>
                                                        <th class="text-center">Jumlah</th>
                                                        <th class="text-right">Harga</th>
                                                        <th class="text-right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item as $row_item)
                                                        <tr>
                                                            <td>{{ $row_item->kode_barang }} - {{ $row_item->namabarang }}
                                                            </td>
                                                            <td class="text-center">{{ $row_item->diskon }} %</td>
                                                            <td class="text-center">{{ $row_item->jumlah }} Pcs</td>
                                                            <td class="text-right">Rp.
                                                                {{ number_format($row_item->harga, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-right">Rp.
                                                                {{ number_format($row_item->total, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4">Subtotal</th>
                                                        <th class="text-right">Rp.
                                                            {{ number_format($row_detail->subtotal, 0, ',', '.') }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Biaya Tambahan</label>
                                                    <p>Rp. {{ number_format($row_detail->biaya_tambahan, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Potongan</label>
                                                    <p>Rp. {{ number_format($row_detail->potongan, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Total</label>
                                                    <p>Rp. {{ number_format($row_detail->total, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Dibayar</label>
                                                    <p>Rp. {{ number_format($row_detail->terbayar, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Kekurangan</label>
                                                    <p>Rp. {{ number_format($row_detail->kekurangan, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Kembalian</label>
                                                    <p>Rp. {{ number_format($row_detail->kembalian, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Pembuat</label>
                                                    @php
                                                        $datapembuat = DB::table('users')
                                                            ->where('id', $row_detail->created_by)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($datapembuat as $row_datapembuat)
                                                        <p>{{ $row_datapembuat->name }} - {{ $row_detail->created_at }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Keterangan</label>
                                                    <p>{{ $row_detail->keterangan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="reset" onclick="history.go(-1)"
                                            class="btn btn-danger">Kembali</button>
                                        <button type="button" onclick="cetak()"
                                            class="btn btn-info float-right">Cetak</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="loading-div" id="panelsatu">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">History Pembayaran</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <ul class="products-list product-list-in-card pl-2 pr-2">
                                            @foreach ($pembayaran as $row_pembayaran)
                                                <li class="item ml-2">
                                                    <a href="javascript:void(0)" class="product-title">Rp.
                                                        {{ number_format($row_pembayaran->jumlah, 0, ',', '.') }}
                                                        <span
                                                            class="badge badge-warning float-right">{{ $row_pembayaran->tgl_bayar }}</span></a>
                                                    <span class="product-description">
                                                        {{ $row_pembayaran->name }} - {{ $row_pembayaran->created_at }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
    @php
        $websetting = DB::table('settings')->orderby('id', 'desc')->limit(1)->get();
    @endphp
    @foreach ($detail as $row_detail)
        <div id="print_div" style="display:none;">
            <table width="100%">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    @foreach ($websetting as $row_websetting)
                                        @if ($row_websetting->logo != '')
                                            <img src="{{ asset('img/setting/' . $row_websetting->logo) }}" alt=""
                                                class="img-thumb" width="170px;">
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($websetting as $row_websetting)
                                        <h4 style="margin: 0px;"><b>{{ $row_websetting->instansi }}</b></h4>
                                        <span>{{ $row_websetting->alamat }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right">
                        <br>
                        <h6><b>INVOICE PEMBELIAN</b></h6>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%">
                <tr>
                    <td width="50%">
                        <table>
                            <tr>
                                <td>Kode Pembelian</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ $row_detail->kode }}</td>
                            </tr>
                            <tr>
                                <td>Tgl. Order</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ $row_detail->tgl_buat }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" align="right">
                        <table>
                            <tr>
                                <td>Customer</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ $row_detail->kodecustomer }} - {{ $row_detail->namacustomer }}</td>
                            </tr>
                            <tr>
                                <td>Pembuat</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>
                                    @php
                                        $datapembuat = DB::table('users')->where('id', $row_detail->created_by)->get();
                                    @endphp
                                    @foreach ($datapembuat as $row_datapembuat)
                                        {{ $row_datapembuat->name }}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Tgl. Pembuatan</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>{{ $row_detail->created_at }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <hr>
            <p><b>Detail Item :</b></p>
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <thead>
                    <tr>
                        <td>Barang</td>
                        <td align="center">Diskon</td>
                        <td align="center">Jumlah</td>
                        <td align="right">Harga</td>
                        <td align="right">Total</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item as $row_item)
                        <tr>
                            <td>{{ $row_item->kode_barang }} - {{ $row_item->namabarang }}</td>
                            <td align="center">{{ $row_item->diskon }} %</td>
                            <td align="center">{{ $row_item->jumlah }} Pcs</td>
                            <td align="right">Rp. {{ number_format($row_item->harga, 0, ',', '.') }}
                            </td>
                            <td align="right">Rp. {{ number_format($row_item->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td align="right" colspan="4">Subtotal</td>
                        <td align="right">Rp.
                            {{ number_format($row_detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if ($row_detail->biaya_tambahan !='0')
                        <tr>
                            <td align="right" colspan="4">Biaya Tambahan</td>
                            <td align="right">Rp. {{ number_format($row_detail->biaya_tambahan, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if ($row_detail->potongan !='0')
                    <tr>
                        <td align="right" colspan="4">Potongan</td>
                        <td align="right">Rp. {{ number_format($row_detail->potongan, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td align="right" colspan="4">Total</td>
                        <td align="right">Rp. {{ number_format($row_detail->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td align="right" colspan="4">Dibayar</td>
                        <td align="right">Rp. {{ number_format($row_detail->terbayar, 0, ',', '.') }}</td>
                    </tr>
                    @if ($row_detail->kekurangan !='0')
                    <tr>
                        <td align="right" colspan="4">Kekurangan</td>
                        <td align="right">Rp. {{ number_format($row_detail->kekurangan, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if ($row_detail->kembalian !='0')
                    <tr>
                        <td align="right" colspan="4">Kembalian</td>
                        <td align="right">Rp. {{ number_format($row_detail->kembalian, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
            <hr>
            <table width="100%">
                <tr>
                    <td>
                        <b>Keterangan : </b>
                        <p>{{ $row_detail->keterangan }}</p>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
@endsection

@push('customjs')
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/loadingjs/loading.js') }}"></script>
@endpush

@push('customscripts')
    <!-- <script src="{{ asset('customjs/backend/penjualan_input.js') }}"></script> -->
    <script>
        function cetak() {
            var divToPrint = document.getElementById('print_div');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print();window.close()">' + divToPrint.innerHTML +
                '</body></html>');
            newWin.document.close();
        }
    </script>
@endpush
