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

        return view(
            'administrador.director.listar',
            compact(
                'directoresActivos',
                'directoresInactivos',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Muestra el formulario para crear un nuevo director de proyectos
     *
     * @return \Illuminate\View\View Vista crear director
     */
    public function crear()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );
        
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::obtenerRolesNoCliente();

        return view(
            'administrador.director.crear',
            compact(
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
    public function guardar(ValidacionUsuario $request)
    {
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

        return redirect()
            ->route('crear_director_administrador')
            ->with(
                'mensaje',
                'Director de proyectos agregado con éxito, por favor que '.
                    $request['USR_Nombres_Usuario'].
                    ' '.
                    $request['USR_Apellidos_Usuario'].
                    ' revise su correo electrónico'
            );
    }

    /**
     * Muestra el formulario para editar el director de proyectos
     *
     * @param  $id Identificador del director de proyectos
     * @return \Illuminate\View\View Vista para editar director de proyectos
     */
    public function editar($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );
        
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $director = Usuarios::findOrFail($id);

        return view(
            'administrador.director.editar',
            compact(
                'director',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ValidacionUsuario  $request
     * @param  $id Identifcador del director de proyectos
     * @return redirect()->route()
     */
    public function actualizar(ValidacionUsuario $request, $id)
    {
        Usuarios::editarUsuario($request, $id);
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

        return redirect()
            ->route('directores_administrador')
            ->with('mensaje', 'Director de proyectos actualizado con éxito');
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
}
