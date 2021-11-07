<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'backend/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function username()
    {
        return 'username';
    }
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credential = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        
        $remember_me = $request->has('remember') ? true : false;
            if (auth()->attempt(['username' => $request->input('username'), 'password' => $request->input('password')], $remember_me))
            {
                $user = auth()->user();
                Auth::login($user,true);
                return redirect('backend/home');
            }else{
                return back()->with('error','your username and password are wrong.');

            }
    }
}
