<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

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

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
        return view(
            'perfil.editar',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
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
        can('editar-perfil');
        $validator = Validator::make($request->all(), [
            'USR_Foto_Perfil_Usuario' =>
                'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {
            $archivo_Imagen = $request->USR_Foto_Perfil_Usuario;
            $imagen = Image::make($archivo_Imagen);
            $imagen->resize(128, 128, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Response::make($imagen->encode('jpeg', 50));

            Usuarios::findOrFail(session()->get('Usuario_Id'))
                ->update(['USR_Foto_Perfil_Usuario' => $imagen]);
            
            return response()
                ->json(['success' => 'Foto Actualizada']);
        }

        return response()
            ->json(['error' => $validator->errors()->all()]);
    }

    public function obtener_foto()
    {
        $image = Usuarios::findOrFail(session()->get('Usuario_Id'))->USR_Foto_Perfil_Usuario;
        $archivo_Imagen = Image::make($image);

        $response = Response::make($archivo_Imagen->encode('jpeg'));
        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }

    /**
     * Actualiza los datos del perfil del usuario autenticado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->back()->with()
     */
    public function actualizarDatos(Request $request)
    {
        can('editar-perfil');

        $usuarios = Usuarios::where('USR_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->where('id', '<>', session()->get('Usuario_Id'))
            ->get();
        
        foreach ($usuarios as $usuario) {
            
            if($usuario->USR_Documento_Usuario == $request->USR_Documento_Usuario){
                return redirect()
                    ->route('perfil')
                    ->withErrors('El Documento ya se encuentra en uso.');
            }
            
            if($usuario->USR_Correo_Usuario == $request->USR_Correo_Usuario){
                return redirect()
                    ->route('perfil')
                    ->withErrors('El correo electrónico ya se encuentra en uso.');
            }
        }

        Usuarios::findOrFail(session()->get('Usuario_Id'))
            ->update($request->all());
        
        return redirect()
            ->route('perfil')
            ->with('mensaje', 'Datos actualizados con éxito');
    }

    /**
     * Actualiza la contraseña del usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->back()->with()
     */
    public function actualizarClave(Request $request)
    {
        can('editar-perfil');
        
        if($request->USR_Clave_Nueva != $request->USR_Clave_Confirmar){
            return redirect()->route('perfil')->withErrors('Las contraseñas no coinciden.');
        }

        $clave = Usuarios::select('password')
            ->where('id', '=', session()->get('Usuario_Id'))
            ->first();
        
        $correcta = Hash::check($request->USR_Clave_Anterior, $clave->password, []);
        
        if (!$correcta) {
            return redirect()
                ->route('perfil')
                ->withErrors('La contraseña antigua es incorrecta.');
        }

        Usuarios::findOrFail(session()->get('Usuario_Id'))->update([
            'password' => bcrypt($request->USR_Clave_Nueva)
        ]);
        
        return redirect()
            ->route('perfil')
            ->with('mensaje', 'Contraseña actualizada.');
    }
}