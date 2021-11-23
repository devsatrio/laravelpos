<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

class pembelianController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(
            DB::table('pembelian')
            ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
            ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
            ->leftjoin('users','users.id','=','pembelian.pembuat')
            ->orderby('pembelian.id','desc')
            ->get()
            )->make(true);
    }

    //=================================================================
    public function adddetailpembelian(Request $request)
    {
        $caribarangdetail = DB::table('pembelian_thumb_detail')
        ->where([['kode_pembelian',$request->kode],['kode_barang',$request->kode_barang]])
        ->get();
        if(count($caribarangdetail)>0){
            foreach ($caribarangdetail as $row) {
                $jumlah=$row->jumlah + $request->jumlah_barang;
                $total = $row->total + str_replace('.','',$request->total_harga_barang);

                DB::table('pembelian_thumb_detail')
                ->where([['kode_pembelian',$request->kode],['kode_barang',$request->kode_barang]])
                ->update([
                    'jumlah'=>$jumlah,
                    'total'=>$total,
                ]);
            }
        }else{
            DB::table('pembelian_thumb_detail')
            ->insert([
                'kode_pembelian'=>$request->kode,
                'kode_barang'=>$request->kode_barang,
                'jumlah'=>$request->jumlah_barang,
                'harga'=>str_replace('.','',$request->harga_barang),
                'total'=>str_replace('.','',$request->total_harga_barang),
                'pembuat'=>Auth::user()->id,
            ]);
        }
    }

    //=================================================================
    public function hapusdetailpembelian(Request $request)
    {
        $data = DB::table('pembelian_thumb_detail')
        ->where('id',$request->kode)
        ->delete();
    }

    //=================================================================
    public function listdetailpembelian($kode)
    {
        $data = DB::table('pembelian_thumb_detail')
        ->select(DB::raw('pembelian_thumb_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','pembelian_thumb_detail.kode_barang')
        ->where([['pembelian_thumb_detail.pembuat',Auth::user()->id],['pembelian_thumb_detail.kode_pembelian',$kode]])
        ->get();
        return response()->json($data);
    }

    //=================================================================
    public function index()
    {
        return view('backend.pembelian.index');
    }

    //=================================================================
    public function create()
    {
        $kode = $this->carikode();
        $supplier = DB::table('master_supplier')->orderby('id','desc')->get();
        return view('backend.pembelian.create',compact('kode','supplier'));
    }

    //=================================================================
    public function carikode()
    {
        $datenow = date('mY');
        $carikode = DB::table('pembelian')->where('kode','like','%'.$datenow.'%')->max('kode');
        if(!$carikode){
            $finalkode = 'PMB-'.$datenow.'-0001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%04s",$newkode[2]+1);
            $finalkode = 'PMB-'.$datenow.'-'.$nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function store(Request $request)
    {
        $detail = DB::table('pembelian_thumb_detail')
        ->where('kode_pembelian',$request->kode)
        ->get();
        $data=[];
        foreach ($detail as $row) {
            $data[] = [
                'kode_pembelian'=>$row->kode_pembelian,
                'kode_barang'=>$row->kode_barang,
                'jumlah'=>$row->jumlah,
                'harga'=>$row->harga,
                'total'=>$row->total,
            ]; 
        }

        DB::table('pembelian_detail')->insert($data);
        
        $total = str_replace('.','',$request->subtotal)+str_replace('.','',$request->biaya_tambahan)+str_replace('.','',$request->potongan);
        DB::table('pembelian')
        ->insert([
            'kode'=>$request->kode,
            'supplier'=>$request->supplier,
            'subtotal'=>str_replace('.','',$request->subtotal),
            'biaya_tambahan'=>str_replace('.','',$request->biaya_tambahan),
            'potongan'=>str_replace('.','',$request->potongan),
            'total'=>$total,
            'terbayar'=>str_replace('.','',$request->dibayar),
            'kekurangan'=>str_replace('.','',$request->kekurangan),
            'pembuat'=>Auth::user()->id,
            'tgl_buat'=>$request->tgl_order,
            'keterangan'=>$request->keterangan,
        ]);

        DB::table('pembelian_thumb_detail')->where('pembuat',Auth::user()->id)->delete();
    }

    //=================================================================
    public function show($id)
    {
        //
    }

    //=================================================================
    public function edit($id)
    {
        //
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        //
    }

    //=================================================================
    public function destroy($id)
    {
        $data = DB::table('pembelian')->where('id',$id)->get();
        foreach ($data as $row) {
            DB::table('pembelian_detail')->where('kode_pembelian',$row->kode)->delete();
        }
        DB::table('pembelian')->where('id',$id)->delete();
    }
}
