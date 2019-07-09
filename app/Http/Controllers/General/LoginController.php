<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        return 'USR_Nombre_Usuario';
    }

    public function index()
    {
        return view('general.login');
    }

    public function authenticated(Request $request, $user){
        $roles = $user->roles()->where('USR_RLS_Estado', 1)->get();
        if($roles->isNotEmpty()){
            $user->setSession($roles->toArray());
        }else{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('iniciar-sesion')->withErrors(['error'=>'El Usuario no tiene un rol activo.']);
        }
    }

    public function redirectTo()
    {
        $rol = Auth::user()->roles()->get();
        if($rol[0]['id']=='1'){
            return '/administrador';
        }else if($rol[0]['id']=='2'){
            return '/director';
        }
    }
}
