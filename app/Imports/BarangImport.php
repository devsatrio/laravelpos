<?php

namespace App\Imports;
use App\models\BarangModel;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class BarangImport implements ToModel, WithHeadingRow,WithValidation
{

    use Importable;
    public function model(array $row)
    {
        $kode = $this->carikode();
        $barang = BarangModel::create([
            'kode' => $kode,
            'nama'=>$row['nama'],
            'kategori'=>$row['kategori'],
            'harga_beli'=>$row['harga_beli'],
            'harga_jual'=>$row['harga_jual'],
            'harga_jual_customer'=>$row['harga_jual_customer'],
            'diskon'=>$row['diskon'],
            'diskon_customer'=>$row['diskon_customer'],
            'stok'=>0,
            'keterangan'=>$row['keterangan'],
            'hitung_stok'=>$row['hitung_stok'],
        ]);

        $barcodeValue = isset($row['barcode']) ? $row['barcode'] : (isset($row['kode_qr']) ? $row['kode_qr'] : null);
        if ($barcodeValue && $barcodeValue != '-') {
            $barcodes = explode(',', $barcodeValue);
            foreach ($barcodes as $bc) {
                $bc = trim($bc);
                if (!empty($bc)) {
                    DB::table('barang_barcode')->insert([
                        'id_barang' => $barang->id,
                        'kode_barcode' => $bc,
                    ]);
                }
            }
        }

        return $barang;
    }

    //=================================================================
    public function rules(): array
    {
        return [
            'nama'=>'required|string',
            'kategori'=>'required|numeric',
            'harga_beli'=>'required|numeric',
            'harga_jual'=>'required|numeric',
            'harga_jual_customer'=>'required|numeric',
            'diskon'=>'required|numeric',
            'diskon_customer'=>'required|numeric',
            'hitung_stok' => 'in:y,n'
        ];
    }

    //=================================================================
    public function carikode()
    {
        $carikode = DB::table('barang')->max('kode');
        if(!$carikode){
            $finalkode = 'BRG-0001';
        }else{
            $newkode    = explode("-", $carikode);
            $nomer      = sprintf("%04s",$newkode[1]+1);
            $finalkode = 'BRG-'.$nomer;
        }
        return $finalkode;
    }
}