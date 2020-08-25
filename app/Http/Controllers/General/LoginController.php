<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Tablas\SesionUsuario;
use App\Models\Tablas\Usuarios;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Login Controller, controlador para la autenticcación de usuarios
 * en el sistema.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'USR_Nombre_Usuario';
    }

    /**
     * Visualiza la página del inicio de sesión del software
     *
     * @return \Illuminate\View\View Vista de login
     */
    public function index()
    {
        return view('general.login');
    }

    /**
     * Verifica las credenciales del usuario y verifica el rol autneticado
     *
     */
    public function authenticated(Request $request, $user)
    {
        $roles = $user->roles()->where('USR_RLS_Estado', 1)->get();
        if($roles->isNotEmpty()){
            $user->setSession($roles->toArray());
        }else{
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors(['error'=>'El Usuario no tiene un rol activo.']);
        }
    }

    /**
     * Cierra y elimina la sesión del usuario
     *
     */
    public function logout(Request $request)
    {
        SesionUsuario::where('SES_USR_Usuario_Id', session()->get('Usuario_Id'))
            ->delete();
        
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/iniciar-sesion');
    }

    /**
     * Redirige el usuario dependiendo del rol autenticado
     *
     */
    public function redirectTo()
    {
        if(session()->get('Usuario_Id') != null) {
            $estado = SesionUsuario::where('SES_USR_Usuario_Id', '=', session()->get('Usuario_Id'))
                ->where('SES_USR_Estado_Sesion', '=', 1)
                ->first()->SES_USR_Estado_Sesion;
            if($estado) {
                $rol = Auth::user()->roles()->get();

                switch ($rol[0]['id']) {
                    case '1':
                        return '/administrador';
                        break;
                    
                    case '2':
                        return '/director';
                        break;

                    case '3':
                        return '/cliente';
                        break;

                    default:
                        switch ($rol[0]['RLS_Rol_Id']) {
                            case '4':
                                return '/perfil-operacion';
                                break;
                        }
                }
            } else {
                return '/activar-sesion';
            }
        }
    }
}