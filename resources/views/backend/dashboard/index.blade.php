@extends('layouts/base')
@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">

            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Dashboard</h1>
                <span>You are loggin as {{Auth::user()->level}}</span>
            </div>
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
            <div class="col-lg-12">
                <div class="jumbotron jumbotron-fluid pr-4 pl-4">
                    <div class="container">
                        <h1 class="display-4">Welcome To The Dashboard</h1>
                        <p class="lead">This boiler is use <a href="https://adminlte.io" target="blank()">adminLTE</a> for backend template.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection