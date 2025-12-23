<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

class perbaikanStokController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-perbaikan-stok', ['only' => ['index','show']]);
        $this->middleware('permission:create-perbaikan-stok', ['only' => ['create']]);
        $this->middleware('permission:edit-perbaikan-stok', ['only' => ['edit']]);
        $this->middleware('permission:delete-perbaikan-stok', ['only' => ['destroy']]);
        $this->middleware('permission:approve-perbaikan-stok', ['only' => ['updatestatus']]);
    }

    //=================================================================
    public function index(Request $request)
    {
        if($request->has('tgl_buat')){
            $tanggal = explode(' - ',$request->tgl_buat);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }
        $data = DB::table('perbaikan_stok')
        ->select(DB::raw('perbaikan_stok.*,users.name'))
        ->leftjoin('users','users.id','=','perbaikan_stok.pembuat');


        if($request->has('status')){
            if($request->status!='Semua Status'){
                $data=$data->where('perbaikan_stok.status',$request->status);
            }
        }

        if($request->has('pembuat')){
            if($request->pembuat!='Semua Pembuat'){
                $data=$data->where('perbaikan_stok.pembuat',$request->pembuat);
            }
        }

        if($request->has('kode')){
            if($request->kode!=null){
                $data=$data->where('perbaikan_stok.kode','like','%'.$request->kode.'%');
            }
        }

        $data=$data->whereBetween('perbaikan_stok.tgl_buat',[$tglsatu,$tgldua])
        ->orderby('perbaikan_stok.id','desc')
        ->paginate(60);

        $user = DB::table('users')->get();
        return view('backend.perbaikanstok.index',compact('data','user'));
    }

    //=================================================================
    public function create()
    {
        $kode = $this->carikode();
        DB::table('detail_perbaikan_stok_thumb')
        ->where('detail_perbaikan_stok_thumb.pembuat',Auth::user()->id)
        ->delete();
        return view('backend.perbaikanstok.create',compact('kode'));
    }

    //=================================================================
    public function carikode()
    {
        $carikode = DB::table('perbaikan_stok')->max('kode');
        if(!$carikode){
            $finalkode = 'PBS-0001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%04s",$newkode[1]+1);
            $finalkode = 'PBS-'.$nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function adddetail(Request $request)
    {
        $caridata = DB::table('detail_perbaikan_stok_thumb')->where([['kode_perbaikan_stok',$request->kode],['kode_barang',$request->kode_barang]])->get();
        if(count($caridata)>0){
            DB::table('detail_perbaikan_stok_thumb')
            ->where([['kode_perbaikan_stok',$request->kode],['kode_barang',$request->kode_barang]])
            ->update([
                'stok_lama'=>$request->stok,
                'stok_baru'=>$request->stok_baru,
                'keterangan'=>$request->keterangan_barang,
            ]);
        }else{
            DB::table('detail_perbaikan_stok_thumb')
            ->insert([
                'kode_perbaikan_stok'=>$request->kode,
                'kode_barang'=>$request->kode_barang,
                'stok_lama'=>$request->stok,
                'stok_baru'=>$request->stok_baru,
                'keterangan'=>$request->keterangan_barang,
                'pembuat'=>Auth::user()->id,
            ]);
        }
        
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(
        DB::table('perbaikan_stok')
        ->select(DB::raw('perbaikan_stok.*,users.name'))
        ->leftjoin('users','users.id','=','perbaikan_stok.pembuat')
        ->orderby('perbaikan_stok.id','desc')
        ->get()
        )->make(true);
    }

    //=================================================================
    public function listdetail($kode)
    {
        $data = DB::table('detail_perbaikan_stok_thumb')
        ->select(DB::raw('detail_perbaikan_stok_thumb.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','detail_perbaikan_stok_thumb.kode_barang')
        ->where([['detail_perbaikan_stok_thumb.kode_perbaikan_stok',$kode],['detail_perbaikan_stok_thumb.pembuat',Auth::user()->id]])
        ->get();
        return response()->json($data);
    }

    //=================================================================
    public function hapusdetail(Request $request)
    {
        DB::table('detail_perbaikan_stok_thumb')->where('id',$request->kode)->delete();
    }

    //=================================================================
    public function store(Request $request)
    {
        $detail = DB::table('detail_perbaikan_stok_thumb')
        ->where('kode_perbaikan_stok',$request->kode)
        ->get();
        $data=[];
        foreach ($detail as $row) {
            $data[] = [
                'kode_perbaikan_stok'=>$row->kode_perbaikan_stok,
                'kode_barang'=>$row->kode_barang,
                'stok_lama'=>$row->stok_lama,
                'stok_baru'=>$row->stok_baru,
                'keterangan'=>$row->keterangan,
            ]; 
        }

        DB::table('detail_perbaikan_stok')->insert($data);
        DB::table('perbaikan_stok')
        ->insert([
            'kode'=>$request->kode,
            'pembuat'=>Auth::user()->id,
            'tgl_buat'=>$request->tgl_buat,
            'keterangan'=>$request->keterangan,
        ]);

        DB::table('detail_perbaikan_stok_thumb')->where('pembuat',Auth::user()->id)->delete();
    }

    //=================================================================
    public function show($kode)
    {
        $data = DB::table('perbaikan_stok')
        ->select(DB::raw('perbaikan_stok.*,users.name'))
        ->leftjoin('users','users.id','=','perbaikan_stok.pembuat')
        ->where('perbaikan_stok.kode',$kode)
        ->get();
        $data_detail = DB::table('detail_perbaikan_stok')
        ->select(DB::raw('detail_perbaikan_stok.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','detail_perbaikan_stok.kode_barang')
        ->where('detail_perbaikan_stok.kode_perbaikan_stok',$kode)
        ->get();
        return view('backend.perbaikanstok.show',compact('data','data_detail'));
    }

    //=================================================================
    public function updatestatus(Request $request,$kode)
    {
        $detail = DB::table('detail_perbaikan_stok')
        ->where('kode_perbaikan_stok',$request->kode)
        ->get();
        $data=[];
        foreach ($detail as $row) {
            $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
            foreach($caribarang as $row_caribarang){
                $newstok = $row->stok_baru;
                DB::table('barang')
                ->where('kode',$row->kode_barang)
                ->update([
                    'stok'=>$newstok
                ]);
            } 
        }

        DB::table('perbaikan_stok')
        ->where('kode',$kode)
        ->update([
            'status'=>'Approve',
        ]);
    }

    //=================================================================
    public function edit($id)
    {
        $kode='';
        $status="Draft";
        $data_perbaikan = DB::table('perbaikan_stok')->where('id',$id)->get();
        foreach ($data_perbaikan as $row_data_perbaikan) {
            $kode=$row_data_perbaikan->kode;
            $status=$row_data_perbaikan->status;
        }
        DB::table('detail_perbaikan_stok_thumb')->where('pembuat',Auth::user()->id)->delete();
        
        

        if($status=='Draft'){
            $detail = DB::table('detail_perbaikan_stok')
            ->where('kode_perbaikan_stok',$kode)
            ->get();
            $data=[];
            foreach ($detail as $row) {
                $data[] = [
                    'kode_perbaikan_stok'=>$row->kode_perbaikan_stok,
                    'kode_barang'=>$row->kode_barang,
                    'stok_lama'=>$row->stok_lama,
                    'stok_baru'=>$row->stok_baru,
                    'keterangan'=>$row->keterangan,
                    'pembuat'=>Auth::user()->id,
                ]; 
            }

            DB::table('detail_perbaikan_stok_thumb')->insert($data);
            return view('backend.perbaikanstok.edit',compact('data_perbaikan'));
        }else{
            
            $detail = DB::table('detail_perbaikan_stok')
            ->select(DB::raw('detail_perbaikan_stok.*,barang.nama as namabarang'))
            ->leftjoin('barang','barang.kode','=','detail_perbaikan_stok.kode_barang')
            ->where('detail_perbaikan_stok.kode_perbaikan_stok',$kode)
            ->get();
            return view('backend.perbaikanstok.edit_dua',compact('data_perbaikan','detail'));
        }
    }

    //=================================================================
    public function update(Request $request, $kode)
    {
        $status="Draft";
        $data_perbaikan = DB::table('perbaikan_stok')->where('kode',$kode)->get();
        foreach ($data_perbaikan as $row_data_perbaikan) {
            $status=$row_data_perbaikan->status;
        }

        if($status=='Draft'){

            DB::table('detail_perbaikan_stok')->where('kode_perbaikan_stok',$kode)->delete();

            $detail = DB::table('detail_perbaikan_stok_thumb')
            ->where('kode_perbaikan_stok',$request->kode)
            ->get();
            $data=[];
            foreach ($detail as $row) {
                $data[] = [
                    'kode_perbaikan_stok'=>$row->kode_perbaikan_stok,
                    'kode_barang'=>$row->kode_barang,
                    'stok_lama'=>$row->stok_lama,
                    'stok_baru'=>$row->stok_baru,
                    'keterangan'=>$row->keterangan,
                ]; 
            }

            DB::table('detail_perbaikan_stok')->insert($data);
            DB::table('perbaikan_stok')
            ->where('kode',$kode)
            ->update([
                'pembuat'=>Auth::user()->id,
                'tgl_buat'=>$request->tgl_buat,
                'keterangan'=>$request->keterangan,
            ]);
        }else{
            DB::table('perbaikan_stok')
            ->where('kode',$kode)
            ->update([
                'pembuat'=>Auth::user()->id,
                'tgl_buat'=>$request->tgl_buat,
                'keterangan'=>$request->keterangan,
            ]);
        }
        
    }

    //=================================================================
    public function destroy($id)
    {
        $kode='';
        $data = DB::table('perbaikan_stok')->where('id',$id)->get();
        foreach ($data as $row) {
            $kode=$row->kode;
        }

        $detail = DB::table('detail_perbaikan_stok')
        ->where('kode_perbaikan_stok',$kode)
        ->delete();
        DB::table('perbaikan_stok')->where('id',$id)->delete();
    }
}