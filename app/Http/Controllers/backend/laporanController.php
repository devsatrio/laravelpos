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
        $this->middleware('permission:view-laporan-penjualan', ['only' => ['laporanpenjualan']]);
        $this->middleware('permission:view-laporan-detail-penjualan', ['only' => ['laporandetailpenjualan']]);
        $this->middleware('permission:view-laporan-pembelian', ['only' => ['laporanpembelian']]);
        $this->middleware('permission:view-laporan-detail-pembelian', ['only' => ['laporandetailpembelian']]);
        $this->middleware('permission:view-laporan-pemasukan-pengeluaran-lain', ['only' => ['laporanlain']]);
        $this->middleware('permission:view-laporan-laba-rugi', ['only' => ['laporanlabarugi']]);
        $this->middleware('permission:view-laporan-modal', ['only' => ['laporanmodal']]);
        $this->middleware('permission:view-laporan-nilai-barang', ['only' => ['laporannilaibarang']]);
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

    //=================================================================
    public function laporanpembelian(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }

        if ($request->has('supplier') ||$request->has('pembuat') ||$request->has('status')) {
            if($request->supplier!='Semua'){
                if($request->pembuat!='Semua'){
                    if($request->status!='Semua'){
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->where('pembelian.status','=',$request->status)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }
                }else{
                    if($request->status!='Semua'){
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian.status','=',$request->status)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }
                }
            }else{
                if($request->pembuat!='Semua'){
                    if($request->status!='Semua'){
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->where('pembelian.status','=',$request->status)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }
                }else{
                    if($request->status!='Semua'){
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.status','=',$request->status)
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian')
                        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->orderby('pembelian.id','desc')
                        ->get();
                    }
                }
            }
        }else{
            $data = DB::table('pembelian')
            ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
            ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
            ->leftjoin('users','users.id','=','pembelian.pembuat')
            ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
            ->orderby('pembelian.id','desc')
            ->get();
        }
        $datasupplier = DB::table('master_supplier')->orderby('id','desc')->get();
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        
        return view('backend.laporan.laporanpembelian',compact('datasupplier','dataadmin','data'));
    }

    //=================================================================
    public function laporanlain(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }
        if ($request->has('pembuat') ||$request->has('status')) {
            if($request->pembuat!='Semua'){
                if($request->status!='Semua'){
                    $data = DB::table('transaksi_lain')
                    ->select(DB::raw('transaksi_lain.*,users.name'))
                    ->leftjoin('users','users.id','=','transaksi_lain.created_by')
                    ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
                    ->where('transaksi_lain.created_by','=',$request->pembuat)
                    ->where('transaksi_lain.status','=',$request->status)
                    ->orderby('transaksi_lain.id','desc')
                    ->get();
                }else{
                    $data = DB::table('transaksi_lain')
                    ->select(DB::raw('transaksi_lain.*,users.name'))
                    ->leftjoin('users','users.id','=','transaksi_lain.created_by')
                    ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
                    ->where('transaksi_lain.created_by','=',$request->pembuat)
                    ->orderby('transaksi_lain.id','desc')
                    ->get();
                }
            }else{
                if($request->status!='Semua'){
                    $data = DB::table('transaksi_lain')
                    ->select(DB::raw('transaksi_lain.*,users.name'))
                    ->leftjoin('users','users.id','=','transaksi_lain.created_by')
                    ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
                    ->where('transaksi_lain.status','=',$request->status)
                    ->orderby('transaksi_lain.id','desc')
                    ->get();
                }else{
                    $data = DB::table('transaksi_lain')
                    ->select(DB::raw('transaksi_lain.*,users.name'))
                    ->leftjoin('users','users.id','=','transaksi_lain.created_by')
                    ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
                    ->orderby('transaksi_lain.id','desc')
                    ->get();
                }
            }
        }else{
            $data = DB::table('transaksi_lain')
            ->select(DB::raw('transaksi_lain.*,users.name'))
            ->leftjoin('users','users.id','=','transaksi_lain.created_by')
            ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
            ->orderby('transaksi_lain.id','desc')
            ->get();
        }
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        
        return view('backend.laporan.laporanlain',compact('dataadmin','data'));
    }

    //==========================================================================
    public function laporandetailpenjualan(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }
        if ($request->has('customer') ||$request->has('pembuat') ||$request->has('barang')) {
            if($request->customer!='Semua'){
                if($request->pembuat!='Semua'){
                    if($request->barang!='Semua'){
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->where('penjualan_detail.kode_barang','=',$request->barang)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }
                }else{
                    if($request->barang!='Semua'){
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->where('penjualan_detail.kode_barang','=',$request->barang)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.customer','=',$request->customer)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }
                }
            }else{
                if($request->pembuat!='Semua'){
                    if($request->barang!='Semua'){
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->where('penjualan_detail.kode_barang','=',$request->barang)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan.pembuat','=',$request->pembuat)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }
                }else{
                    if($request->barang!='Semua'){
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->where('penjualan_detail.kode_barang','=',$request->barang)
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }else{
                        $data = DB::table('penjualan_detail')
                        ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
                        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
                        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                        ->leftjoin('users','users.id','=','penjualan.pembuat')
                        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                        ->orderby('penjualan_detail.kode_penjualan','desc')
                        ->get();
                    }
                }
            }
        }else{
            $data = DB::table('penjualan_detail')
            ->select(DB::raw('penjualan_detail.*,users.name,penjualan.tgl_buat,penjualan.customer,master_customer.nama as namacustomer,penjualan.pembuat,barang.nama,barang.harga_beli'))
            ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
            ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
            ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
            ->leftjoin('users','users.id','=','penjualan.pembuat')
            ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
            ->orderby('penjualan_detail.kode_penjualan','desc')
            ->get();
        }
        $datacustomer = DB::table('master_customer')->orderby('id','desc')->get();
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        $databarang= DB::table('barang')->orderby('id','desc')->get();
        return view('backend.laporan.laporandetailpenjualan',compact('datacustomer','dataadmin','data','databarang'));
    }

    //==========================================================================
    public function laporandetailpembelian(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }

        if ($request->has('supplier') ||$request->has('pembuat')||$request->has('barang')) {
            if($request->supplier!='Semua'){
                if($request->pembuat!='Semua'){
                    if($request->barang!='Semua'){
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->where('pembelian_detail.kode_barang','=',$request->barang)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }
                }else{
                    if($request->barang!='Semua'){
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->where('pembelian_detail.kode_barang','=',$request->barang)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }else{                        
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.supplier','=',$request->supplier)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }
                }
            }else{
                if($request->pembuat!='Semua'){
                    if($request->barang!='Semua'){
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->where('pembelian_detail.kode_barang','=',$request->barang)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian.pembuat','=',$request->pembuat)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }
                }else{
                    if($request->barang!='Semua'){
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->where('pembelian_detail.kode_barang','=',$request->barang)
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }else{
                        $data = DB::table('pembelian_detail')
                        ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
                        ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
                        ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
                        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
                        ->leftjoin('users','users.id','=','pembelian.pembuat')
                        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
                        ->orderby('pembelian_detail.id','desc')
                        ->get();
                    }
                }
            }
        }else{
            $data = DB::table('pembelian_detail')
            ->select(DB::raw('pembelian_detail.*,pembelian.tgl_buat,pembelian.pembuat,pembelian.supplier,master_supplier.nama as namasupplier,users.name,barang.nama as namabarang'))
            ->leftjoin('pembelian','pembelian.kode','=','pembelian_detail.kode_pembelian')
            ->leftjoin('barang','barang.kode','=','pembelian_detail.kode_barang')
            ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
            ->leftjoin('users','users.id','=','pembelian.pembuat')
            ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
            ->orderby('pembelian_detail.id','desc')
            ->get();
        }
        $datasupplier = DB::table('master_supplier')->orderby('id','desc')->get();
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        $databarang= DB::table('barang')->orderby('id','desc')->get();
        
        return view('backend.laporan.laporandetailpembelian',compact('datasupplier','dataadmin','data','databarang'));
    }

    //==========================================================================
    public function laporanlabarugi(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }

        
        $pemasukan_penjualan = DB::table('penjualan')
        ->select(DB::raw('penjualan.*,master_customer.nama as namacustomer,users.name'))
        ->leftjoin('master_customer','master_customer.kode','=','penjualan.customer')
        ->leftjoin('users','users.id','=','penjualan.pembuat')
        ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
        ->orderby('penjualan.id','desc')
        ->get();

        $pengeluaran_pembelian = DB::table('pembelian')
        ->select(DB::raw('pembelian.*,master_supplier.nama as namasupplier,users.name'))
        ->leftjoin('master_supplier','master_supplier.kode','=','pembelian.supplier')
        ->leftjoin('users','users.id','=','pembelian.pembuat')
        ->whereBetween('pembelian.tgl_buat',[$tglsatu,$tgldua])
        ->orderby('pembelian.id','desc')
        ->get();

        $transaksi_lain = DB::table('transaksi_lain')
        ->select(DB::raw('transaksi_lain.*,users.name'))
        ->leftjoin('users','users.id','=','transaksi_lain.created_by')
        ->whereBetween('transaksi_lain.tgl_buat',[$tglsatu,$tgldua])
        ->orderby('transaksi_lain.id','desc')
        ->get();
        return view('backend.laporan.laporanlabarugi',compact('pemasukan_penjualan','pengeluaran_pembelian','transaksi_lain'));
    }

    //==========================================================================
    public function laporanmodal(Request $request)
    {   
        if($request->has('kategori_barang')){
            $data_modal =DB::table('barang')
            ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
            ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
            ->where('barang.kategori','=',$request->kategori_barang)
            ->orderby('barang.id','desc')
            ->get();
        }else{
            $data_modal =DB::table('barang')
            ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
            ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
            ->orderby('barang.id','desc')
            ->get();
        }
        
        $kategori_barang = DB::table('kategori_barang')->orderby('id','desc')->get();
        return view('backend.laporan.laporanmodal',compact('data_modal','kategori_barang'));
    }

    //==========================================================================
    public function laporanpenjualanbarang(Request $request)
    {
        if($request->has('tanggal')){
            $tanggal = explode(' - ',$request->tanggal);
            $tglsatu = $tanggal[0];
            $tgldua = $tanggal[1];
        }else{
            $tglsatu = date('Y-m-d');
            $tgldua = date('Y-m-d');
        }
        if($request->has('barang')) {
            if($request->barang=='Semua'){
                $data = DB::table('penjualan_detail')
                ->select(DB::raw('penjualan_detail.*,sum(penjualan_detail.jumlah) as total_jumlah_penjualan,sum(penjualan_detail.total) as total_penjualan,penjualan.tgl_buat,barang.nama,barang.harga_beli'))
                ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                ->groupby('penjualan_detail.kode_barang')
                ->orderby('penjualan_detail.kode_penjualan','desc')
                ->get();

            }else{
                $data = DB::table('penjualan_detail')
                ->select(DB::raw('penjualan_detail.*,sum(penjualan_detail.jumlah) as total_jumlah_penjualan,sum(penjualan_detail.total) as total_penjualan,penjualan.tgl_buat,barang.nama,barang.harga_beli'))
                ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
                ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
                ->where('penjualan_detail.kode_barang','=',$request->barang)
                ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
                ->groupby('penjualan_detail.kode_barang')
                ->orderby('penjualan_detail.kode_penjualan','desc')
                ->get();
            }
        }else{
            $data = DB::table('penjualan_detail')
            ->select(DB::raw('penjualan_detail.*,sum(penjualan_detail.jumlah) as total_jumlah_penjualan,sum(penjualan_detail.total) as total_penjualan,penjualan.tgl_buat,barang.nama,barang.harga_beli'))
            ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
            ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
            ->whereBetween('penjualan.tgl_buat',[$tglsatu,$tgldua])
            ->groupby('penjualan_detail.kode_barang')
            ->orderby('penjualan_detail.kode_penjualan','desc')
            ->get();
        }
        $datacustomer = DB::table('master_customer')->orderby('id','desc')->get();
        $dataadmin = DB::table('users')->orderby('id','desc')->get();
        $databarang= DB::table('barang')->orderby('id','desc')->get();
        return view('backend.laporan.laporanpenjualanbarang',compact('datacustomer','dataadmin','data','databarang'));
    }

    //==========================================================================
    public function laporannilaibarang(Request $request) {
        $kategoribarang = DB::table('kategori_barang')->orderby('id','desc')->get();
        $data = DB::table('barang')
        ->select(DB::raw('barang.*,kategori_barang.nama as namakategori'))
        ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori');

        if ($request->has('kategori')){
            if($request->kategori!='semua'){
                $data=$data->where('barang.kategori',$request->kategori);
            }
        }
        $data=$data->orderby('barang.id','desc')->get();

        return view('backend.laporan.laporannilaibarang',compact('data','kategoribarang'));
    }
}