<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Tablas\SesionUsuario;
use App\Models\Tablas\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Inicio Controller, controlador que sirve en caso de que se desee
 * tener una página de inicio con las descripciones del Software.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class InicioController extends Controller
{
    /**
     * Redirige al formulario de cuenta en suspensión
     *
     */
    public function formActivar()
    {
        if(session()->get('Usuario_Id') != null){
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));

            $sesion = SesionUsuario::where('SES_USR_Usuario_Id', '=', $usuario->id)->first()->SES_USR_Estado_Sesion;
            
            if(!$sesion){
                return view('general.inactiva');
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Reactiva la sesión del usuario
     *
     */
    public function inactivar()
    {
        if(session()->get('Usuario_Id') != null){
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));

            SesionUsuario::where('SES_USR_Usuario_Id', '=', $usuario->id)
                ->first()
                ->update(['SES_USR_Estado_Sesion' => 0]);
                
            return redirect()->route('form_activar_sesion');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Reactiva la sesión del usuario
     *
     */
    public function activar(Request $request)
    {
        if(session()->get('Usuario_Id') != null){
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));

            $correcta = Hash::check($request->password, $usuario->password, []);

            if($correcta) {
                SesionUsuario::where('SES_USR_Usuario_Id', '=', $usuario->id)
                    ->first()
                    ->update(['SES_USR_Estado_Sesion' => 1]);
                
                return redirect()->route('login');
            } else {
                return redirect()->route('form_activar_sesion')->withErrors('La contraseña es incorrecta.');
            }
        } else {
            $this->guard()->logout();
            $request->session()->invalidate();
            return $this->loggedOut($request) ?: redirect('/iniciar-sesion');
        }
    }

    /**
     * Reactiva la sesión del usuario
     *
     */
    public function estadoSesion()
    {
        if(session()->get('Usuario_Id') != null){
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));

            $estado = SesionUsuario::where('SES_USR_Usuario_Id', '=', $usuario->id)
                ->first()->SES_USR_Estado_Sesion;
            
            if($estado) {
                return response()->json(['estado' => true]);
            } else {
                return response()->json(['estado' => false]);
            }
        } else {
            return redirect()->route('login');
        }
    }
}