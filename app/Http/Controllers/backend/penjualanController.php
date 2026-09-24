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
        $this->middleware('permission:view-penjualan', ['only' => ['index','show']]);
        $this->middleware('permission:create-penjualan', ['only' => ['create']]);
        $this->middleware('permission:update-hutang-penjualan', ['only' => ['updatehutang']]);
        $this->middleware('permission:delete-penjualan', ['only' => ['destroy']]);
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(
            DB::table('penjualan')
            ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
            ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
            ->leftjoin('users','users.id','=','penjualan.pembuat')
            ->orderby('penjualan.id','desc')
            ->get()
        )->make(true);
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

        $data = DB::table('penjualan')
        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
        ->leftjoin('users','users.id','=','penjualan.pembuat');

        if($request->has('status')){
            if($request->status!='Semua Status'){
                $data=$data->where('penjualan.status',$request->status);
            }
        }

        if($request->has('jenis_bayar')){
            if($request->jenis_bayar!='Semua Jenis Bayar'){
                $data=$data->where('penjualan.jenis_bayar',$request->jenis_bayar);
            }
        }

        if($request->has('kode')){
            if($request->kode!=null){
                $data=$data->where('penjualan.kode','like','%'.$request->kode.'%');
            }
        }
        
        if($request->has('pembuat')){
            if($request->pembuat!='Semua Pembuat'){
                $data=$data->where('penjualan.pembuat',$request->pembuat);
            }
        }
        
        if($request->has('customer')){
            if($request->customer!='Semua Customer'){
                $data=$data->where('penjualan.customer',$request->customer);
            }
        }

        $data = $data->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua]);

        $data=$data->orderby('penjualan.id','desc')
        ->paginate(60);

        $user = DB::table('users')->get();
        $customer = DB::table('master_customer')->get();
        return view('backend.penjualan.index',compact('data','user','customer'));
    }

    //=================================================================
    public function create()
    {
        $kode = $this->carikode();
        DB::table('penjualan_thumb_detail')
        ->where('pembuat',Auth::user()->id)
        ->delete();
        $web_set = DB::table('settings')->orderby('id','desc')->get();
        return view('backend.penjualan.create',compact('kode','web_set'));
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
        $barang = DB::table('barang')->where('kode', $request->kode_barang)->first();
        if (!$barang) {
            return response()->json(false);
        }

        $jumlah_input = intval(str_replace('.', '', $request->jumlah_barang));
        if ($jumlah_input <= 0) {
            return response()->json(false);
        }

        $hitung_stok = $barang->hitung_stok;
        $stok = intval($barang->stok);

        $caribarangdetail = DB::table('penjualan_thumb_detail')
            ->where([['kode_penjualan', $request->kode], ['kode_barang', $request->kode_barang], ['pembuat', Auth::user()->id]])
            ->get();

        if (count($caribarangdetail) > 0) {
            foreach ($caribarangdetail as $row) {
                $jumlah = $row->jumlah + $jumlah_input;
                if ($hitung_stok == 'y' && $jumlah > $stok) {
                    return response()->json(false);
                }
                $harga = str_replace('.', '', $request->harga_barang);
                $jumlahdiskon = $harga * $request->diskon / 100;
                $total = $jumlah * ($harga - $jumlahdiskon);

                DB::table('penjualan_thumb_detail')
                    ->where([['kode_penjualan', $request->kode], ['kode_barang', $request->kode_barang], ['pembuat', Auth::user()->id]])
                    ->update([
                        'harga' => $harga,
                        'jumlah' => $jumlah,
                        'total' => $total,
                    ]);
            }
        } else {
            if ($hitung_stok == 'y' && $jumlah_input > $stok) {
                return response()->json(false);
            }
            $harga = str_replace('.', '', $request->harga_barang);
            $jumlahdiskon = $harga * $request->diskon / 100;
            $total = $jumlah_input * ($harga - $jumlahdiskon);

            DB::table('penjualan_thumb_detail')
                ->insert([
                    'kode_penjualan' => $request->kode,
                    'kode_barang' => $request->kode_barang,
                    'jumlah' => $jumlah_input,
                    'diskon' => $request->diskon,
                    'harga' => $harga,
                    'total' => $total,
                    'pembuat' => Auth::user()->id,
                ]);
        }
        return response()->json(true);
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
    public function detailpenjualan($kode)
    {
        $data = DB::table('penjualan_thumb_detail')
        ->select(DB::raw('penjualan_thumb_detail.*,barang.hitung_stok,barang.nama as namabarang,barang.stok'))
        ->leftjoin('barang','barang.kode','=','penjualan_thumb_detail.kode_barang')
        ->where('penjualan_thumb_detail.id',$kode)
        ->get();
        return response()->json($data);
    }

    //=================================================================
    public function editdetailpembelian(Request $request)
    {
        $detail = DB::table('penjualan_thumb_detail')->where('id', $request->edit_id)->first();
        if (!$detail) {
            return response()->json(false);
        }

        $barang = DB::table('barang')->where('kode', $detail->kode_barang)->first();
        $jumlah = intval(str_replace('.', '', $request->edit_jumlah_barang));
        if ($jumlah <= 0) {
            return response()->json(false);
        }

        if ($barang && $barang->hitung_stok == 'y' && $jumlah > intval($barang->stok)) {
            return response()->json(false);
        }

        $harga_barang = str_replace('.', '', $request->edit_harga_barang);
        $total = $harga_barang * $jumlah;
        DB::table('penjualan_thumb_detail')
            ->where('id', $request->edit_id)
            ->update([
                'jumlah' => $jumlah,
                'harga' => $harga_barang,
                'total' => $total,
            ]);
        return response()->json(true);
    }

    //=================================================================
    public function store(Request $request)
    {
        $detail = DB::table('penjualan_thumb_detail')
        ->where('kode_penjualan',$request->kode)
        ->get();
        $data=[];
        foreach ($detail as $row) {
            $data[] = [
                'kode_penjualan'=>$row->kode_penjualan,
                'kode_barang'=>$row->kode_barang,
                'jumlah'=>$row->jumlah,
                'diskon'=>$row->diskon,
                'harga'=>$row->harga,
                'total'=>$row->total,
            ]; 

            $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
            foreach($caribarang as $row_caribarang){
                if($row_caribarang->hitung_stok=='y'){
                    $newstok = $row_caribarang->stok - $row->jumlah;
                    DB::table('barang')
                    ->where('kode',$row->kode_barang)
                    ->update([
                        'stok'=>$newstok
                    ]);
                }
            }
        }

        DB::table('penjualan_detail')->insert($data);
        if(intval(str_replace('.','',$request->kekurangan)) > 0){
            $status = "Belum Lunas";
            DB::table('pembayaran')
            ->insert([
                'kode_penjualan'=>$request->kode,
                'jumlah'=>str_replace('.','',$request->dibayar),
                'tgl_bayar'=>date('Y-m-d'),
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>Auth::user()->id,
                'keterangan'=>'Pembayaran Pertama',
                'customer'=>$request->customer,
            ]);
        }else{
            $status = "Telah Lunas";
            DB::table('pembayaran')
            ->insert([
                'kode_penjualan'=>$request->kode,
                'jumlah'=>str_replace('.','',$request->dibayar),
                'tgl_bayar'=>date('Y-m-d'),
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>Auth::user()->id,
                'keterangan'=>'Pembayaran Pertama & Pelunasan',
                'customer'=>$request->customer,
            ]);
        }
        
        $total = str_replace('.','',$request->subtotal)+str_replace('.','',$request->biaya_tambahan)-str_replace('.','',$request->potongan);
        
        DB::table('penjualan')
        ->insert([
            'kode'=>$request->kode,
            'customer'=>$request->customer,
            'subtotal'=>str_replace('.','',$request->subtotal),
            'biaya_tambahan'=>str_replace('.','',$request->biaya_tambahan),
            'potongan'=>str_replace('.','',$request->potongan),
            'total'=>$total,
            'terbayar'=>str_replace('.','',$request->dibayar),
            'kekurangan'=>str_replace('.','',$request->kekurangan),
            'kembalian'=>str_replace('.','',$request->kembalian),
            'pembuat'=>Auth::user()->id,
            'tgl_buat'=>$request->tgl_order,
            'keterangan'=>$request->keterangan,
            'status'=>$status,
            'status_penjualan'=>'Draft',
            'created_at'=>date('Y-m-d H:i:s'),
            'created_by'=>Auth::user()->id,
            'jenis_bayar'=>$request->jenis_bayar ?? 'Cash',
        ]);

        DB::table('penjualan_thumb_detail')->where('pembuat',Auth::user()->id)->delete();
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
        $status = true;
        $statusbarang = true;
        $kode_barang = $request->kode_barang;
        $caribarang = DB::table('barang')
            ->whereExists(function ($query) use ($kode_barang) {
                $query->select(DB::raw(1))
                    ->from('barang_barcode')
                    ->whereColumn('barang_barcode.id_barang', 'barang.id')
                    ->where('barang_barcode.kode_barcode', $kode_barang);
            })
            ->orWhere('barang.kode', $kode_barang)
            ->first();

        if ($caribarang) {
            $stok = intval($caribarang->stok);
            $hitung_stok = $caribarang->hitung_stok;
            $kode_brg = $caribarang->kode;

            if ($request->status == 'umum') {
                $harga = $caribarang->harga_jual;
                $diskon = $caribarang->diskon;
            } else {
                $harga = $caribarang->harga_jual_customer;
                $diskon = $caribarang->diskon_customer;
            }

            $caribarangdetail = DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan', $request->kode], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                ->first();

            if ($caribarangdetail) {
                $jumlah = $caribarangdetail->jumlah + 1;
                if ($hitung_stok == 'y' && $jumlah > $stok) {
                    $status = false;
                } else {
                    $jumlahdiskon = $harga * $diskon / 100;
                    $total = $jumlah * ($harga - $jumlahdiskon);
                    DB::table('penjualan_thumb_detail')
                        ->where([['kode_penjualan', $request->kode], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                        ->update([
                            'diskon' => $diskon,
                            'harga' => $harga,
                            'jumlah' => $jumlah,
                            'total' => $total,
                        ]);
                    $status = true;
                }
            } else {
                $jumlah = 1;
                if ($hitung_stok == 'y' && $jumlah > $stok) {
                    $status = false;
                } else {
                    $jumlahdiskon = $harga * $diskon / 100;
                    $total = $jumlah * ($harga - $jumlahdiskon);
                    DB::table('penjualan_thumb_detail')
                        ->insert([
                            'kode_penjualan' => $request->kode,
                            'kode_barang' => $kode_brg,
                            'jumlah' => $jumlah,
                            'diskon' => $diskon,
                            'harga' => $harga,
                            'total' => $total,
                            'pembuat' => Auth::user()->id,
                        ]);
                    $status = true;
                }
            }
        } else {
            $statusbarang = false;
            $status = false;
        }
        $data = [
            'statusbarang' => $statusbarang,
            'status' => $status,
        ];
        return response()->json($data);
    }

    //=================================================================
    public function show($kode)
    {
        $item = DB::table('penjualan_detail')
        ->select(DB::raw('penjualan_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
        ->where('penjualan_detail.kode_penjualan',$kode)
        ->get();
        $detail = DB::table('penjualan')
        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,master_customer.kode as kodecustomer,users.name'))
        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
        ->leftjoin('users','users.id','=','penjualan.pembuat')
        ->where('penjualan.kode',$kode)
        ->get();

        $pembayaran = DB::table('pembayaran')
        ->select(DB::raw('pembayaran.*,users.name'))
        ->leftjoin('users','users.id','=','pembayaran.created_by')
        ->where('pembayaran.kode_penjualan',$kode)
        ->get();
        return view('backend.penjualan.show',compact('item','detail','pembayaran'));
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
    public function destroy($kode)
    {
        $detail = DB::table('penjualan_detail')
        ->where('kode_penjualan',$kode)
        ->get();
        foreach ($detail as $row) {
            $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->get();
            foreach($caribarang as $row_caribarang){
                if($row_caribarang->hitung_stok=='y'){
                    $newstok = $row_caribarang->stok + $row->jumlah;
                    DB::table('barang')
                    ->where('kode',$row->kode_barang)
                    ->update([
                        'stok'=>$newstok
                    ]);
                }
            }
        }

        DB::table('penjualan_detail')->where('kode_penjualan',$kode)->delete();
        DB::table('pembayaran')->where('kode_penjualan',$kode)->delete();
        DB::table('penjualan')->where('kode',$kode)->delete();
    }

    //=================================================================
    public function cetakulang($kode)
    {
        $item = DB::table('penjualan_detail')
        ->select(DB::raw('penjualan_detail.*,barang.nama as namabarang'))
        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
        ->where('penjualan_detail.kode_penjualan',$kode)
        ->get();
        $detail = DB::table('penjualan')
        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
        ->leftjoin('users','users.id','=','penjualan.pembuat')
        ->where('penjualan.kode',$kode)
        ->get();

        $print=[
            'item'=>$item,
            'detail'=>$detail,
        ];
        return response()->json($print);
    }

    //=================================================================
    // public function updatehutang(Request $request)
    // {
    //     $terbayar = str_replace('.','',$request->hutang)+str_replace('.','',$request->dibayar);
    //     $kekurangan = str_replace('.','',$request->kekurangan);
    //     if($kekurangan>0){
    //         $status = "Belum Lunas";
    //     }else{
    //         $status = "Telah Lunas";
    //     }
    //     DB::table('penjualan')
    //     ->where('kode',$request->kode)
    //     ->update([
    //         'status'=>$status,
    //         'terbayar'=>$terbayar,
    //         'kekurangan'=>$kekurangan
    //     ]);
    //     DB::table('pembayaran')
    //     ->insert([
    //         'kode_penjualan'=>$request->kode,
    //         'jumlah'=>str_replace('.','',$request->dibayar),
    //         'tgl_bayar'=>$request->tgl_bayar,
    //         'created_at'=>date('Y-m-d H:i:s'),
    //         'created_by'=>Auth::user()->id,
    //         'keterangan'=>'Pembayaran Hutang',
    //     ]);
    // }

    //=================================================================
    public function updatehutang(Request $request)
    {
        $datapenjualan = DB::table('penjualan')
        ->where('kode',$request->kode)
        ->get();
        foreach ($datapenjualan as $row_datapenjualan) {
            $telah_dibayar = $row_datapenjualan->terbayar;
            $terbayar = $telah_dibayar+str_replace('.','',$request->dibayar);
            $kekurangan = str_replace('.','',$request->kekurangan);
            if($kekurangan>0){
                $status = "Belum Lunas";
            }else{
                $status = "Telah Lunas";
            }
            DB::table('penjualan')
            ->where('kode',$request->kode)
            ->update([
                'status'=>$status,
                'terbayar'=>$terbayar,
                'kekurangan'=>$kekurangan
            ]);
            DB::table('pembayaran')
            ->insert([
                'kode_penjualan'=>$request->kode,
                'jumlah'=>str_replace('.','',$request->dibayar),
                'tgl_bayar'=>$request->tgl_bayar,
                'created_at'=>date('Y-m-d H:i:s'),
                'created_by'=>Auth::user()->id,
                'keterangan'=>'Pembayaran Hutang',
            ]);
        }
        
    }
}