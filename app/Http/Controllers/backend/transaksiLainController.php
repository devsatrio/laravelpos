<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

class transaksiLainController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-transaksi-lain', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-transaksi-lain', ['only' => ['create']]);
        $this->middleware('permission:edit-transaksi-lain', ['only' => ['edit']]);
        $this->middleware('permission:delete-transaksi-lain', ['only' => ['destroy']]);
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

        $data=DB::table('transaksi_lain')
        ->select(DB::raw('transaksi_lain.*,users.name'))
        ->leftjoin('users','users.id','=','transaksi_lain.created_by');


        if($request->has('status')){
            if($request->status!='Semua Status'){
                $data=$data->where('transaksi_lain.status',$request->status);
            }
        }

        if($request->has('pembuat')){
            if($request->pembuat!='Semua Pembuat'){
                $data=$data->where('transaksi_lain.created_by',$request->pembuat);
            }
        }

        $data=$data->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
        ->orderby('transaksi_lain.id','desc')
        ->paginate(60);

        $user = DB::table('users')->get();
        return view('backend.transaksilain.index',compact('data','user'));
    }

    //=================================================================
    public function create()
    {
        return view('backend.transaksilain.create');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(
            DB::table('transaksi_lain')
            ->select(DB::raw('transaksi_lain.*,users.name'))
            ->leftjoin('users','users.id','=','transaksi_lain.created_by')
            ->orderby('transaksi_lain.id','desc')
            ->get()
            )->make(true);
    }

    //=================================================================
    public function store(Request $request)
    {
        DB::table('transaksi_lain')
        ->insert([
            'status'=>$request->status,
            'jumlah'=>str_replace('.','',$request->jumlah),
            'status'=>$request->status,
            'tgl_buat'=>$request->tgl_buat,
            'keterangan'=>$request->keterangan,
            'created_by'=>Auth::user()->id,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect('/backend/transaksi-lain')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function show($id)
    {
        //
    }

    //=================================================================
    public function edit($id)
    {
        $data = DB::table('transaksi_lain')->where('id',$id)->get();
        return view('backend.transaksilain.edit',compact('data'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('transaksi_lain')
        ->where('id',$id)
        ->update([
            'status'=>$request->status,
            'jumlah'=>str_replace('.','',$request->jumlah),
            'status'=>$request->status,
            'keterangan'=>$request->keterangan,
            'tgl_buat'=>$request->tgl_buat,
            'created_by'=>Auth::user()->id,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect('/backend/transaksi-lain')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        DB::table('transaksi_lain')->where('id',$id)->delete();
    }
}
