<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $guarded = ['id'];
    public $timestamps = false;
}
