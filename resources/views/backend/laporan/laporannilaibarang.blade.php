@extends('layouts/base')

@section('token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('customcss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h1 class="m-0 text-dark"> Laporan Nilai Barang</h1>
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
                            <label class="mb-0">Cari Data Kategori Barang</label><br>
                            <form action="" method="get">
                                <div class="row mb-3">
                                    <div class="col-md-4 mt-3">
                                        <div class="input-group">
                                            <select name="kategori" id="kategori" class="form-control">
                                                <option value="semua"
                                                    @if (Request::has('kategori')) @if (Request::get('kategori') == 'semua') selected @endif
                                                    @endif>Semua Kategori</option>
                                                @foreach ($kategoribarang as $row_kategoribarang)
                                                    <option value="{{ $row_kategoribarang->id }}"
                                                        @if (Request::has('kategori')) @if (Request::get('kategori') == $row_kategoribarang->id)
                                                selected @endif
                                                        @endif>
                                                        {{ $row_kategoribarang->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit"><i
                                                        class="fa fa-search"></i></button>
                                                <a href="{{ url('/backend/laporan-nilai-barang') }}"
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
                            @if (Request::has('kategori'))
                                Laporan Nilai Barang Berdasarkan
                                @if (Request::get('kategori') == 'semua')
                                    <b>Semua</b> kategori
                                @else
                                    @php
                                        $cari_kategori = DB::table('kategori_barang')
                                            ->where('id', Request::get('kategori'))
                                            ->get();
                                    @endphp
                                    @foreach ($cari_kategori as $row_cari_kategori)
                                        Kategori <b>{{ $row_cari_kategori->nama }}</b>
                                    @endforeach
                                @endif
                            @else
                                Laporan Nilai Barang Berdasarkan <b>Semua</b> kategori
                            @endif
                            <div class="table-responsive">
                                <table id="list-data" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>No</th>
                                            <th>kode</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th class="text-center">Harga Jual</th>
                                            <th class="text-center">Harga Grosir</th>
                                            <th class="text-center">Harga Beli</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                        @endphp
                                        @foreach ($data as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row->kode }}</td>
                                                <td>{{ $row->nama }}</td>
                                                <td>{{ $row->namakategori }}</td>
                                                <td class="text-right">Rp.
                                                    {{ number_format($row->harga_jual, 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">Rp.
                                                    {{ number_format($row->harga_jual_customer, 0, ',', '.') }}</td>
                                                <td class="text-right">Rp.
                                                    {{ number_format($row->harga_beli, 0, ',', '.') }}
                                                </td>
                                                <td class="text-right">{{ $row->stok }} PCS</td>
                                                <td class="text-right">Rp.
                                                    {{ number_format($row->stok * $row->harga_beli, 0, ',', '.') }}</td>
                                            </tr>
                                            @php
                                                $total += $row->stok * $row->harga_beli;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-right" colspan="8">Total</th>
                                            <th class="text-right">Rp. {{ number_format($total, 0, ',', '.') }}</th>
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
        @if (Request::has('kategori'))
            Laporan Nilai Barang Berdasarkan
            @if (Request::get('kategori') == 'semua')
                <b>Semua</b> kategori
            @else
                @php
                    $cari_kategori = DB::table('kategori_barang')
                        ->where('id', Request::get('kategori'))
                        ->get();
                @endphp
                @foreach ($cari_kategori as $row_cari_kategori)
                    Kategori <b>{{ $row_cari_kategori->nama }}</b>
                @endforeach
            @endif
        @else
            Laporan Nilai Barang Berdasarkan <b>Semua</b> kategori
        @endif
        <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data_penjualan">
            <thead>
                <tr>
                    <th style="padding:3px;">No</th>
                    <th style="padding:3px;">kode</th>
                    <th style="padding:3px;">Nama</th>
                    <th style="padding:3px;">Kategori</th>
                    <th style="padding:3px;" align="right">Harga Jual</th>
                    <th style="padding:3px;" align="right">Harga Grosir</th>
                    <th style="padding:3px;" align="right">Harga Beli</th>
                    <th style="padding:3px;" align="right">Stok</th>
                    <th style="padding:3px;" align="right">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; @endphp
                @foreach ($data as $row)
                    <tr>
                        <td style="padding:3px;">{{ $no++ }}</td>
                        <td style="padding:3px;">{{ $row->kode }}</td>
                        <td style="padding:3px;">{{ $row->nama }}</td>
                        <td style="padding:3px;">{{ $row->namakategori }}</td>
                        <td style="padding:3px;" align="right">Rp.
                            {{ number_format($row->harga_jual, 0, ',', '.') }}
                        </td>
                        <td style="padding:3px;" align="right">Rp.
                            {{ number_format($row->harga_jual_customer, 0, ',', '.') }}</td>
                        <td style="padding:3px;" align="right">Rp.
                            {{ number_format($row->harga_beli, 0, ',', '.') }}
                        </td>
                        <td style="padding:3px;" align="right">{{ $row->stok }} PCS</td>
                        <td style="padding:3px;" align="right">Rp.
                            {{ number_format($row->stok * $row->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" style="padding:3px;" align="right">Total</th>
                    <th style="padding:3px;" align="right">Rp. {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('customjs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('customjs/backend/xlsx.full.min.js') }}"></script>
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
