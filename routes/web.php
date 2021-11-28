<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::prefix('backend')->group(function () {
    //-------------------------------------------------------------------------------------------
    Route::get('/home', 'backend\HomeController@index')->name('home');
    Route::get('/edit-profile', 'backend\HomeController@editprofile')->name('editprofile');
    Route::post('/edit-profile/{id}', 'backend\HomeController@aksieditprofile');

    //-------------------------------------------------------------------------------------------
    Route::resource('/roles','backend\rolesController');
    Route::get('/data-roles','backend\rolesController@listdata');
    
    //-------------------------------------------------------------------------------------------
    Route::get('/data-admin','backend\AdminController@listdata');
    Route::resource('/admin','backend\AdminController');

    //-------------------------------------------------------------------------------------------
    Route::get('/web-setting', 'backend\HomeController@websetting');
    Route::post('/web-setting', 'backend\HomeController@updatewebsetting');

    //-------------------------------------------------------------------------------------------
    Route::get('/data-customer','backend\customerController@listdata');
    Route::get('/data-customer/detail','backend\customerController@caridetailcustomer');
    Route::resource('/customer','backend\customerController');

    //-------------------------------------------------------------------------------------------
    Route::get('/data-kategori-barang','backend\kategoriBarangController@listdata');
    Route::resource('/kategori-barang','backend\kategoriBarangController');
    
    //-------------------------------------------------------------------------------------------
    Route::get('/data-supplier','backend\supplierController@listdata');
    Route::resource('/supplier','backend\supplierController');

    //-------------------------------------------------------------------------------------------
    Route::get('/data-barang','backend\barangController@listdata');
    Route::get('/data-barang/detail','backend\barangController@caridetailbarang');
    Route::get('/data-barang/cari-detail/{kode}','backend\barangController@pilihdetailbarang');
    Route::resource('/barang','backend\barangController');

    //-------------------------------------------------------------------------------------------
    Route::get('/data-pembelian','backend\pembelianController@listdata');
    Route::post('/data-pembelian/add-detail-pembelian','backend\pembelianController@adddetailpembelian');
    Route::post('/data-pembelian/add-detail-pembelian-qr','backend\pembelianController@adddetailpembelianqr');
    Route::post('/data-pembelian/edit-detail-pembelian','backend\pembelianController@editdetailpembelian');
    Route::post('/data-pembelian/hapus-detail-pembelian','backend\pembelianController@hapusdetailpembelian');
    Route::get('/data-pembelian/list-detail-pembelian/{kode}','backend\pembelianController@listdetailpembelian');
    Route::get('/data-pembelian/detail-pembelian/{id}','backend\pembelianController@detailpembelian');
    Route::post('/pembelian/update-status/{id}','backend\pembelianController@updatestatuspembelian');
    Route::resource('/pembelian','backend\pembelianController');

    //-------------------------------------------------------------------------------------------
    Route::get('/data-penjualan','backend\penjualanController@listdata');
    Route::post('/data-penjualan/add-detail-penjualan','backend\penjualanController@adddetailpenjualan');
    Route::post('/data-penjualan/hapus-detail-penjualan','backend\penjualanController@hapusdetailpenjualan');
    Route::post('/data-penjualan/add-detail-penjualan-qr','backend\penjualanController@adddetailpenjualanqr');
    Route::post('/data-penjualan/edit-detail-penjualan','backend\penjualanController@editdetailpembelian');
    Route::get('/data-penjualan/list-detail-penjualan/{kode}','backend\penjualanController@listdetailpenjualan');
    Route::get('/data-penjualan/detail-penjualan/{id}','backend\penjualanController@detailpenjualan');
    Route::get('/data-penjualan/cetak-ulang/{kode}','backend\penjualanController@cetakulang');
    Route::get('/data-penjualan/ganti-harga/{kode}/{status}','backend\penjualanController@gantiharga');
    Route::resource('/penjualan','backend\penjualanController');

});
