<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Tablas\Notificaciones;

/**
 * Perfil Usuario Controller, donde se mostrarán los datos
 * del usuario autenticado y se realizaran las distintas
 * actualizaciones del perfil
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class PerfilUsuarioController extends Controller
{
    /**
     * Muestra la vista para la edición del perfil de usuario
     *
     * @return \Illuminate\View\View Vista del perfil de usuario
     */
    public function index()
    {
        can('editar-perfil');

        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        return view(
            'perfil.editar',
            compact(
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Actualiza la foto de perfil del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response()->json()
     */
    public function actualizarFoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'USR_Foto_Perfil_Usuario' =>
                'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $ruta = public_path("assets/bsb/images/".$usuario->USR_Foto_Perfil_Usuario);
            if ($usuario->USR_Foto_Perfil_Usuario != null) {
                unlink($ruta);
            }
            $input = $request->all();
            $nombreArchivo = $input['USR_Foto_Perfil_Usuario'] = time().
                '.'.
                $request->USR_Foto_Perfil_Usuario->getClientOriginalExtension();
            $request->USR_Foto_Perfil_Usuario->move(
                public_path('assets/bsb/images'),
                $input['USR_Foto_Perfil_Usuario']
            );
            Usuarios::findOrFail(session()->get('Usuario_Id'))
                ->update(['USR_Foto_Perfil_Usuario' => $nombreArchivo]);
            
            return response()
                ->json(['success' => 'Foto Actualizada']);
        }

        return response()
            ->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Actualiza los datos del perfil del usuario autenticado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->back()->with()
     */
    public function actualizarDatos(Request $request)
    {
        $usuarios = Usuarios::where('USR_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->where('id', '<>', session()->get('Usuario_Id'))
            ->get();
        foreach ($usuarios as $usuario) {
            if($usuario->USR_Documento_Usuario == $request->USR_Documento_Usuario){
                return redirect()
                    ->back()
                    ->withErrors('El Documento ya se encuentra en uso.');
            }
            if($usuario->USR_Correo_Usuario == $request->USR_Correo_Usuario){
                return redirect()
                    ->back()
                    ->withErrors('El correo electrónico ya se encuentra en uso.');
            }
        }
        Usuarios::findOrFail(session()->get('Usuario_Id'))
            ->update($request->all());
        
        return redirect()
            ->back()
            ->with('mensaje', 'Datos actualizados con exito');
    }

    /**
     * Actualiza la contraseña del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->back()->with()
     */
    public function actualizarClave(Request $request)
    {
        if($request->USR_Clave_Nueva != $request->USR_Clave_Confirmar){
            return redirect()->back()->withErrors('Las contraseñas no coinciden.');
        }
        $clave = Usuarios::select('password')
            ->where('id', '=', session()->get('Usuario_Id'))
            ->first();
        $correcta = Hash::check($request->USR_Clave_Anterior, $clave->password, []);
        if (!$correcta) {
            return redirect()
                ->back()
                ->withErrors('La contraseña antigua es incorrecta.');
        }
        Usuarios::findOrFail(session()->get('Usuario_Id'))->update([
            'password' => bcrypt($request->USR_Clave_Nueva)
        ]);
        
        return redirect()
            ->back()
            ->with('mensaje', 'Contraseña actualizada.');
    }
}
