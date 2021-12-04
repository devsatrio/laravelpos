<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class laporanController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    //=================================================================
    public function laporanpenjualan(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }

        if ($request->has('customer') ||$request->has('pembuat') ||$request->has('status')) {
            if($request->customer!='Semua'){
                if($request->pembuat!='Semua'){
                    if($request->status!='Semua'){
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->where('penjualan.status','=',$request->status)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }
                }else{
                    if($request->status!='Semua'){
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan.status','=',$request->status)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }
                }
            }else{
                if($request->pembuat!='Semua'){
                    if($request->status!='Semua'){
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->where('penjualan.status','=',$request->status)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }
                }else{
                    if($request->status!='Semua'){
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.status','=',$request->status)
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan')
                        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->orderby('penjualan.id','desc')
                        ->get();
                    }
                }
            }
        }else{
            $data = DB::table('penjualan')
            ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
            ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
            ->leftjoin('users','users.id','=','penjualan.pembuat')
            ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
            ->orderby('penjualan.id','desc')
            ->get();
        }

        $datacustomer = DB::table('master_customer')->orderby('id','desc')->get();
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        
        return view('backend.laporan.laporanpenjualan',compact('datacustomer','dataadmin','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
