<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\Notificaciones;

/**
 * Empresa Controller, donde se operan los datos de la empresa.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class EmpresaController extends Controller
{
    /**
     * Muestra los datos de la empresa
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Empresas::obtenerDatosEmpresa(
            session()->get('Usuario_Id')
        );

        return view(
            'administrador.empresa.editar',
            compact(
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Actualiza el logo de la empresa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response()->json()
     */
    public function actualizarLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'EMP_Logo_Empresa' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if ($validator->passes()) {
            $usuario = Empresas::obtenerDatosEmpresa(session()->get('Usuario_Id'));
            $empresa = Empresas::findOrFail($usuario->USR_Empresa_Id);
            
            if ($empresa->EMP_Logo_Empresa != null) {
                $ruta = public_path("assets/bsb/images/Logos/".$empresa->EMP_Logo_Empresa);
                unlink($ruta);
            }

            $input = $request->all();
            $nombreArchivo = $input['EMP_Logo_Empresa'] = time().
                '.'.
                $request->EMP_Logo_Empresa->getClientOriginalExtension();
            
            $request->EMP_Logo_Empresa->move(
                public_path('assets/bsb/images/Logos'),
                $input['EMP_Logo_Empresa']
            );
            
            Empresas::cambiarLogo($usuario, $nombreArchivo);
            
            return response()->json(['success' => 'Logo actualizado']);
        }

        return response()
            ->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Actualiza los datos de la empresa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response()->json()
     */
    public function actualizarDatos(Request $request)
    {
        Empresas::actualizar($request);
        
        return redirect()
            ->back()
            ->with('mensaje', 'Datos actualizados con exito');
    }
}
