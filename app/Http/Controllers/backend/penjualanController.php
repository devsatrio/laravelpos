<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use DB;

class penjualanController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    //=================================================================
    public function listdata(){
        
    }

    //=================================================================
    public function index()
    {
        return view('backend.penjualan.index');
    }

    //=================================================================
    public function create()
    {
        $kode = $this->carikode();
        // DB::table('penjualan_thumb_detail')
        // ->where('pembuat',Auth::user()->id)
        // ->delete();
        return view('backend.penjualan.create',compact('kode'));
    }

    //=================================================================
    public function carikode()
    {
        $datenow = date('mY');
        $carikode = DB::table('penjualan')->where('kode','like','%'.$datenow.'%')->max('kode');
        if(!$carikode){
            $finalkode = 'PNJ-'.$datenow.'-0001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%04s",$newkode[2]+1);
            $finalkode = 'PNJ-'.$datenow.'-'.$nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function adddetailpenjualan(Request $request)
    {
        $caribarangdetail = DB::table('penjualan_thumb_detail')
        ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
        ->get();
        if(count($caribarangdetail)>0){
            foreach ($caribarangdetail as $row) {
                $harga = str_replace('.','',$request->harga_barang);
                $jumlahdiskon = $harga*$request->diskon/100;
                $jumlah=$row->jumlah + $request->jumlah_barang;
                $total = $jumlah*($harga-$jumlahdiskon);
                if($jumlah>$request->stok){
                    $status = false;
                }else{
                    DB::table('penjualan_thumb_detail')
                    ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
                    ->update([
                        'harga'=>$harga,
                        'jumlah'=>$jumlah,
                        'total'=>$total,
                    ]);
                    $status = true;
                }
            }
        }else{
            DB::table('penjualan_thumb_detail')
            ->insert([
                'kode_penjualan'=>$request->kode,
                'kode_barang'=>$request->kode_barang,
                'jumlah'=>$request->jumlah_barang,
                'diskon'=>$request->diskon,
                'harga'=>str_replace('.','',$request->harga_barang),
                'total'=>str_replace('.','',$request->total_harga_barang),
                'pembuat'=>Auth::user()->id,
            ]);
            $status = true;
        }
        return response()->json($status);
    }

    //=================================================================
    public function listdetailpenjualan($kode)
    {
        $data = DB::table('penjualan_thumb_detail')
        ->select(DB::raw('penjualan_thumb_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','penjualan_thumb_detail.kode_barang')
        ->where([['penjualan_thumb_detail.pembuat',Auth::user()->id],['penjualan_thumb_detail.kode_penjualan',$kode]])
        ->get();
        return response()->json($data);
    }

    public function detailpenjualan($kode)
    {
        $data = DB::table('penjualan_thumb_detail')
        ->select(DB::raw('penjualan_thumb_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','penjualan_thumb_detail.kode_barang')
        ->where([['penjualan_thumb_detail.pembuat',Auth::user()->id],['penjualan_thumb_detail.kode_penjualan',$kode]])
        ->get();
        return response()->json($data);
    }

    //=================================================================
    public function store(Request $request)
    {
        //
    }

    //=================================================================
    public function gantiharga($kode,$status)
    {
        $caribarangdetail = DB::table('penjualan_thumb_detail')
        ->where([['kode_penjualan',$kode],['pembuat',Auth::user()->id]])
        ->get();

        if(count($caribarangdetail)>0){
            foreach ($caribarangdetail as $row) {
                $harga=0;
                $diskon=0;
                $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
                foreach($caribarang as $row_caribarang){
                    if($status=='umum'){
                        $harga = $row_caribarang->harga_jual;
                        $diskon=$row_caribarang->diskon;
                    }else{
                        $harga = $row_caribarang->harga_jual_customer;
                        $diskon=$row_caribarang->diskon_customer;
                    }
                }
                                
                $jumlah = $row->jumlah;
                $jumlahdiskon = $harga*$diskon/100;
                $total = $jumlah*($harga-$jumlahdiskon);

                DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan',$kode],['kode_barang',$row->kode_barang]])
                ->update([
                    'diskon'=>$diskon,
                    'harga'=>$harga,
                    'jumlah'=>$jumlah,
                    'total'=>$total,
                ]);
            }
        }
    }

    //=================================================================
    public function hapusdetailpenjualan(Request $request)
    {
        $data = DB::table('penjualan_thumb_detail')
        ->where('id',$request->kode)
        ->delete();
    }

    //=================================================================
    public function adddetailpenjualanqr(Request $request)
    {
        $caribarang = DB::table('barang')->where('kode',$request->kode_barang)->get();

        if(count($caribarang)>0){
            foreach ($caribarang as $row_caribarang) {
                $stok=$row_caribarang->stok;
                if($request->status=='umum'){
                    $harga = $row_caribarang->harga_jual;
                    $diskon = $row_caribarang->diskon;
                }else{
                    $harga = $row_caribarang->harga_jual_customer;
                    $diskon = $row_caribarang->diskon_customer;
                }
            }

            $caribarangdetail = DB::table('penjualan_thumb_detail')
            ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
            ->get();

            if(count($caribarangdetail)>0){
                foreach ($caribarangdetail as $row) {
                    $jumlahdiskon = $harga*$diskon/100;
                    $jumlah=$row->jumlah + 1;
                    $total = $jumlah*($harga-$jumlahdiskon);
                    if($jumlah>$stok){
                        $status = false;
                    }else{
                        DB::table('penjualan_thumb_detail')
                        ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
                        ->update([
                            'diskon'=>$diskon,
                            'harga'=>$harga,
                            'jumlah'=>$jumlah,
                            'total'=>$total,
                        ]);
                        $status = true;
                    }
                }
                dd('ada');
            }else{
                $jumlahdiskon = $harga*$diskon/100;
                $jumlah=1;
                $total = $jumlah*($harga-$jumlahdiskon);
                DB::table('penjualan_thumb_detail')
                ->insert([
                    'kode_penjualan'=>$request->kode,
                    'kode_barang'=>$request->kode_barang,
                    'jumlah'=>$jumlah,
                    'diskon'=>$diskon,
                    'harga'=>$harga,
                    'total'=>$total,
                    'pembuat'=>Auth::user()->id,
                ]);
                $status = true;
                dd('tidak');
            }
        }
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
        //
    }
}