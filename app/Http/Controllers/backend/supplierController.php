<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class supplierController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-supplier', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-supplier', ['only' => ['create']]);
        $this->middleware('permission:edit-supplier', ['only' => ['edit']]);
        $this->middleware('permission:delete-supplier', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        return view('backend.supplier.index');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(DB::table('master_supplier')->orderby('id','desc')->get())->make(true);
    }

    //=================================================================
    public function create()
    {
        return view('backend.supplier.create');
    }

     //=================================================================
     public function store(Request $request)
     {
         $kode = $this->carikode();
         DB::table('master_supplier')
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
         $carikode = DB::table('master_supplier')->max('kode');
         if(!$carikode){
             $finalkode = 'SUP-001';
         }else{
             $newkode    = explode("-", $carikode);
             $nomer      = sprintf("%03s",$newkode[2]+1);
             $finalkode = 'SUP-'.$nomer;
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
         $data = DB::table('master_supplier')
         ->where('id',$id)
         ->get();
         return view('backend.supplier.edit',compact('data'));
     }
 
     //=================================================================
     public function update(Request $request, $id)
     {
         DB::table('master_supplier')
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
         DB::table('master_supplier')
         ->where('id',$id)
         ->delete();
     }
}
