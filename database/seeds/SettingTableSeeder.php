<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'nama_program' => 'D-Boiler Laravel 7',
            'singkatan_nama_program'=>'DBL7',
            'instansi' => 'unknow',
            'deskripsi_program'=>'A free boiler for everyone',
        ]);
    }
}
