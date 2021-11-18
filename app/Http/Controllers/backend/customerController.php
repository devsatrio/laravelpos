<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class customerController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-customer', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-customer', ['only' => ['create']]);
        $this->middleware('permission:edit-customer', ['only' => ['edit']]);
        $this->middleware('permission:delete-customer', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        return view('backend.customer.index');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(DB::table('master_customer')->orderby('id','desc')->get())->make(true);
    }

    //=================================================================
    public function create()
    {
        return view('backend.customer.create');
    }

    //=================================================================
    public function store(Request $request)
    {
        $kode = $this->carikode();
        DB::table('master_customer')
        ->insert([
            'kode'=>$kode,
            'nama'=>$request->nama,
            'telp'=>$request->telp,
            'alamat'=>$request->alamat,
            'keterangan'=>$request->keterangan,
        ]);
        return redirect('/backend/customer')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function carikode()
    {
        $carikode = DB::table('master_customer')->max('kode');
        if(!$carikode){
            $finalkode = 'CUS-001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%03s",$newkode[2]+1);
            $finalkode = 'CUS-'.$nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function show($id)
    {
        
    }

    //=================================================================
    public function edit($id)
    {
        $data = DB::table('master_customer')
        ->where('id',$id)
        ->get();
        return view('backend.customer.edit',compact('data'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('master_customer')
        ->where('id',$id)
        ->update([
            'nama'=>$request->nama,
            'telp'=>$request->telp,
            'alamat'=>$request->alamat,
            'keterangan'=>$request->keterangan,
        ]);
        return redirect('/backend/customer')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        DB::table('master_customer')
        ->where('id',$id)
        ->delete();
    }
}
