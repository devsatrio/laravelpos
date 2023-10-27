<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Session;
use Auth;
use DB;

class BarangExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    
    public function collection(){
        if(auth()->user()->can('view-harga-beli-barang')){
            return DB::table('barang')
            ->select(DB::raw('barang.kode,barang.kode_qr,barang.nama,kategori_barang.nama as namakategori,barang.harga_beli,barang.harga_jual,barang.harga_jual_customer,barang.diskon,barang.diskon_customer,barang.stok,barang.keterangan'))
            ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
            ->get();
        }else{
            return DB::table('barang')
            ->select(DB::raw('barang.kode,barang.kode_qr,barang.nama,kategori_barang.nama as namakategori,barang.harga_jual,barang.harga_jual_customer,barang.diskon,barang.diskon_customer,barang.stok,barang.keterangan'))
            ->leftjoin('kategori_barang','kategori_barang.id','=','barang.kategori')
            ->get();
        }
        
    }
    

    public function headings(): array{
        
        if(auth()->user()->can('view-harga-beli-barang')){
            return [
                'kode',
                'kode_qr',
                'nama',
                'kategori',
                'harga_beli',
                'harga_jual',
                'harga_jual_customer',
                'diskon',
                'diskon_customer',
                'stok',
                'keterangan'
            ];
        }else{
            return [
                'kode',
                'kode_qr',
                'nama',
                'kategori',
                'harga_jual',
                'harga_jual_customer',
                'diskon',
                'diskon_customer',
                'stok',
                'keterangan'
            ];
        }
    }
}