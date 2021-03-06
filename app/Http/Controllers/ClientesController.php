<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionCliente;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Clientes Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los clientes
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class ClientesController extends Controller
{
    /**
     * Muestra el listado de clientes de la empresa seleccionada
     *
     * @param  $id  Identificador de la empresa
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index($id)
    {
        can('listar-clientes');
        
        $permisos = [
            'crear'=> can2('crear-clientes'),
            'editar'=>can2('editar-clientes'),
            'eliminar'=>can2('eliminar-clientes')
        ];

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
        
        $clientes = Usuarios::obtenerClientes($id);
        $empresa = Empresas::findOrFail($id);

        return view(
            'clientes.listar',
            compact(
                'clientes',
                'empresa',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Guarda los datos del cliente en la base de datos
     *
     * @param  App\Http\Requests\ValidacionUsuario $request
     * @return redirect()->back()->with()
     */
    public function guardar(Request $request)
    {
        can('crear-clientes');
        
        $permisos = [
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
        ];        

        $data = $request->all();
        $validacionUsuario = new ValidacionCliente();
        $validator = Validator::make($data, $validacionUsuario->rules(null), $validacionUsuario->messages());

        if($validator->passes()){
            $request['USR_Fecha_Nacimiento_Usuario'] = ($request['USR_Fecha_Nacimiento_Usuario'] == null) ? "" : $request['USR_Fecha_Nacimiento_Usuario'];
            $request['USR_Direccion_Residencia_Usuario'] = ($request['USR_Direccion_Residencia_Usuario'] == null) ? "" : $request['USR_Direccion_Residencia_Usuario'];
            $request['USR_Ciudad_Residencia_Usuario'] = ($request['USR_Ciudad_Residencia_Usuario'] == null) ? "" : $request['USR_Ciudad_Residencia_Usuario'];

            Usuarios::crearUsuario($request);
            
            $cliente = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
            
            UsuariosRoles::asignarRol(3, $cliente->id);
            MenuUsuario::asignarMenuCliente($cliente->id);
            PermisoUsuario::asignarPermisoPerfil($cliente->id);
            PermisoUsuario::asignarPermisosCliente($cliente->id);
            
            Usuarios::enviarcorreo(
                $request,
                'Bienvenido '.
                    $request['USR_Nombres_Usuario'].
                    ' '.
                    $request['USR_Apellidos_Usuario'],
                'general.correo.bienvenida'
            );

            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            
            ($datos->USR_Supervisor_Id == 0) ? $datos->USR_Supervisor_Id = 1 : $datos->USR_Supervisor_Id = $datos->USR_Supervisor_Id;
            
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha creado el usuario '.
                    $request->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'clientes',
                'id',
                $request->id,
                'person_add'
            );

            Notificaciones::crearNotificacion(
                'Hola! '.
                    $request->USR_Nombres_Usuario.
                    ' '.
                    $request->USR_Apellidos_Usuario.
                    ', Bienvenido(a) a InkBrutalPRY, revise sus datos.',
                session()->get('Usuario_Id'),
                $cliente->id,
                'perfil',
                null,
                null,
                'account_circle'
            );
            
            return response()->json(['usuario' => $cliente, 'permisos' => $permisos, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Muestra el formulario para editar el cliente
     *
     * @param  $idC  Identificador del cliente
     * @param  $idE  Identificador de la empresa
     * @return \Illuminate\View\View Vista del formulario para editar clientes
     */
    public function editar($idC, $idE)
    {
        can('editar-clientes');

        $cliente = Usuarios::findOrFail($idC);

        return response()->json(['cliente' => $cliente]);
    }

    /**
     * Actualiza los datos del cliente en la Base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idC Identifcador del cliente
     * @param  $idE Identifcador de la empresa
     * @return redirect()->route()->with()
     */
    public function actualizar(Request $request, $idC, $idE)
    {
        can('editar-clientes');

        $permisos = [
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
        ];

        $data = $request->all();
        $validacionUsuario = new ValidacionCliente();
        $validator = Validator::make($data, $validacionUsuario->rules($idC), $validacionUsuario->messages());

        if($validator->passes()){
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            Usuarios::editarUsuario($request, $idC);

            $cliente = Usuarios::findOrFail($idC);

            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha actualizado los datos de '.
                    $request->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                ($datos->USR_Supervisor_Id == 0) ? 1 : $datos->USR_Supervisor_Id,
                null,
                null,
                null,
                'update'
            );
    
            Notificaciones::crearNotificacion(
                $request->USR_Nombres_Usuario.
                    ' '.
                    $request->USR_Apellidos_Usuario.
                    ', susdatos fueron actualizados',
                session()->get('Usuario_Id'),
                $idC,
                'perfil',
                null,
                null,
                'update'
            );

            return response()->json(['usuario' => $cliente, 'permisos' => $permisos, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Elimina el cliente de la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador del cliente
     * @return response()->json()
     */
    public function eliminar(Request $request, $id)
    {
        if (!can('eliminar-clientes')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
                    $datosU = Usuarios::findOrFail($id);
                    Usuarios::destroy($id);

                    Notificaciones::crearNotificacion(
                        $datos->USR_Nombres_Usuario.
                            ' '.
                            $datos->USR_Apellidos_Usuario.
                            ' ha eliminado al usuario '.
                            $datosU->USR_Nombres_Usuario,
                        session()->get('Usuario_Id'),
                        $datos->USR_Supervisor_Id,
                        null,
                        null,
                        null,
                        'delete_forever'
                    );
                    return response()->json(['mensaje' => 'ok']);
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
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

                /*Usuarios::enviarcorreo(
                    $usuario,
                    'Contraseña restaurada por el administrador!, '.
                        $usuario['USR_Nombres_Usuario'].
                        ' '.
                        $usuario['USR_Apellidos_Usuario'],
                    'general.correo.reset'
                );*/

                return response()->json(['mensaje' => 'ok']);
            } catch (QueryException $e) {
                return response()->json(['mensaje' => 'error']);
            }
        }
    }
}