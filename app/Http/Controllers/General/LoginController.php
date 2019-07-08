<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/administrador';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(){
        return 'USR_Nombre_Usuario';
    }

    public function password(){
        return 'USR_Clave_Usuario';
    }

    public function index()
    {
        return view('general.login');
    }

    public function datos(Request $request){
        dd($request->all());
    }
}
