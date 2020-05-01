<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use App\Models\Tablas\Roles;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use PDF;
use Exception;
use Illuminate\Database\QueryException;

/**
 * Perfil Operacion Controller, donde se mostrarán las
 * métricas del sistema para el administrador
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class PerfilOperacionController extends Controller
{
    /**
     * Muestra el listado del perfil de operación
     *
     * @return \Illuminate\View\View Vista del listado de perfil de operación
     */
    public function index()
    {
        can('listar-perfil-operacion');

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
        $perfilesOperacionActivos = Usuarios::obtenerPerfilOperacionActivos();
        $perfilesOperacionInactivos = Usuarios::obtenerPerfilOperacionInactivos();

        return view(
            'director.perfiloperacion.listar',
            compact(
                'perfilesOperacionActivos',
                'perfilesOperacionInactivos',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear perfil de operación
     *
     * @return \Illuminate\View\View Vista del formulario
     */
    public function crear()
    {
        can('crear-perfil-operacion');
        
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
        $roles = Roles::obtenerRolesPefilOperacion();
        
        return view(
            'director.perfiloperacion.crear',
            compact(
                'roles',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda el perfil de operación en la Base de Datos
     *
     * @param  App\Http\Requests\ValidacionUsuario  $request
     * @return redirect()->back()->with()
     */
    public function guardar(ValidacionUsuario $request)
    {
        Usuarios::crearUsuario($request);
        
        $perfil = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        UsuariosRoles::asignarRol($request['USR_RLS_Rol_Id'], $perfil->id);
        MenuUsuario::asignarMenuPerfilOperacion($perfil->id);
        PermisoUsuario::asignarPermisoPerfil($perfil->id);
        PermisoUsuario::asignarPermisosPerfilOperacion($perfil->id);
        Usuarios::enviarcorreo(
            $request,
            'Bienvenido(a) '.
                $request['USR_Nombres_Usuario'].
                ' '.
                $request['USR_Apellidos_Usuario'],
            'general.correo.bienvenida'
        );
        
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.
                ' '.
                $datos->USR_Apellidos_Usuario.
                ' ha creado el usuario '.
                $request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            ($datos->USR_Supervisor_Id == 0) ? 1 : $datos->USR_Supervisor_Id,
            'perfil_operacion', null, null,
            'person_add'
        );

        Notificaciones::crearNotificacion(
            'Hola! '.
                $request->USR_Nombres_Usuario.
                ' '.
                $request->USR_Apellidos_Usuario.
                ', Bienvenido a InkBrutalPRY, verifique sus datos.',
            session()->get('Usuario_Id'),
            $perfil->id,
            'perfil',
            null, null,
            'account_circle'
        );

        return redirect()
            ->back()
            ->with('mensaje', 'Perfil de Operación agregado con éxito');
    }

    /**
     * Muestra el formulario para editar el perfil de operación
     *
     * @param  $id  Identificador del perfil de operación
     * @return \Illuminate\View\View Vista del formulario
     */
    public function editar($id)
    {
        can('editar-perfil-operacion');
        
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
        $perfil = Usuarios::findOrFail($id);

        return view(
            'director.perfiloperacion.editar',
            compact(
                'perfil',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Actualiza los datos del perfil de operacion en la Base de Datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            ->route('perfil_operacion')
            ->with('mensaje', 'Perfi de operación  actualizado con éxito');
    }

    /**
     * Inactivar el perfil de operacion seleccionado
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
                
                UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)->update(['USR_RLS_Estado' => 0]);
                Notificaciones::crearNotificacion(
                    $datos->USR_Nombres_Usuario.
                        ' '.
                        $datos->USR_Apellidos_Usuario.
                        ' ha dejado inactivo al usuario '.
                        $datosU->USR_Nombres_Usuario,
                    session()->get('Usuario_Id'),
                    $datos->USR_Supervisor_Id,
                    'perfil_operacion',
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
     * Activar el perfil de operacion seleccionado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id Identificador del director de proyectos a eliminar
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::findOrFail($id);
        $usuario = Usuarios::findOrFail($id);
        
        if($usuario != null){
            
            if($datos->USR_Supervisor_Id == 0)
                $datos->USR_Supervisor_Id = 1;
                
            UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)->update(['USR_RLS_Estado' => 1]);
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha reactivado al usuario '.
                    $datosU->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'perfil_operacion',
                null,
                null,
                'arrow_downward'
            );

            return redirect()
                ->route('perfil_operacion')
                ->with('mensaje', 'Perfil de operación reactivado');
        }
    }

    /**
     * Deja inactivo el perfil de operación
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del perfil de operación
     * @return response()->json()
     */
    public function eliminar(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                Usuarios::findOrFail($id)->delete($id);
                return response()->json(['mensaje' => 'ok']);
            } catch (QueryException $e) {
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }

    /**
     * Activa el perfil de operación
     *
     * @param  $id Identificador del perfil de operación
     * @return response()->json()
     */
    public function agregar($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::findOrFail($id);
        $usuario = Usuarios::findOrFail($id);
        
        if($usuario != null){
            
            if($datos->USR_Supervisor_Id == 0)
                $datos->USR_Supervisor_Id = 1;
            
            UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
                ->update(['USR_RLS_Estado' => 1]);
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha dejado activo al usuario '.
                    $datosU->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'perfil_operacion',
                null,
                null,
                'arrow_upward'
            );
        }
        
        return redirect()
            ->route('perfil_operacion')
            ->with('mensaje', 'Perfil de operación reingresado con éxito');
    }

    /**
     * Muestra la vista con la carga de trabajo por Perfil de operación
     *
     * @param  $idA  Identificador de la actividad
     * @return \Illuminate\View\View Vista para aprobar la solicitud de tiempo
     */
    public function cargaTrabajo($id)
    {
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
        $actividades = Actividades::obtenerGenerales($id);
        $perfilOperacion = Usuarios::obtenerUsuarioById($id);
        $actividadesTotales = count(Actividades::obtenerTodasPerfilOperacion($id));
        $actividadesFinalizadas = count(Actividades::obtenerActividadesFinalizadasPerfil($id));
        $actividadesAtrasadas = count(Actividades::obtenerActividadesAtrasadasPerfil($id));
        $actividadesProceso = count(Actividades::obtenerActividadesProcesoPerfil($id));

        try {
            $porcentajeFinalizado = (int)(($actividadesFinalizadas/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeFinalizado = 0;
        }

        try {
            $porcentajeAtrasado = (int)(($actividadesAtrasadas/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeAtrasado = 0;
        }

        try {
            $porcentajeProceso = (int)(($actividadesProceso/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeProceso = 0;
        }

        return view(
            'director.perfiloperacion.carga.actividades',
            compact(
                'actividades',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas',
                'porcentajeFinalizado',
                'porcentajeAtrasado',
                'porcentajeProceso',
                'perfilOperacion'
            )
        );
    }

    /**
     * Genera el PDF con la carga de trabajo por Perfil de operación
     *
     * @param  $id  Identificador de la actividad
     * @return PDF->download()
     */
    public function pdfCargaTrabajo($id)
    {
        $actividades = Actividades::obtenerGenerales($id);
        $perfilOperacion = Usuarios::obtenerUsuarioById($id);

        $actividadesTotales = count(
            Actividades::obtenerTodasPerfilOperacion($id)
        );

        $actividadesFinalizadas = count(
            Actividades::obtenerActividadesFinalizadasPerfil($id)
        );

        $actividadesAtrasadas = count(
            Actividades::obtenerActividadesAtrasadasPerfil($id)
        );

        $actividadesProceso = count(
            Actividades::obtenerActividadesProcesoPerfil($id)
        );

        try {
            $porcentajeFinalizado = (int)(($actividadesFinalizadas/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeFinalizado = 0;
        }

        try {
            $porcentajeAtrasado = (int)(($actividadesAtrasadas/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeAtrasado = 0;
        }

        try {
            $porcentajeProceso = (int)(($actividadesProceso/$actividadesTotales)*100);
        }catch(Exception $ex) {
            $porcentajeProceso = 0;
        }
        
        $pdf = PDF::loadView(
            'includes.pdf.carga',
            compact(
                'actividades',
                'perfilOperacion',
                'porcentajeFinalizado',
                'porcentajeAtrasado',
                'porcentajeProceso'
            )
        );

        $fileName = 'CargaTrabajo-'.
            $perfilOperacion->USR_Nombres_Usuario.
            '-'.
            $perfilOperacion->USR_Apellidos_Usuario;
        
        return $pdf->download($fileName.'.pdf');
    }
}