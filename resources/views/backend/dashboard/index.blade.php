@extends('layouts/base')
@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
        @php
            $websetting = DB::table('settings')->orderby('id','desc')->limit(1)->get();
            @endphp
            @foreach($websetting as $row_websetting)
            @if($row_websetting->logo!='')
            <div class="col-sm-3 text-center">
                <!-- <img src="{{asset('img/setting/'.$row_websetting->logo)}}" alt="" width="100%"><br> -->
                <span>You are loggin as {{Auth::user()->level}}</span>
            </div>
            <div class="col-md-9"></div>
            @else
            <div class="col-sm-3">
                <h1 class="m-0 text-dark"> Dashboard</h1>
                <span>You are loggin as {{Auth::user()->level}}</span>
            </div>
            <div class="col-md-9"></div>
            @endif
            @if($row_websetting->note_program!='')
            <div class="col-md-12">
                <div class="callout callout-info mt-4 mb-0">
                    <h5>Pengumuman & Informasi!</h5>
                    <p>{{$row_websetting->note_program}}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            @if (session('status'))
            <div class="col-sm-12">
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>Info!</h4>
                    {{ session('status') }}
                </div>
            </div>
            @endif
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$jumlahtransaksi}}</h3>
                        <p>Jumlah Transaksi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{url('/backend/penjualan')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$jumlahpelanggan}}</h3>
                        <p>Jumlah Customer</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{url('/backend/customer')}}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$jumlahsupplier}}</h3>
                        <p>Jumlah Supplier</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <a href="{{url('/backend/supplier')}}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{$jumlahbarang}}</h3>
                        <p>Jumlah Barang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <a href="{{url('/backend/barang')}}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Transaksi Mingguan</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="lineChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Kategori Barang Terlaris 2021</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Transaksi Tahunan</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="barChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Barang Stok Menipis</span>
                                <span class="info-box-number">{{$barangstokmenipis}} Barang</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-frown"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Semua Hutang</span>
                                <span class="info-box-number">{{$jumlahhutang}} Pembelian</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i
                                    class="fas fa-heartbeat"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Semua Piutang</span>
                                <span class="info-box-number">{{$jumlahpiutang}} Penjualan</span>
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
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$(function() {

    var areaChartData = {
        labels: [{!!substr($linelabelmgn, 0, -1)!!}],
        datasets: [{
            label: 'Transaksi Mingguan',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [{!!substr($linevaluemgn, 0, -1)!!}]
        }, ]
    }
    var areaChartDataDua = {
        labels: [{!!substr($linelabeltahun, 0, -1)!!}],
        datasets: [{
            label: 'Lunas',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [{!!substr($linevallunastahun, 0, -1)!!}]
        },{
            label: 'Belum Lunas',
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [{!!substr($linevalbelumlunastahun, 0, -1)!!}]
        }, ]
    }
    var donutData = {
        labels: [{!!substr($nama_kategori, 0, -1)!!}],
        datasets: [{
            data: [{!!substr($value_kategori, 0, -1)!!}],
            backgroundColor: [{!!substr($warna_kategori, 0, -1)!!}],
        }]
    }
    var areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,
                }
            }],
            yAxes: [{
                gridLines: {
                    display: false,
                }
            }]
        }
    }
    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
    var lineChartData = jQuery.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = donutData;
    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartDataDua)
    var temp1 = areaChartDataDua.datasets[0]
    var temp0 = areaChartDataDua.datasets[1]
    areaChartDataDua.datasets[0] = temp1
    areaChartDataDua.datasets[1] = temp0

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })

})
</script>
@endpush