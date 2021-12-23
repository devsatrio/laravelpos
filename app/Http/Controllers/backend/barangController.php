<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\BarangImport;
use App\Exports\BarangExport;
use Session;
use Excel;
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
        $this->middleware('permission:cetak-barcode-barang', ['only' => ['cetakbarcodebarang']]);
        $this->middleware('permission:import-export-barang', ['only' => ['exsportexcel','importexcel']]);
    }

    //=================================================================
    public function index()
    {
        $kategoribarang = DB::table('kategori_barang')->orderby('id','desc')->get();
        return view('backend.barang.index',compact('kategoribarang'));
    }

    //==================================================================
    public function exsportexcel(){
        return Excel::download(new BarangExport, 'Data Barang.xlsx');
    }

    //==================================================================
    public function importexcel(Request $request)
    {
        try {
            if($request->hasFile('file_excel')){
                $error = Excel::import(new BarangImport, request()->file('file_excel'));
                return redirect('backend/barang')->with('status','Berhasil Import Data');
             }else{
                Session::flash('errorexcel_satu', 'error uploading data');
                return redirect('backend/barang');
             }
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            Session::flash('errorexcel', $failures);
            return redirect('backend/barang');
        }
    }

    //=================================================================
    public function caridetailbarang(Request $request)
    {
        if($request->has('q')){
            $cari = $request->q;
            
            $data = DB::table('barang')
            ->where('kode','like','%'.$cari.'%')
            ->orwhere('nama','like','%'.$cari.'%')
            ->get();
            
            return response()->json($data);
        }
    }

    //=================================================================
    public function pilihdetailbarang($kode)
    {
        $data = DB::table('barang')
            ->where('kode',$kode)
            ->get();
            
            return response()->json($data);
    }

    //=================================================================
    public function listdata($kategori){
        if($kategori=='semua'){
            return Datatables::of(
                DB::table('barang')
                ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
                ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
                ->orderby('barang.id','desc')
                ->get()
                )->make(true);
        }else{
            return Datatables::of(
                DB::table('barang')
                ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
                ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
                ->where('barang.kategori',$kategori)
                ->orderby('barang.id','desc')
                ->get()
                )->make(true);
        }
        
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
            'kode_qr'=>$request->kode_qr,
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>str_replace('.','',$request->harga_beli),
            'harga_jual'=>str_replace('.','',$request->harga_jual),
            'harga_jual_customer'=>str_replace('.','',$request->harga_grosir),
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
    public function cetakbarcodebarang()
    {
        $barang=DB::table('barang')->orderby('id','desc')->get();
        return view('backend.barang.cetakbarcode',compact('barang'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('barang')
        ->where('id',$id)
        ->update([
            'kode_qr'=>$request->kode_qr,
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>str_replace('.','',$request->harga_beli),
            'harga_jual'=>str_replace('.','',$request->harga_jual),
            'harga_jual_customer'=>str_replace('.','',$request->harga_grosir),
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