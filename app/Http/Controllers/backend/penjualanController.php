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
                $jumlah=$row->jumlah + $request->jumlah_barang;
                $total = $row->total + str_replace('.','',$request->total_harga_barang);

                DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
                ->update([
                    'jumlah'=>$jumlah,
                    'total'=>$total,
                ]);
            }
        }else{
            DB::table('penjualan_thumb_detail')
            ->insert([
                'kode_penjualan'=>$request->kode,
                'kode_barang'=>$request->kode_barang,
                'jumlah'=>$request->jumlah_barang,
                'harga'=>str_replace('.','',$request->harga_barang),
                'harga_grosir'=>str_replace('.','',$request->harga_barang),
                'total'=>str_replace('.','',$request->total_harga_barang),
                'pembuat'=>Auth::user()->id,
            ]);
        }
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

    //=================================================================
    public function store(Request $request)
    {
        //
    }

    //=================================================================
    public function gantiharga($kode,$status)
    {
        $caribarangdetail = DB::table('penjualan_thumb_detail')
        ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
        ->get();

        if(count($caribarangdetail)>0){
            foreach ($caribarangdetail as $row) {
                $harga=0;
                $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
                foreach($caribarang as $row_caribarang){
                    if($status=='umum'){
                        $harga = $row_caribarang->harga_jual;
                    }else{
                        $harga = $row_caribarang->harga_jual_customer;
                    }
                }
                                
                $jumlah=$row->jumlah;
                $total = $row->total + str_replace('.','',$request->total_harga_barang);

                DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan',$request->kode],['kode_barang',$request->kode_barang]])
                ->update([
                    'jumlah'=>$jumlah,
                    'total'=>$total,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}