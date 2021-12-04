<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use File;
use DB;
use Auth;
use Hash;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //=================================================================
    public function index()
    {
        $jumlahpelanggan = DB::table('master_customer')->count();
        $jumlahsupplier = DB::table('master_customer')->count();
        $jumlahbarang = DB::table('barang')->count();
        $jumlahtransaksi = DB::table('penjualan')->count();
        $barangstokmenipis = DB::table('barang')->where('stok','<','10')->count();
        $jumlahhutang = DB::table('pembelian')->where('status','Belum Lunas')->count();
        $jumlahpiutang = DB::table('penjualan')->where('status','Belum Lunas')->count();

        $kategoritahunan = DB::table('penjualan_detail')
        ->select(DB::raw('penjualan_detail.*,penjualan.tgl_buat,barang.kategori,kategori_barang.nama,sum(penjualan_detail.jumlah) as totalpcs'))
        ->leftjoin('penjualan','penjualan.kode','=','penjualan_detail.kode_penjualan')
        ->leftjoin('barang','barang.kode','=','penjualan_detail.kode_barang')
        ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
        ->whereYear('penjualan.tgl_buat',date('Y'))
        ->groupby('barang.kategori')
        ->get();

        $linelabelmgn = '';
        $linevaluemgn = '';
        $nama_kategori = '';
        $value_kategori ='';
        $warna_kategori='';
        $linelabeltahun='';
        $linevallunastahun='';
        $linevalbelumlunastahun='';

        for ($x=7; $x > 0; $x--) { 
            $day=date('Y-m-d',strtotime(date('Y-m-d') . "-" .$x." days"));
            $linelabelmgn=$linelabelmgn."'".$day."',"; 
            $jmlminggu=DB::table('penjualan')->where('tgl_buat',$day)->count();
            $linevaluemgn = $linevaluemgn."".$jmlminggu.",";
        }

        foreach($kategoritahunan as $row_kategoritahunan){
            $nama_kategori = $nama_kategori."'".$row_kategoritahunan->nama."',";
            $value_kategori =$value_kategori."".$row_kategoritahunan->totalpcs.",";
            $warna_kategori =$warna_kategori."'#". str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."',";
        }

        for ($no_bln=1; $no_bln <= 12; $no_bln++) { 
            $new_no_bln = sprintf("%02s",$no_bln);
            $bulan_nama = date("F", mktime(0, 0, 0, $new_no_bln, 10));
            $linelabeltahun = $linelabeltahun."'".$bulan_nama."',";

            $jmlbulan=DB::table('penjualan')->whereYear('tgl_buat','=',date('Y'))->whereMonth('tgl_buat','=',$new_no_bln)->where('status','Telah Lunas')->count();
            $linevallunastahun = $linevallunastahun."".$jmlbulan.",";

            $jmlbulanblmlunas=DB::table('penjualan')->whereYear('tgl_buat','=',date('Y'))->whereMonth('tgl_buat','=',$new_no_bln)->where('status','Belum Lunas')->count();
            $linevalbelumlunastahun = $linevalbelumlunastahun."".$jmlbulanblmlunas.",";
        }

        return view('backend.dashboard.index',compact('linevallunastahun','linevalbelumlunastahun','linelabeltahun','warna_kategori','value_kategori','nama_kategori','linelabelmgn','linevaluemgn','kategoritahunan','jumlahpiutang','jumlahhutang','jumlahpelanggan','jumlahsupplier','jumlahbarang','jumlahtransaksi','barangstokmenipis'));
    }

    //==================================================================
    public function editprofile(){
        $data = User::find(Auth::user()->id);
        return view('backend.dashboard.editprofile',['data'=>$data]);
    }

    //==================================================================
    public function aksieditprofile(Request $request,$id){
        if($request->hasFile('gambar')){
            File::delete('img/admin/'.$request->gambar_lama);
            $nameland=$request->file('gambar')->
            getClientOriginalname();
            $lower_file_name=strtolower($nameland);
            $replace_space=str_replace(' ', '-', $lower_file_name);
            $finalname=time().'-'.$replace_space;
            $destination=public_path('img/admin');
            $request->file('gambar')->move($destination,$finalname);

            if($request->password==''){
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'gambar'=>$finalname,
                ]);
            }else{
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'gambar'=>$finalname,
                    'password'=>Hash::make($request->password),
                ]);
            }
        }else{
            if($request->password==''){
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                ]);
            }else{
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'password'=>Hash::make($request->password),
                ]);
            }
        }

        return redirect('/backend/home')->with('status','Sukses memperbarui profile');
    }

    //==================================================================
    public function websetting()
    {
        $data = DB::table('settings')->orderby('id','desc')->get();
        return view('backend.dashboard.websetting',compact('data'));
    }

    //==================================================================
    public function updatewebsetting(Request $request)
    {
        DB::table('settings')->where('id',$request->kode)
        ->update([
            'singkatan_nama_program'=>$request->singkatan_nama_program,
            'nama_program'=>$request->nama_program,
            'instansi'=>$request->instansi,
            'alamat'=>$request->alamat,
            'note'=>$request->note,
            'deskripsi_program'=>$request->deskripsi,
        ]);
        return redirect('/backend/home')->with('status','Sukses memperbarui setting web');
    }
}