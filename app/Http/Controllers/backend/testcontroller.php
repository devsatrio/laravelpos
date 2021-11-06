<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class testcontroller extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-test', ['only' => ['index','show']]);
        $this->middleware('permission:create-test', ['only' => ['create']]);
        $this->middleware('permission:edit-test', ['only' => ['edit']]);
        $this->middleware('permission:delete-test', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        echo "index";
    }

    public function create()
    {
        echo "create";
    }
    
    public function show($id)
    {
        echo "show";
    }
    
    public function edit($id)
    {    
        echo "edit";
    }
    
    public function destroy($id)
    {
        echo "'delete";
    }
}
