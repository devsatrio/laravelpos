<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use DB;

class rolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //=================================================================
    public function index()
    {
        return view('backend.roles.index');
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(DB::table('roles')
        ->select(DB::raw('roles.*,count(role_has_permissions.role_id) as total'))
        ->leftjoin('role_has_permissions','role_has_permissions.role_id','=','roles.id')
        ->groupby('roles.id')
        ->orderby('roles.id','desc')
        ->get())->make(true);
    }

    //=================================================================
    public function create()
    {
        $data_permission = DB::table('permissions')->orderby('grub','desc')->get();
        $permission_grub = DB::table('permissions')->groupby('grub')->orderby('grub','desc')->get();
        return view('backend.roles.create',compact('data_permission','permission_grub'));
    }

    //=================================================================
    public function store(Request $request)
    { 
        $role = Role::create(['name' => $request->input('nama')]);
        $role->syncPermissions($request->input('permission'));
        return redirect('backend/roles')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = DB::table('permissions')->orderby('grub','desc')->get();
        $permission_grub = DB::table('permissions')->groupby('grub')->orderby('grub','desc')->get();
        $rolePermissions =  DB::table('role_has_permissions')->where('role_id',$id)->get();
        return view('backend.roles.edit',compact('role','permission','permission_grub','rolePermissions'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->nama;
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect('backend/roles')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        $code='200';
        $msg='Berhasil Dihapus!';
        
        $cek = DB::table('model_has_roles')
        ->where('role_id',$id)
        ->get();

        if(count($cek)>0){
            $code='400';
            $msg='Gagal Dihapus! Data Sedang Digunakan di User';
        }else{
            DB::table("roles")->where('id',$id)->delete();
        }

        $print=[
            'code'=>$code,
            'msg'=>$msg
        ];
        return response()->json($print);
    }
}
