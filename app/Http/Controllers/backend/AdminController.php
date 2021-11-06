<?php
namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\User;
use File;
use Hash;
use DB;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-users', ['only' => ['index','show','listdata']]);
        $this->middleware('permission:create-users', ['only' => ['create']]);
        $this->middleware('permission:edit-users', ['only' => ['edit']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
    }

    //=================================================================
    public function index()
    {
        $data = DB::table('users')->orderby('id','desc')->get();
        return view('backend.admin.index',['data'=>$data]);
    }

    //=================================================================
    public function listdata(){
        return Datatables::of(User::all())->make(true);
    }
    
    //=================================================================
    public function create()
    {
        $roles = DB::table('roles')->orderby('id','desc')->get();
        return view('backend.admin.create',compact('roles'));
    }

    //=================================================================
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'unique:users'],
        ]);
        $nameland=$request->file('gambar')->getClientOriginalname();
        $lower_file_name=strtolower($nameland);
        $replace_space=str_replace(' ', '-', $lower_file_name);
        $finalname=time().'-'.$replace_space;
        $destination=public_path('img/admin');
        $request->file('gambar')->move($destination,$finalname);
        
        $usr = User::create([
            'name'=>$request->nama,
            'username'=>$request->username,
            'email'=>$request->email,
            'telp'=>$request->telp,
            'level'=>$request->level,
            'gambar'=>$finalname,
            'password'=>Hash::make($request->password),
        ]);
        $usr->assignRole($request->level);
        
        return redirect('/backend/admin')->with('status','Sukses menyimpan data');
    }

    //=================================================================
    public function show($id)
    {
        //
    }

    //=================================================================
    public function edit($id)
    {
        $roles = DB::table('roles')->orderby('id','desc')->get();
        $data = User::find($id);
        return view('backend.admin.edit',compact('data','roles'));
    }

    //=================================================================
    public function update(Request $request, $id)
    {
        if($request->username!=$request->oldusername){
            $request->validate([
                'username' => ['required', 'unique:users'],
            ]);
        }

        if($request->email!=$request->oldemail){
            $request->validate([
                'email' => ['required', 'unique:users'],
            ]);
        }
        
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
                    'level'=>$request->level,
                    'gambar'=>$finalname,
                ]);

                $usr = User::find($id);
                $usr->assignRole($request->level);

            }else{
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'level'=>$request->level,
                    'gambar'=>$finalname,
                    'password'=>Hash::make($request->password),
                ]);

                $usr = User::find($id);
                $usr->assignRole($request->level);
            }
        }else{
            if($request->password==''){
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'level'=>$request->level,
                ]);
                $usr = User::find($id);
                $usr->assignRole($request->level);
            }else{
                User::find($id)
                ->update([
                    'name'=>$request->nama,
                    'username'=>$request->username,
                    'email'=>$request->email,
                    'telp'=>$request->telp,
                    'level'=>$request->level,
                    'password'=>Hash::make($request->password),
                ]);
                $usr = User::find($id);
                $usr->assignRole($request->level);
            }
        }

        return redirect('/backend/admin')->with('status','Sukses memperbarui data');
    }

    //=================================================================
    public function destroy($id)
    {
        $data = User::find($id);
        if($data->gambar !=''){
            File::delete('img/admin/'.$data->gambar);
        }
        User::destroy($id);
        //return redirect('/backend/admin')->with('status','Sukses menghapus data');
    }
}
