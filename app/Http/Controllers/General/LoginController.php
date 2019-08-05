<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tablas\Usuarios;

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
            return redirect()->route('login')->withErrors(['error'=>'El Usuario no tiene un rol activo.']);
        }
    }

    public function redirectTo()
    {
        $rol = Auth::user()->roles()->get();
        switch ($rol[0]['id']) {
            case '1':
                return '/administrador';
                break;
            
            case '2':
                return '/director';
                break;

            case '3':
                return '/finanzas';
                break;

            case '4':
                return '/tester';
                break;

            case '5':
                return '/cliente';
                break;

            default:
                switch ($rol[0]['RLS_Rol_Id']) {
                    case '6':
                        return '/perfil-operacion';
                        break;
                }
        }
    }
}
