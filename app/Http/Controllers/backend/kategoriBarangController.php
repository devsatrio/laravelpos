<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\KategoriBarangExport;
use Excel;
use DataTables;
use DB;

class kategoriBarangController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-kategori-barang', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-kategori-barang', ['only' => ['create','store']]);
        $this->middleware('permission:edit-kategori-barang', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-kategori-barang', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        return view('backend.kategoribarang.index');
    }

    //==================================================================
    public function exsportexcel(){
        return Excel::download(new KategoriBarangExport, 'Data Kategori Barang.xlsx');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(DB::table('kategori_barang')->orderby('id','desc')->get())->make(true);
    }

    //=================================================================
    public function create()
    {
        
    }

    //=================================================================
    public function store(Request $request)
    {
        DB::table('kategori_barang')
        ->insert([
            'nama'=>$request->nama,
            'slug'=>strtolower(str_replace(' ','-',$request->nama)),
        ]);
        return redirect('/backend/kategori-barang')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function show($id)
    {
        $data = DB::table('kategori_barang')
        ->where('id',$id)
        ->get();

        return response()->json($data);
    }

    //=================================================================
    public function edit($id)
    {
        //
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('kategori_barang')
        ->where('id',$id)
        ->update([
            'nama'=>$request->nama,
            'slug'=>strtolower(str_replace(' ','-',$request->nama)),
        ]);
        return redirect('/backend/kategori-barang')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        DB::table('kategori_barang')
        ->where('id',$id)
        ->delete();
    }
}
