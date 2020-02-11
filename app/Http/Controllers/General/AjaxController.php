<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AjaxController extends Controller
{
    public function asignarSesion(Request $request){
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
