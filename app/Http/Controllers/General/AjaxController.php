<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

/**
 * Ajax Controller, donde verificamos el rol elegido por
 * el usuario autenticado
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class AjaxController extends Controller
{
    /**
     * Dependiendo
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function asignarSesion(Request $request)
    {
        if($request->ajax()) {
            switch ($request->input('Rol_Id')) {
                case '1':
                    $ruta = '/administrador';
                    break;
                
                case '2':
                    $ruta = '/director';
                    break;

                case '3':
                    $ruta = '/cliente';
                    break;

                default:
                    switch ($request->input('Sub_Rol_Id')) {
                        case '4':
                            $ruta = '/perfil-operacion';
                            break;
                    }
            }

            Session::put([
                'Rol_Id' => $request->input('Rol_Id'),
                'Rol_Nombre' => $request->input('Rol_Nombre'),
                'Sub_Rol_Id' => $request->input('Sub_Rol_Id')
            ]);

            return response()->json(['ruta' => $ruta]);
        } else {
            abort(404);
        }
    }
}