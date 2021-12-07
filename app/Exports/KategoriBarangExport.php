<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Session;
use DB;

class KategoriBarangExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    
    public function collection(){
        return DB::table('kategori_barang')
        ->select('id','nama')
        ->get();
    }
    

    public function headings(): array{
        return [
            'id',
            'nama',
        ];
    }
}