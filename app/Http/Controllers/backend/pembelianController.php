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
        $kode_pembelian = trim($request->kode);
        $kode_barang = trim($request->kode_barang);
        $jumlah_input = intval(str_replace('.', '', $request->jumlah_barang));
        $harga = intval(str_replace('.', '', $request->harga_barang));

        if ($jumlah_input <= 0 || empty($kode_pembelian) || empty($kode_barang)) {
            return response()->json(false);
        }

        if ($harga <= 0) {
            $brg = DB::table('barang')->where('kode', $kode_barang)->first();
            if ($brg) {
                $harga = intval($brg->harga_beli);
            }
        }

        $caribarangdetail = DB::table('pembelian_thumb_detail')
            ->where([['kode_pembelian', $kode_pembelian], ['kode_barang', $kode_barang], ['pembuat', Auth::user()->id]])
            ->get();

        if ($caribarangdetail->count() > 0) {
            $total_jumlah = $caribarangdetail->sum('jumlah') + $jumlah_input;
            $total = $total_jumlah * $harga;

            $first_id = $caribarangdetail->first()->id;
            DB::table('pembelian_thumb_detail')
                ->where([['kode_pembelian', $kode_pembelian], ['kode_barang', $kode_barang], ['pembuat', Auth::user()->id]])
                ->where('id', '!=', $first_id)
                ->delete();

            DB::table('pembelian_thumb_detail')
                ->where('id', $first_id)
                ->update([
                    'harga' => $harga,
                    'jumlah' => $total_jumlah,
                    'total' => $total,
                ]);
        } else {
            $total = $jumlah_input * $harga;
            DB::table('pembelian_thumb_detail')
                ->insert([
                    'kode_pembelian' => $kode_pembelian,
                    'kode_barang' => $kode_barang,
                    'jumlah' => $jumlah_input,
                    'harga' => $harga,
                    'total' => $total,
                    'pembuat' => Auth::user()->id,
                ]);
        }
        return response()->json(true);
    }

    //=================================================================
    public function adddetailpembelianqr(Request $request)
    {
        $statusbarang = true;
        $harga_barang = 0;
        $kode_barang = trim($request->kode_barang);
        $kode_pembelian = trim($request->kode);

        if (empty($kode_barang) || empty($kode_pembelian)) {
            return response()->json(false);
        }

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
            $kode_brg = trim($caribarang->kode);
            $harga_barang = intval($caribarang->harga_beli);

            $caribarangdetail = DB::table('pembelian_thumb_detail')
                ->where([['kode_pembelian', $kode_pembelian], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                ->get();

            if ($caribarangdetail->count() > 0) {
                $total_jumlah = $caribarangdetail->sum('jumlah') + 1;
                $total = $total_jumlah * $harga_barang;

                $first_id = $caribarangdetail->first()->id;
                DB::table('pembelian_thumb_detail')
                    ->where([['kode_pembelian', $kode_pembelian], ['kode_barang', $kode_brg], ['pembuat', Auth::user()->id]])
                    ->where('id', '!=', $first_id)
                    ->delete();

                DB::table('pembelian_thumb_detail')
                    ->where('id', $first_id)
                    ->update([
                        'harga' => $harga_barang,
                        'jumlah' => $total_jumlah,
                        'total' => $total,
                    ]);
            } else {
                DB::table('pembelian_thumb_detail')
                    ->insert([
                        'kode_pembelian' => $kode_pembelian,
                        'kode_barang' => $kode_brg,
                        'jumlah' => 1,
                        'harga' => $harga_barang,
                        'total' => $harga_barang,
                        'pembuat' => Auth::user()->id,
                    ]);
            }
        } else {
            $statusbarang = false;
        }
        return response()->json($statusbarang);
    }

    //=================================================================
    public function hapusdetailpembelian(Request $request)
    {
        DB::table('pembelian_thumb_detail')
            ->where('id', $request->kode)
            ->where('pembuat', Auth::user()->id)
            ->delete();
        return response()->json(true);
    }

    //=================================================================
    public function updatestatuspembelian(Request $request, $id)
    {
        $pembelian = DB::table('pembelian')->where('id', $id)->first();
        if (!$pembelian || $pembelian->status_pembelian === 'Approve') {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan atau sudah di-approve']);
        }

        DB::transaction(function () use ($id, $pembelian) {
            DB::table('pembelian')
                ->where('id', $id)
                ->update([
                    'status_pembelian' => 'Approve',
                ]);

            $data_detail_pembelian = DB::table('pembelian_detail')
                ->where('kode_pembelian', $pembelian->kode)
                ->get();

            foreach ($data_detail_pembelian as $row_data) {
                DB::table('barang')
                    ->where('kode', $row_data->kode_barang)
                    ->increment('stok', intval($row_data->jumlah));
            }
        });

        return response()->json(['status' => true]);
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
        $carikode = DB::table('pembelian')->where('kode', 'like', '%' . $datenow . '%')->max('kode');
        if (!$carikode) {
            $finalkode = 'PMB-' . $datenow . '-0001';
        } else {
            $newkode = explode("-", $carikode);
            $nomerUrut = isset($newkode[2]) ? (intval($newkode[2]) + 1) : 1;
            $nomer = sprintf("%04s", $nomerUrut);
            $finalkode = 'PMB-' . $datenow . '-' . $nomer;
        }
        return $finalkode;
    }

    //=================================================================
    public function store(Request $request)
    {
        $kode = trim($request->kode);
        if (empty($kode)) {
            return response()->json(['status' => false, 'message' => 'Kode pembelian tidak valid'], 400);
        }

        // Cek idempotensi: Jika transaksi sudah ada, tolak duplikasi
        if (DB::table('pembelian')->where('kode', $kode)->exists()) {
            return response()->json(['status' => false, 'message' => 'Transaksi pembelian sudah tersimpan sebelumnya']);
        }

        $detail = DB::table('pembelian_thumb_detail')
            ->where([['kode_pembelian', $kode], ['pembuat', Auth::user()->id]])
            ->get();

        if ($detail->count() === 0) {
            return response()->json(['status' => false, 'message' => 'Tidak ada barang yang dibeli'], 400);
        }

        if (empty($request->supplier)) {
            return response()->json(['status' => false, 'message' => 'Supplier harus dipilih'], 400);
        }

        DB::transaction(function () use ($request, $kode, $detail) {
            $data = [];
            foreach ($detail as $row) {
                $data[] = [
                    'kode_pembelian' => $kode,
                    'kode_barang' => $row->kode_barang,
                    'jumlah' => $row->jumlah,
                    'harga' => $row->harga,
                    'total' => $row->total,
                ];
            }

            DB::table('pembelian_detail')->insert($data);

            // Hitung nilai keuangan secara aman di sisi server dari item yang ada di keranjang
            $subtotal = intval($detail->sum('total'));
            $biaya_tambahan = max(0, intval(str_replace('.', '', $request->biaya_tambahan)));
            $potongan = max(0, intval(str_replace('.', '', $request->potongan)));
            $total = max(0, $subtotal + $biaya_tambahan - $potongan);
            $dibayar = max(0, intval(str_replace('.', '', $request->dibayar)));
            $kekurangan = max(0, $total - $dibayar);

            if ($kekurangan > 0) {
                $status = "Belum Lunas";
            } else {
                $status = "Telah Lunas";
            }

            $tgl_order = !empty($request->tgl_order) ? $request->tgl_order : date('Y-m-d');

            DB::table('pembelian')->insert([
                'kode' => $kode,
                'supplier' => $request->supplier,
                'subtotal' => $subtotal,
                'biaya_tambahan' => $biaya_tambahan,
                'potongan' => $potongan,
                'total' => $total,
                'terbayar' => $dibayar,
                'kekurangan' => $kekurangan,
                'pembuat' => Auth::user()->id,
                'tgl_buat' => $tgl_order,
                'keterangan' => $request->keterangan,
                'status' => $status,
                'status_pembelian' => 'Draft',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ]);

            DB::table('pembelian_thumb_detail')->where('pembuat', Auth::user()->id)->delete();
        });

        return response()->json(['status' => true]);
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
        $status = "";
        DB::table('pembelian_thumb_detail')->where('pembuat', Auth::user()->id)->delete();

        $data_pembelian = DB::table('pembelian')->where('kode', $kode_pembelian)->get();
        $data_detail_pembelian = DB::table('pembelian_detail')->where('kode_pembelian', $kode_pembelian)->get();
        $data = [];
        foreach ($data_detail_pembelian as $row_data_detail_pembelian) {
            $data[] = [
                'kode_pembelian' => $row_data_detail_pembelian->kode_pembelian,
                'kode_barang' => $row_data_detail_pembelian->kode_barang,
                'jumlah' => $row_data_detail_pembelian->jumlah,
                'harga' => $row_data_detail_pembelian->harga,
                'total' => $row_data_detail_pembelian->total,
                'pembuat' => Auth::user()->id
            ];
        }

        if (count($data) > 0) {
            DB::table('pembelian_thumb_detail')->insert($data);
        }
        $supplier = DB::table('master_supplier')->orderby('id', 'desc')->get();
        $web_set = DB::table('settings')->orderby('id', 'desc')->get();

        $kode = $kode_pembelian;
        foreach ($data_pembelian as $row_data_pembelian) {
            $status = $row_data_pembelian->status_pembelian;
        }
        if ($status == 'Approve') {
            return view('backend.pembelian.edit_appr', compact('kode', 'supplier', 'data_pembelian'));
        } else {
            return view('backend.pembelian.edit', compact('kode', 'supplier', 'data_pembelian', 'web_set'));
        }
    }

    //=================================================================
    public function detailpembelian($id)
    {
        $data = DB::table('pembelian_thumb_detail')
            ->select(DB::raw('pembelian_thumb_detail.*,barang.nama as namabarang'))
            ->leftjoin('barang', 'barang.kode', '=', 'pembelian_thumb_detail.kode_barang')
            ->where('pembelian_thumb_detail.id', $id)
            ->get();
        return response()->json($data);
    }

    //=================================================================
    public function editdetailpembelian(Request $request)
    {
        $harga_barang = intval(str_replace('.', '', $request->edit_harga_barang));
        $jumlah = intval(str_replace('.', '', $request->edit_jumlah_barang));
        if ($jumlah <= 0) {
            return response()->json(['status' => false, 'message' => 'Jumlah harus lebih dari 0'], 400);
        }
        $total = $harga_barang * $jumlah;
        DB::table('pembelian_thumb_detail')
            ->where('id', $request->edit_id)
            ->where('pembuat', Auth::user()->id)
            ->update([
                'jumlah' => $jumlah,
                'harga' => $harga_barang,
                'total' => $total,
            ]);
        return response()->json(true);
    }

    //=================================================================
    public function update(Request $request, $kode_pembelian)
    {
        $pembelian = DB::table('pembelian')->where('kode', $kode_pembelian)->first();
        if (!$pembelian) {
            return response()->json(['status' => false, 'message' => 'Data pembelian tidak ditemukan'], 404);
        }

        DB::transaction(function () use ($request, $kode_pembelian, $pembelian) {
            $is_approved = ($pembelian->status_pembelian === 'Approve');

            // Jika statusnya belum Approve, perbarui item detail dari keranjang thumb
            if (!$is_approved) {
                $detail = DB::table('pembelian_thumb_detail')
                    ->where('kode_pembelian', $request->kode)
                    ->where('pembuat', Auth::user()->id)
                    ->get();

                if ($detail->count() > 0) {
                    DB::table('pembelian_detail')->where('kode_pembelian', $kode_pembelian)->delete();
                    $data = [];
                    foreach ($detail as $row) {
                        $data[] = [
                            'kode_pembelian' => $kode_pembelian,
                            'kode_barang' => $row->kode_barang,
                            'jumlah' => $row->jumlah,
                            'harga' => $row->harga,
                            'total' => $row->total,
                        ];
                    }
                    DB::table('pembelian_detail')->insert($data);
                }
            }

            // Hitung nilai keuangan secara aman
            if (!$is_approved) {
                $subtotal_db = DB::table('pembelian_detail')->where('kode_pembelian', $kode_pembelian)->sum('total');
                $subtotal = intval($subtotal_db);
            } else {
                $subtotal = intval(str_replace('.', '', $request->subtotal));
            }

            $biaya_tambahan = max(0, intval(str_replace('.', '', $request->biaya_tambahan)));
            $potongan = max(0, intval(str_replace('.', '', $request->potongan)));
            $total = max(0, $subtotal + $biaya_tambahan - $potongan);
            $dibayar = max(0, intval(str_replace('.', '', $request->dibayar)));
            $kekurangan = max(0, $total - $dibayar);

            if ($kekurangan > 0) {
                $status = "Belum Lunas";
            } else {
                $status = "Telah Lunas";
            }

            $tgl_order = !empty($request->tgl_order) ? $request->tgl_order : $pembelian->tgl_buat;

            DB::table('pembelian')
                ->where('kode', $kode_pembelian)
                ->update([
                    'supplier' => $request->supplier ?: $pembelian->supplier,
                    'subtotal' => $subtotal,
                    'biaya_tambahan' => $biaya_tambahan,
                    'potongan' => $potongan,
                    'total' => $total,
                    'terbayar' => $dibayar,
                    'kekurangan' => $kekurangan,
                    'pembuat' => Auth::user()->id,
                    'tgl_buat' => $tgl_order,
                    'keterangan' => $request->keterangan,
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id,
                ]);

            DB::table('pembelian_thumb_detail')->where('pembuat', Auth::user()->id)->delete();
        });

        return response()->json(['status' => true]);
    }

    //=================================================================
    public function destroy($id)
    {
        $data = DB::table('pembelian')->where('id', $id)->first();
        if (!$data) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        DB::transaction(function () use ($id, $data) {
            // Apabila status pembelian sudah approve maka stok barang dikurangi
            if ($data->status_pembelian === 'Approve') {
                $detail = DB::table('pembelian_detail')
                    ->where('kode_pembelian', $data->kode)
                    ->get();
                foreach ($detail as $row) {
                    DB::table('barang')
                        ->where('kode', $row->kode_barang)
                        ->decrement('stok', intval($row->jumlah));
                }
            }

            DB::table('pembelian_detail')->where('kode_pembelian', $data->kode)->delete();
            DB::table('pembelian')->where('id', $id)->delete();
        });

        return response()->json(['status' => true]);
    }
}