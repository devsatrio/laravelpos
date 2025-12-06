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
        $this->middleware('permission:view-pembelian', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-pembelian', ['only' => ['create']]);
        $this->middleware('permission:edit-pembelian', ['only' => ['edit']]);
        $this->middleware('permission:delete-pembelian', ['only' => ['destroy']]);
        $this->middleware('permission:approve-pembelian', ['only' => ['updatestatuspembelian']]);
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
    public function adddetailpembelianqr(Request $request)
    {
        $statusbarang=true;
        $harga_barang=0;
        $caribarang = DB::table('barang')->where('kode_qr',$request->kode_barang)->get();
        if(count($caribarang)>0){
            foreach ($caribarang as $row_caribarang) {
                $kode_brg=$row_caribarang->kode;
                $harga_barang = $row_caribarang->harga_beli;
            }

            $caribarangdetail = DB::table('pembelian_thumb_detail')
            ->where([['kode_pembelian',$request->kode],['kode_barang',$kode_brg]])
            ->get();
            
            if(count($caribarangdetail)>0){
                foreach ($caribarangdetail as $row) {
                    $jumlah = $row->jumlah + 1;
                    $total = $row->total + $harga_barang;

                    DB::table('pembelian_thumb_detail')
                    ->where([['kode_pembelian',$request->kode],['kode_barang',$kode_brg]])
                    ->update([
                        'jumlah'=>$jumlah,
                        'total'=>$total,
                    ]);
                }
            }else{
                DB::table('pembelian_thumb_detail')
                ->insert([
                    'kode_pembelian'=>$request->kode,
                    'kode_barang'=>$kode_brg,
                    'jumlah'=>1,
                    'harga'=>$harga_barang,
                    'total'=>$harga_barang,
                    'pembuat'=>Auth::user()->id,
                ]);
            }
        }else{
            $statusbarang=false;
        }
        return response()->json($statusbarang);
    }

    //=================================================================
    public function hapusdetailpembelian(Request $request)
    {
        $data = DB::table('pembelian_thumb_detail')
        ->where('id',$request->kode)
        ->delete();
    }

    //=================================================================
    public function updatestatuspembelian(Request $request,$id)
    {
        DB::table('pembelian')
        ->where('id',$id)
        ->update([
            'status_pembelian'=>'Approve',
        ]);

        $kode_pembelian ="";
        $caridatapembelian = DB::table('pembelian')->where('id',$id)->get();
        foreach($caridatapembelian as $row){
            $kode_pembelian=$row->kode;
        }

        $data_detail_pembelian = DB::table('pembelian_detail')->where('kode_pembelian',$kode_pembelian)->get();
        $data=[];
        foreach ($data_detail_pembelian as $row_data_detail_pembelian) {
            $caribarang = DB::table('barang')->where('kode',$row_data_detail_pembelian->kode_barang)->get();
            foreach($caribarang as $row_caribarang){
                $stok = $row_caribarang->stok+$row_data_detail_pembelian->jumlah;
                DB::table('barang')
                ->where('kode',$row_data_detail_pembelian->kode_barang)
                ->update([
                    'stok'=>$stok
                ]);
            }
        }
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

        $data = DB::table('pembelian')
        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
        ->leftjoin('users','users.id','=','pembelian.pembuat');

        if($request->has('status')){
            if($request->status!='Semua Status'){
                $data=$data->where('pembelian.status',$request->status);
            }
        }

        if($request->has('kode')){
            if($request->kode!=null){
                $data=$data->where('pembelian.kode','like','%'.$request->kode.'%');
            }
        }
        $data = $data->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua]);
        $data=$data->orderby('pembelian.id','desc')
        ->paginate(60);

        return view('backend.pembelian.index',compact('data'));
    }

    //=================================================================
    public function create()
    {
        $kode = $this->carikode();
        DB::table('pembelian_thumb_detail')
        ->where('pembuat',Auth::user()->id)
        ->delete();
        $supplier = DB::table('master_supplier')->orderby('id','desc')->get();
        $web_set = DB::table('settings')->orderby('id','desc')->get();
        return view('backend.pembelian.create',compact('kode','supplier','web_set'));
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
        if(intval(str_replace('.','',$request->kekurangan)) > 0){
            $status = "Belum Lunas";
        }else{
            $status = "Telah Lunas";
        }
        
        $total = str_replace('.','',$request->subtotal)+str_replace('.','',$request->biaya_tambahan)-str_replace('.','',$request->potongan);
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
            'status'=>$status,
            'status_pembelian'=>'Draft',
            'created_at'=>date('Y-m-d H:i:s'),
            'created_by'=>Auth::user()->id,
        ]);

        DB::table('pembelian_thumb_detail')->where('pembuat',Auth::user()->id)->delete();
    }

    //=================================================================
    public function show($kode_pembelian)
    {
        $data_pembelian = DB::table('pembelian')
        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier'))
        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
        ->where('pembelian.kode',$kode_pembelian)
        ->get();

        $data_detail_pembelian = DB::table('pembelian_detail')
        ->select(DB::raw('pembelian_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
        ->where('pembelian_detail.kode_pembelian',$kode_pembelian)
        ->get();
        return view('backend.pembelian.show',compact('data_detail_pembelian','data_pembelian'));
    }

    //=================================================================
    public function edit($kode_pembelian)
    {
        $status="";
        DB::table('pembelian_thumb_detail')->where([['kode_pembelian',$kode_pembelian],['pembuat',Auth::user()->id]])->delete();

        $data_pembelian = DB::table('pembelian')->where('kode',$kode_pembelian)->get();
        $data_detail_pembelian = DB::table('pembelian_detail')->where('kode_pembelian',$kode_pembelian)->get();
        $data=[];
        foreach ($data_detail_pembelian as $row_data_detail_pembelian) {
            $data[] = [
                'kode_pembelian'=>$row_data_detail_pembelian->kode_pembelian,
                'kode_barang'=>$row_data_detail_pembelian->kode_barang,
                'jumlah'=>$row_data_detail_pembelian->jumlah,
                'harga'=>$row_data_detail_pembelian->harga,
                'total'=>$row_data_detail_pembelian->total,
                'pembuat'=>Auth::user()->id
            ]; 
        }

        DB::table('pembelian_thumb_detail')->insert($data);
        $supplier = DB::table('master_supplier')->orderby('id','desc')->get();
        $web_set = DB::table('settings')->orderby('id','desc')->get();

        
        $kode = $kode_pembelian;
        foreach ($data_pembelian as $row_data_pembelian) {
            $status=$row_data_pembelian->status_pembelian;
        }
        if($status=='Approve'){
            return view('backend.pembelian.edit_appr',compact('kode','supplier','data_pembelian'));
        }else{
            return view('backend.pembelian.edit',compact('kode','supplier','data_pembelian','web_set'));
        }

    }

    //=================================================================
    public function detailpembelian($id)
    {
        $data = DB::table('pembelian_thumb_detail')
        ->select(DB::raw('pembelian_thumb_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','pembelian_thumb_detail.kode_barang')
        ->where('pembelian_thumb_detail.id',$id)
        ->get();
        return response()->json($data);
    }

    //=================================================================
    public function editdetailpembelian(Request $request)
    {
        $harga_barang = str_replace('.','',$request->edit_harga_barang);
        $jumlah = str_replace('.','',$request->edit_jumlah_barang);
        $total = $harga_barang*$jumlah;
        DB::table('pembelian_thumb_detail')
        ->where('id',$request->edit_id)
        ->update([
            'jumlah'=>$request->edit_jumlah_barang,
            'harga'=>$harga_barang,
            'total'=>$total,
        ]);
    }

    //=================================================================
    public function update(Request $request, $kode_pembelian)
    {
        DB::table('pembelian_detail')->where('kode_pembelian',$kode_pembelian)->delete();

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
        if(intval(str_replace('.','',$request->kekurangan)) > 0){
            $status = "Belum Lunas";
        }else{
            $status = "Telah Lunas";
        }
        
        $total = str_replace('.','',$request->subtotal)+str_replace('.','',$request->biaya_tambahan)-str_replace('.','',$request->potongan);
        DB::table('pembelian')
        ->where('kode',$request->kode)
        ->update([
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
            'status'=>$status,
            'updated_at'=>date('Y-m-d H:i:s'),
            'updated_by'=>Auth::user()->id,
        ]);

        DB::table('pembelian_thumb_detail')->where('pembuat',Auth::user()->id)->delete();
    }

    //=================================================================
    public function destroy($id)
    {
        $kode='';
        $stts='';
        $data = DB::table('pembelian')->where('id',$id)->get();
        foreach ($data as $row) {
            $kode=$row->kode;
            $stts=$row->status_pembelian;
        }

        //apabila status pembelian sudah approve maka stok barang dikurangi
        if($stts=='Approve'){
            $detail = DB::table('pembelian_detail')
            ->where('kode_pembelian',$kode)
            ->get();
            foreach ($detail as $row) {
                $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
                foreach($caribarang as $row_caribarang){
                    $newstok = $row_caribarang->stok - $row->jumlah;
                    DB::table('barang')
                    ->where('kode',$row->kode_barang)
                    ->update([
                        'stok'=>$newstok
                    ]);
                }
            }
        }

        DB::table('pembelian_detail')->where('kode_pembelian',$kode)->delete();
        DB::table('pembelian')->where('id',$id)->delete();
    }
}