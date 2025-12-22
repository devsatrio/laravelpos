<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\KategoriBarangExport;
use DataTables;
use DB;

class permissionsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-permission', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-permission', ['only' => ['create','store']]);
        $this->middleware('permission:edit-permission', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-permission', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        return view('backend.permission.index');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(DB::table('permissions')->orderby('id','desc')->get())->make(true);
    }

    //=================================================================
    public function store(Request $request)
    {
        DB::table('permissions')
        ->insert([
            'name'=>strtolower(str_replace(' ','-',$request->permission)),
            'grub'=>strtolower(str_replace(' ','-',$request->permission_grup)),
            'guard_name'=>'web'
        ]);
        return redirect('/backend/permission')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function show($id)
    {
        $data = DB::table('permissions')
        ->where('id',$id)
        ->get();

        return response()->json($data);
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        DB::table('permissions')
        ->where('id',$id)
        ->update([
            'name'=>strtolower(str_replace(' ','-',$request->permission)),
            'grub'=>strtolower(str_replace(' ','-',$request->permission_grup)),
        ]);
        return redirect('/backend/permission')->with('status','Sukses memperbarui data');
    }

    
    //=================================================================
    public function destroy($id)
    {
        $code='200';
        $msg='Berhasil Dihapus!';
        
        $cek = DB::table('role_has_permissions')
        ->where('permission_id',$id)
        ->get();

        if(count($cek)>0){
            $code='400';
            $msg='Gagal Dihapus! Data Sedang Digunakan di Role';
        }else{
            DB::table('permissions')
            ->where('id',$id)
            ->delete();
        }

        $print=[
            'code'=>$code,
            'msg'=>$msg
        ];
        return response()->json($print);
    }
}
