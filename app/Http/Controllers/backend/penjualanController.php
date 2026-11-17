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
        $carikode = DB::table('penjualan')->where('kode', 'like', '%' . $datenow . '%')->max('kode');
        if (!$carikode) {
            $finalkode = 'PNJ-' . $datenow . '-0001';
        } else {
            $newkode = explode("-", $carikode);
            $nomerUrut = isset($newkode[2]) ? (intval($newkode[2]) + 1) : 1;
            $nomer = sprintf("%04s", $nomerUrut);
            $finalkode = 'PNJ-' . $datenow . '-' . $nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function adddetailpenjualan(Request $request)
    {
        $jumlah_input = intval(str_replace('.', '', $request->jumlah_barang));
        if ($jumlah_input <= 0) {
            return response()->json(false);
        }

        $barang = DB::table('barang')->where('kode', $request->kode_barang)->first();
        if (!$barang) {
            return response()->json(false);
        }

        $hitung_stok = $barang->hitung_stok;
        $stok = intval($barang->stok);
        $harga = intval(str_replace('.', '', $request->harga_barang));
        $diskon = floatval($request->diskon ?? 0);

        $kode_penjualan = trim($request->kode);
        $kode_barang = trim($request->kode_barang);

        $caribarangdetail = DB::table('penjualan_thumb_detail')
            ->where([['kode_penjualan', $kode_penjualan], ['kode_barang', $kode_barang], ['pembuat', Auth::user()->id]])
            ->get();

        if ($caribarangdetail->count() > 0) {
            $total_jumlah = $caribarangdetail->sum('jumlah') + $jumlah_input;
            if ($hitung_stok == 'y' && $total_jumlah > $stok) {
                return response()->json(false);
            }
            $jumlahdiskon = round($harga * $diskon / 100);
            $total = intval($total_jumlah * ($harga - $jumlahdiskon));

            $first_id = $caribarangdetail->first()->id;
            DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan', $kode_penjualan], ['kode_barang', $kode_barang], ['pembuat', Auth::user()->id]])
                ->where('id', '!=', $first_id)
                ->delete();

            DB::table('penjualan_thumb_detail')
                ->where('id', $first_id)
                ->update([
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'jumlah' => $total_jumlah,
                    'total' => $total,
                ]);
        } else {
            if ($hitung_stok == 'y' && $jumlah_input > $stok) {
                return response()->json(false);
            }
            $jumlahdiskon = round($harga * $diskon / 100);
            $total = intval($jumlah_input * ($harga - $jumlahdiskon));

            DB::table('penjualan_thumb_detail')
                ->insert([
                    'kode_penjualan' => $kode_penjualan,
                    'kode_barang' => $kode_barang,
                    'jumlah' => $jumlah_input,
                    'diskon' => $diskon,
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
        $detail = DB::table('penjualan_thumb_detail')
            ->where('id', $request->edit_id)
            ->where('pembuat', Auth::user()->id)
            ->first();

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

        $harga_barang = intval(str_replace('.', '', $request->edit_harga_barang));
        $diskon = floatval($detail->diskon ?? 0);
        $jumlahdiskon = round($harga_barang * $diskon / 100);
        $total = intval($jumlah * ($harga_barang - $jumlahdiskon));

        DB::table('penjualan_thumb_detail')
            ->where('id', $request->edit_id)
            ->where('pembuat', Auth::user()->id)
            ->update([
                'jumlah' => $jumlah,
                'harga' => $harga_barang,
                'diskon' => $diskon,
                'total' => $total,
            ]);
        return response()->json(true);
    }

    //=================================================================
    public function store(Request $request)
    {
        $kode = trim($request->kode);
        if (empty($kode)) {
            return response()->json(['status' => false, 'message' => 'Kode penjualan tidak valid'], 400);
        }

        // Cek apakah transaksi dengan kode ini sudah pernah tersimpan (mencegah dobel transaksi)
        if (DB::table('penjualan')->where('kode', $kode)->exists()) {
            return response()->json(['status' => false, 'message' => 'Transaksi sudah tersimpan sebelumnya']);
        }

        $detail = DB::table('penjualan_thumb_detail')
            ->where([['kode_penjualan', $kode], ['pembuat', Auth::user()->id]])
            ->get();

        if ($detail->count() === 0) {
            return response()->json(['status' => false, 'message' => 'Tidak ada barang yang ditransaksikan'], 400);
        }

        DB::transaction(function () use ($request, $kode, $detail) {
            $data = [];
            foreach ($detail as $row) {
                $data[] = [
                    'kode_penjualan' => $kode,
                    'kode_barang' => $row->kode_barang,
                    'jumlah' => $row->jumlah,
                    'diskon' => $row->diskon,
                    'harga' => $row->harga,
                    'total' => $row->total,
                ];

                $caribarang = DB::table('barang')->where('kode', $row->kode_barang)->first();
                if ($caribarang && $caribarang->hitung_stok == 'y') {
                    DB::table('barang')
                        ->where('kode', $row->kode_barang)
                        ->decrement('stok', intval($row->jumlah));
                }
            }

            DB::table('penjualan_detail')->insert($data);

            // Perhitungan keuangan di sisi server dari item keranjang yang tersimpan
            $subtotal = intval($detail->sum('total'));
            $biaya_tambahan = max(0, intval(str_replace('.', '', $request->biaya_tambahan)));
            $potongan = max(0, intval(str_replace('.', '', $request->potongan)));
            $total = max(0, $subtotal + $biaya_tambahan - $potongan);
            $dibayar = max(0, intval(str_replace('.', '', $request->dibayar)));

            if ($dibayar >= $total) {
                $terbayar = $total;
                $kembalian = $dibayar - $total;
                $kekurangan = 0;
                $status = "Telah Lunas";
                $ket_bayar = "Pembayaran Pertama & Pelunasan";
            } else {
                $terbayar = $dibayar;
                $kekurangan = $total - $dibayar;
                $kembalian = 0;
                $status = "Belum Lunas";
                $ket_bayar = "Pembayaran Pertama";
            }

            $tgl_order = !empty($request->tgl_order) ? $request->tgl_order : date('Y-m-d');
            $jenis_bayar = in_array($request->jenis_bayar, ['Cash', 'QRIS', 'Transfer Bank']) ? $request->jenis_bayar : 'Cash';

            DB::table('pembayaran')
                ->insert([
                    'kode_penjualan' => $kode,
                    'jumlah' => $terbayar,
                    'tgl_bayar' => $tgl_order,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'keterangan' => $ket_bayar,
                    'customer' => $request->customer,
                ]);

            DB::table('penjualan')
                ->insert([
                    'kode' => $kode,
                    'customer' => $request->customer,
                    'subtotal' => $subtotal,
                    'biaya_tambahan' => $biaya_tambahan,
                    'potongan' => $potongan,
                    'total' => $total,
                    'terbayar' => $terbayar,
                    'kekurangan' => $kekurangan,
                    'kembalian' => $kembalian,
                    'pembuat' => Auth::user()->id,
                    'tgl_buat' => $tgl_order,
                    'keterangan' => $request->keterangan,
                    'status' => $status,
                    'status_penjualan' => 'Draft',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'jenis_bayar' => $jenis_bayar,
                ]);

            DB::table('penjualan_thumb_detail')->where('pembuat', Auth::user()->id)->delete();
        });

        return response()->json(['status' => true]);
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
                $caribarang = DB::table('barang')->where('kode',$row->kode_barang)->first();
                if($caribarang){
                    if($status=='umum'){
                        $harga = intval($caribarang->harga_jual);
                        $diskon = floatval($caribarang->diskon ?? 0);
                    }else{
                        $harga = intval($caribarang->harga_jual_customer);
                        $diskon = floatval($caribarang->diskon_customer ?? 0);
                    }
                }
                                
                $jumlah = intval($row->jumlah);
                $jumlahdiskon = round($harga * $diskon / 100);
                $total = intval($jumlah * ($harga - $jumlahdiskon));

                DB::table('penjualan_thumb_detail')
                ->where('id', $row->id)
                ->where('pembuat', Auth::user()->id)
                ->update([
                    'diskon'=>$diskon,
                    'harga'=>$harga,
                    'jumlah'=>$jumlah,
                    'total'=>$total,
                ]);
            }
        }
        return response()->json(true);
    }

    //=================================================================
    public function hapusdetailpenjualan(Request $request)
    {
        DB::table('penjualan_thumb_detail')
            ->where('id', $request->kode)
            ->where('pembuat', Auth::user()->id)
            ->delete();
        return response()->json(true);
    }

    //=================================================================
    public function adddetailpenjualanqr(Request $request)
    {
        $status = true;
        $statusbarang = true;
        $kode_barang = $request->kode_barang;
        $caribarang = DB::table('barang')
            ->where(function ($query) use ($kode_barang) {
                $query->whereExists(function ($sub) use ($kode_barang) {
                    $sub->select(DB::raw(1))
                        ->from('barang_barcode')
                        ->whereColumn('barang_barcode.id_barang', 'barang.id')
                        ->where('barang_barcode.kode_barcode', $kode_barang);
                })
                ->orWhere('barang.kode', $kode_barang)
                ->orWhere('barang.kode_qr', $kode_barang);
            })
            ->first();

        if ($caribarang) {
            $stok = intval($caribarang->stok);
            $hitung_stok = $caribarang->hitung_stok;
            $kode_penjualan = trim($request->kode);
            $kode_brg = trim($caribarang->kode);

            if ($request->status == 'umum') {
                $harga = intval($caribarang->harga_jual);
                $diskon = floatval($caribarang->diskon ?? 0);
            } else {
                $harga = intval($caribarang->harga_jual_customer);
                $diskon = floatval($caribarang->diskon_customer ?? 0);
            }

            $caribarangdetail = DB::table('penjualan_thumb_detail')
                ->where([['kode_penjualan', $kode_penjualan], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                ->get();

            if ($caribarangdetail->count() > 0) {
                $total_jumlah = $caribarangdetail->sum('jumlah') + 1;
                if ($hitung_stok == 'y' && $total_jumlah > $stok) {
                    $status = false;
                } else {
                    $jumlahdiskon = round($harga * $diskon / 100);
                    $total = intval($total_jumlah * ($harga - $jumlahdiskon));

                    $first_id = $caribarangdetail->first()->id;
                    DB::table('penjualan_thumb_detail')
                        ->where([['kode_penjualan', $kode_penjualan], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                        ->where('id', '!=', $first_id)
                        ->delete();

                    DB::table('penjualan_thumb_detail')
                        ->where('id', $first_id)
                        ->update([
                            'diskon' => $diskon,
                            'harga' => $harga,
                            'jumlah' => $total_jumlah,
                            'total' => $total,
                        ]);
                    $status = true;
                }
            } else {
                $jumlah = 1;
                if ($hitung_stok == 'y' && $jumlah > $stok) {
                    $status = false;
                } else {
                    $jumlahdiskon = round($harga * $diskon / 100);
                    $total = intval($jumlah * ($harga - $jumlahdiskon));
                    DB::table('penjualan_thumb_detail')
                        ->insert([
                            'kode_penjualan' => $kode_penjualan,
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
        DB::transaction(function () use ($kode) {
            $detail = DB::table('penjualan_detail')
                ->where('kode_penjualan', $kode)
                ->get();
            foreach ($detail as $row) {
                DB::table('barang')
                    ->where('kode', $row->kode_barang)
                    ->where('hitung_stok', 'y')
                    ->increment('stok', intval($row->jumlah));
            }

            DB::table('penjualan_detail')->where('kode_penjualan', $kode)->delete();
            DB::table('pembayaran')->where('kode_penjualan', $kode)->delete();
            DB::table('penjualan')->where('kode', $kode)->delete();
        });

        return response()->json(['status' => true]);
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
        $kode = trim($request->kode);
        $dibayar_input = intval(str_replace('.', '', $request->dibayar));

        if (empty($kode)) {
            return response()->json(['status' => false, 'message' => 'Kode penjualan tidak valid'], 400);
        }

        if ($dibayar_input <= 0) {
            return response()->json(['status' => false, 'message' => 'Nominal pembayaran harus lebih dari 0'], 400);
        }

        $penjualan = DB::table('penjualan')->where('kode', $kode)->first();
        if (!$penjualan) {
            return response()->json(['status' => false, 'message' => 'Data transaksi penjualan tidak ditemukan'], 404);
        }

        $sisa_kekurangan = intval($penjualan->kekurangan);
        if ($sisa_kekurangan <= 0) {
            return response()->json(['status' => false, 'message' => 'Transaksi ini sudah lunas'], 400);
        }

        if ($dibayar_input > $sisa_kekurangan) {
            return response()->json(['status' => false, 'message' => 'Pembayaran melebihi sisa kekurangan hutang'], 400);
        }

        DB::transaction(function () use ($request, $kode, $penjualan, $dibayar_input, $sisa_kekurangan) {
            $kekurangan_baru = max(0, $sisa_kekurangan - $dibayar_input);
            $terbayar_baru = intval($penjualan->terbayar) + $dibayar_input;

            if ($kekurangan_baru <= 0) {
                $status = "Telah Lunas";
                $keterangan_bayar = "Pembayaran Hutang & Pelunasan";
            } else {
                $status = "Belum Lunas";
                $keterangan_bayar = "Pembayaran Hutang";
            }

            $tgl_bayar = !empty($request->tgl_bayar) ? $request->tgl_bayar : date('Y-m-d');

            DB::table('penjualan')
                ->where('kode', $kode)
                ->update([
                    'status' => $status,
                    'terbayar' => $terbayar_baru,
                    'kekurangan' => $kekurangan_baru,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id,
                ]);

            DB::table('pembayaran')
                ->insert([
                    'kode_penjualan' => $kode,
                    'jumlah' => $dibayar_input,
                    'tgl_bayar' => $tgl_bayar,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'keterangan' => $keterangan_bayar,
                    'customer' => $penjualan->customer,
                ]);
        });

        return response()->json(['status' => true, 'message' => 'Pembayaran kekurangan/hutang berhasil disimpan']);
    }
}