<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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