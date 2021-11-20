<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;

class barangController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-barang', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-barang', ['only' => ['create']]);
        $this->middleware('permission:edit-barang', ['only' => ['edit']]);
        $this->middleware('permission:delete-barang', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        return view('backend.barang.index');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(
            DB::table('barang')
            ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
            ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
            ->orderby('barang.id','desc')
            ->get()
            )->make(true);
    }

    //=================================================================
    public function create()
    {
        $kategori=DB::table('kategori_barang')->orderby('id','desc')->get();
        return view('backend.barang.create',compact('kategori'));
    }

    //=================================================================
    public function store(Request $request)
    {
        $kode = $this->carikode();
        DB::table('barang')
        ->insert([
            'kode'=>$kode,
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual,
            'harga_jual_customer'=>$request->harga_grosir,
            'diskon'=>$request->diskon,
            'diskon_customer'=>$request->diskon_grosir,
            'stok'=>0,
            'keterangan'=>$request->keterangan,
        ]);
        return redirect('/backend/barang')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function carikode()
    {
        $carikode = DB::table('barang')->max('kode');
        if(!$carikode){
            $finalkode = 'BRG-0001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%04s",$newkode[1]+1);
            $finalkode = 'BRG-'.$nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function show($id)
    {
        $kategori=DB::table('kategori_barang')->orderby('id','desc')->get();
        $data=DB::table('barang')->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
        ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
        ->where('barang.id',$id)->get();
        return view('backend.barang.show',compact('kategori','data'));
    }

    //=================================================================
    public function edit($id)
    {
        $kategori=DB::table('kategori_barang')->orderby('id','desc')->get();
        $data=DB::table('barang')->where('id',$id)->get();
        return view('backend.barang.edit',compact('kategori','data'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('barang')
        ->where('id',$id)
        ->update([
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>$request->harga_beli,
            'harga_jual'=>$request->harga_jual,
            'harga_jual_customer'=>$request->harga_grosir,
            'diskon'=>$request->diskon,
            'diskon_customer'=>$request->diskon_grosir,
            'stok'=>0,
            'keterangan'=>$request->keterangan,
        ]);
        return redirect('/backend/barang')->with('status','Sukses memeperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        $data = DB::table('barang')->where('id',$id)->get();
        foreach ($data as $row) {
            DB::table('log_stok_barang')->where('kode_barang',$row->kode)->delete();
        }
        DB::table('barang')->where('id',$id)->delete();
    }
}
