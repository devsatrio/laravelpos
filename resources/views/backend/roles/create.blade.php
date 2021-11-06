@extends('layouts/base')

@section('customcss')
<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark"> Roles</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Data</h3>
                    </div>
                    <form method="POST" onsubmit="return validasiinput();" role="form" enctype="multipart/form-data"
                        action="{{url('backend/roles')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama Role</label>
                                <input type="text" class="form-control" name="nama" required autofocus>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1">Pilih Permission</label>
                                </div>
                                @foreach($data_permission as $row_data_permission)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="permission[]"
                                                id="customCheckbox{{$row_data_permission->id}}"
                                                value="{{$row_data_permission->id}}">
                                            <label for="customCheckbox{{$row_data_permission->id}}"
                                                class="custom-control-label">{{$row_data_permission->name}}</label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"
                                        id="checkall">
                                    <label for="checkall"
                                        class="custom-control-label">Pilih Semua</label>
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
        </div>
    </div>
</div>
@endsection
@push('customjs')
<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
@endpush

@push('customscripts')
<script>
$('#checkall').on('click',function(event) {
    if (this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }
});
</script>
@endpush