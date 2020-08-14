<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use App\Models\Tablas\Roles;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

/**
 * Director Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los directores de protyectos
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class DirectorProyectosController extends Controller
{
    /**
     * Muestra el listado de directores de proyectos registrados
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

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $directoresActivos = Usuarios::obtenerDirectoresActivos();
        $directoresInactivos = Usuarios::obtenerDirectoresInactivos();

        $roles = Roles::obtenerRolesNoCliente();

        return view(
            'administrador.director.listar',
            compact(
                'directoresActivos',
                'directoresInactivos',
                'datos',
                'notificaciones',
                'cantidad',
                'roles'
            )
        );
    }

    /**
     * Guarda en la base de datos el nuevo director de proyectos
     *
     * @param  App\Http\Requests\ValidacionUsuario $request
     * @return redirect()->back()->with()
     */
    public function guardar(Request $request)
    {
        $data = $request->all();
        $validacionUsuario = new ValidacionUsuario();
        $validator = Validator::make($data, $validacionUsuario->rules(null), $validacionUsuario->messages());

        if($validator->passes()){
            $request['USR_Fecha_Nacimiento_Usuario'] = ($request['USR_Fecha_Nacimiento_Usuario'] == null) ? "" : $request['USR_Fecha_Nacimiento_Usuario'];
            $request['USR_Direccion_Residencia_Usuario'] = ($request['USR_Direccion_Residencia_Usuario'] == null) ? "" : $request['USR_Direccion_Residencia_Usuario'];
            $request['USR_Ciudad_Residencia_Usuario'] = ($request['USR_Ciudad_Residencia_Usuario'] == null) ? "" : $request['USR_Ciudad_Residencia_Usuario'];

            Usuarios::crearUsuario($request);
            $director = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
            UsuariosRoles::asignarRol(2, $director->id);
            MenuUsuario::asignarMenuDirector($director->id);
            PermisoUsuario::asignarPermisosDirector($director->id);
            
            Usuarios::enviarcorreo(
                $request,
                'Bienvenido(a) '.
                    $request['USR_Nombres_Usuario'].
                    ' '.
                    $request['USR_Apellidos_Usuario'],
                'general.correo.bienvenida'
            );

            Notificaciones::crearNotificacion(
                'Hola! '.
                    $request->USR_Nombres_Usuario.
                    ' '.
                    $request->USR_Apellidos_Usuario.
                    ', Bienvenido(a) a InkBrutalPRY, verifique sus datos.',
                session()->get('Usuario_Id'),
                $director->id,
                'perfil',
                null,
                null,
                'account_circle'
            );

            return response()->json(['usuario' => $director, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Muestra el formulario para editar el director de proyectos
     *
     * @param  $id Identificador del director de proyectos
     * @return \Illuminate\View\View Vista para editar director de proyectos
     */
    public function editar($id)
    {
        
        $director = Usuarios::findOrFail($id);

        return response()->json(['director' => $director]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ValidacionUsuario  $request
     * @param  $id Identifcador del director de proyectos
     * @return redirect()->route()
     */
    public function actualizar(Request $request, $id)
    {
        $data = $request->all();
        $validacionUsuario = new ValidacionUsuario();
        $validator = Validator::make($data, $validacionUsuario->rules($id), $validacionUsuario->messages());

        if($validator->passes()){
            $request['USR_Fecha_Nacimiento_Usuario'] = ($request['USR_Fecha_Nacimiento_Usuario'] == null) ? "" : $request['USR_Fecha_Nacimiento_Usuario'];
            $request['USR_Direccion_Residencia_Usuario'] = ($request['USR_Direccion_Residencia_Usuario'] == null) ? "" : $request['USR_Direccion_Residencia_Usuario'];
            
            Usuarios::editarUsuario($request, $id);
            
            $usuario = Usuarios::findOrFail($id);
            
            Notificaciones::crearNotificacion(
                $request->USR_Nombres_Usuario.
                    ' '.
                    $request->USR_Apellidos_Usuario.
                    ', sus datos fueron actualizados',
                session()->get('Usuario_Id'),
                $id,
                'perfil',
                null,
                null,
                'update'
            );

            return response()->json(['usuario' => $usuario, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Inactivar el director de proyectos seleccionado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id Identificador del director de proyectos a eliminar
     * @return \Illuminate\Http\Response
     */
    public function inactivar(Request $request, $id)
    {
        if ($request->ajax()) {
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $datosU = Usuarios::findOrFail($id);
            $usuario = Usuarios::findOrFail($id);
            
            if($usuario != null){
                
                if($datos->USR_Supervisor_Id == 0)
                    $datos->USR_Supervisor_Id = 1;
                
                UsuariosRoles::inactivarUsuario($id, 2);
                Notificaciones::crearNotificacion(
                    $datos->USR_Nombres_Usuario.
                        ' '.
                        $datos->USR_Apellidos_Usuario.
                        ' ha dejado inactivo al usuario '.
                        $datosU->USR_Nombres_Usuario,
                    session()->get('Usuario_Id'),
                    $datos->USR_Supervisor_Id,
                    'directores_administrador',
                    null,
                    null,
                    'arrow_downward'
                );

                return response()->json(['mensaje' => 'ok']);
            }else{
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }

    /**
     * Activar el director de proyectos seleccionado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id Identificador del director de proyectos a eliminar
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request, $id)
    {
        if ($request->ajax()) {
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $datosU = Usuarios::findOrFail($id);
            $usuario = Usuarios::findOrFail($id);
            
            if($usuario != null){
                
                if($datos->USR_Supervisor_Id == 0)
                    $datos->USR_Supervisor_Id = 1;
                
                UsuariosRoles::activarUsuario($id, 2);
                Notificaciones::crearNotificacion(
                    $datos->USR_Nombres_Usuario.
                        ' '.
                        $datos->USR_Apellidos_Usuario.
                        ' ha reactivado al usuario '.
                        $datosU->USR_Nombres_Usuario,
                    session()->get('Usuario_Id'),
                    $datos->USR_Supervisor_Id,
                    'directores_administrador',
                    null,
                    null,
                    'arrow_downward'
                );

                return response()->json(['mensaje' => 'ok']);
            }else{
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }

    /**
     * Elimina el director de proyectos seleccionado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id Identificador del director de proyectos a eliminar
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                Usuarios::findOrFail($id)->destroy();
                return response()->json(['mensaje' => 'ok']);
            } catch (QueryException $e) {
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }

    /**
     * Reinicia la contraseña del usuario
     *
     * @param $id Identificador del director de proyectos a eliminar
     * @return \Illuminate\Http\Response
     */
    public function recuperar_contraseña(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $usuario = Usuarios::findOrFail($id);
                $usuario->update(['password' => bcrypt($usuario->USR_Nombre_Usuario)]);

                Usuarios::enviarcorreo(
                    $usuario,
                    'Contraseña restaurada por el administrador!, '.
                        $usuario['USR_Nombres_Usuario'].
                        ' '.
                        $usuario['USR_Apellidos_Usuario'],
                    'general.correo.reset'
                );

                return response()->json(['mensaje' => 'ok']);
            } catch (QueryException $e) {
                return response()->json(['mensaje' => 'error']);
            }
        }
    }
}
