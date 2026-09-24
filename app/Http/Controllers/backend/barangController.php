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
    public function index(Request $request)
    {
        $kategoribarang = DB::table('kategori_barang')->orderby('id','desc')->get();
        $data = DB::table('barang')
        ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
        ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori');

        if($request->has('kategori')){
            if($request->kategori!='semua'){
                $data=$data->where('barang.kategori',$request->kategori);
            }
        }
        if($request->has('stok')){
            if($request->stok!='semua'){
                $data=$data->where('barang.hitung_stok',$request->stok);
            }
        }

        if($request->has('nama')){
            if($request->nama!=null){
                $cari = $request->nama;
                $data=$data->where(function($query) use ($cari){
                    $query->where('barang.nama','like','%'.$cari.'%')
                    ->orwhere('barang.kode','like','%'.$cari.'%')
                    ->orWhereExists(function ($sub) use ($cari) {
                        $sub->select(DB::raw(1))
                            ->from('barang_barcode')
                            ->whereColumn('barang_barcode.id_barang', 'barang.id')
                            ->where('barang_barcode.kode_barcode', 'like', '%'.$cari.'%');
                    });
                });
            }
        }

        $data=$data->orderby('barang.id','desc')
        ->paginate(50);
        return view('backend.barang.index',compact('kategoribarang','data'));
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
            ->where(function($query) use ($cari){
                $query->where('barang.kode','like','%'.$cari.'%')
                ->orwhere('barang.nama','like','%'.$cari.'%')
                ->orWhereExists(function ($sub) use ($cari) {
                    $sub->select(DB::raw(1))
                        ->from('barang_barcode')
                        ->whereColumn('barang_barcode.id_barang', 'barang.id')
                        ->where('barang_barcode.kode_barcode', 'like', '%'.$cari.'%');
                });
            })
            ->get();
            
            return response()->json($data);
        }
    }

    //=================================================================
    public function caridetailbarangnonstok(Request $request)
    {
        if($request->has('q')){
            $cari = $request->q;
            
            $data = DB::table('barang')
            ->where('hitung_stok','=','y')
            ->where(function($query) use ($cari){
                $query->where('barang.kode','like','%'.$cari.'%')
                ->orwhere('barang.nama','like','%'.$cari.'%')
                ->orWhereExists(function ($sub) use ($cari) {
                    $sub->select(DB::raw(1))
                        ->from('barang_barcode')
                        ->whereColumn('barang_barcode.id_barang', 'barang.id')
                        ->where('barang_barcode.kode_barcode', 'like', '%'.$cari.'%');
                });
            })
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
        $barang_id = DB::table('barang')
        ->insertGetId([
            'kode'=>$kode,
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>str_replace('.','',$request->harga_beli),
            'harga_jual'=>str_replace('.','',$request->harga_jual),
            'harga_jual_customer'=>str_replace('.','',$request->harga_grosir),
            'diskon'=>$request->diskon,
            'diskon_customer'=>$request->diskon_grosir,
            'hitung_stok'=>$request->hitung_stok,
            'stok'=>0,
            'keterangan'=>$request->keterangan,
        ]);

        if ($request->has('barcode')) {
            $barcodes = is_array($request->barcode) ? $request->barcode : [$request->barcode];
            $insertBarcodes = [];
            foreach ($barcodes as $bc) {
                $bc = trim($bc);
                if (!empty($bc) && !in_array($bc, array_column($insertBarcodes, 'kode_barcode'))) {
                    $insertBarcodes[] = [
                        'id_barang' => $barang_id,
                        'kode_barcode' => $bc,
                    ];
                }
            }
            if (!empty($insertBarcodes)) {
                DB::table('barang_barcode')->insert($insertBarcodes);
            }
        }

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
        $barcodes = DB::table('barang_barcode')->where('id_barang', $id)->get();
        return view('backend.barang.show',compact('kategori','data','barcodes'));
    }

    //=================================================================
    public function edit($id)
    {
        $kategori=DB::table('kategori_barang')->orderby('id','desc')->get();
        $data=DB::table('barang')->where('id',$id)->get();
        $barcodes=DB::table('barang_barcode')->where('id_barang',$id)->get();
        return view('backend.barang.edit',compact('kategori','data','barcodes'));
    }

    //=================================================================
    public function cetakbarcodebarang()
    {
        $barang = DB::table('barang')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('barang_barcode')
                    ->whereColumn('barang_barcode.id_barang', 'barang.id');
            })
            ->orderby('id', 'desc')
            ->get();
        return view('backend.barang.cetakbarcode',compact('barang'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('barang')
        ->where('id',$id)
        ->update([
            'nama'=>$request->nama,
            'kategori'=>$request->kategori,
            'harga_beli'=>str_replace('.','',$request->harga_beli),
            'harga_jual'=>str_replace('.','',$request->harga_jual),
            'harga_jual_customer'=>str_replace('.','',$request->harga_grosir),
            'diskon'=>$request->diskon,
            'hitung_stok'=>$request->hitung_stok,
            'diskon_customer'=>$request->diskon_grosir,
            'keterangan'=>$request->keterangan,
        ]);

        DB::table('barang_barcode')->where('id_barang', $id)->delete();
        if ($request->has('barcode')) {
            $barcodes = is_array($request->barcode) ? $request->barcode : [$request->barcode];
            $insertBarcodes = [];
            foreach ($barcodes as $bc) {
                $bc = trim($bc);
                if (!empty($bc) && !in_array($bc, array_column($insertBarcodes, 'kode_barcode'))) {
                    $insertBarcodes[] = [
                        'id_barang' => $id,
                        'kode_barcode' => $bc,
                    ];
                }
            }
            if (!empty($insertBarcodes)) {
                DB::table('barang_barcode')->insert($insertBarcodes);
            }
        }

        return redirect('/backend/barang')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        $data = DB::table('barang')->where('id',$id)->get();
        foreach ($data as $row) {
            DB::table('log_stok_barang')->where('kode_barang',$row->kode)->delete();
        }
        DB::table('barang_barcode')->where('id_barang', $id)->delete();
        DB::table('barang')->where('id',$id)->delete();
    }
}