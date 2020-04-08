<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use Illuminate\Http\Request;

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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $clientes = Usuarios::obtenerClientes($id);
        $empresa = Empresas::findOrFail($id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();

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
     * Muestra el formulario para crear un cliente
     *
     * @param  $id  Identificador de la empresa
     * @return \Illuminate\View\View Vista del formulario para crear clientes
     */
    public function crear($id)
    {
        can('crear-clientes');
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();
        
        return view(
            'clientes.crear',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'empresa',
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
    public function guardar(ValidacionUsuario $request)
    {
        Usuarios::crearUsuario($request);
        $cliente = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
        UsuariosRoles::asignarRol(3, $cliente->id);
        MenuUsuario::asignarMenuCliente($cliente->id);
        PermisoUsuario::asignarPermisoPerfil($cliente->id);
        PermisoUsuario::asignarPermisosCliente($cliente->id);
        Usuarios::enviarcorreo(
            $request,
            'Bienvenido(a) a InkBrutalPRY, Software de Gestión de Proyectos',
            'Bienvenido '.
                $request['USR_Nombres_Usuario'].
                ' '.
                $request['USR_Apellidos_Usuario'],
            'general.correo.bienvenida'
        );

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        if ($datos->USR_Supervisor_Id == 0)
            $datos->USR_Supervisor_Id = 1;
        
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
        
        return redirect()
            ->back()
            ->with('mensaje', 'Cliente agregado con exito');
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Usuarios::findOrFail($idC);
        $empresa = Empresas::findOrFail($idE);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();
        
        return view(
            'clientes.editar',
            compact(
                'cliente',
                'empresa',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Usuarios::editarUsuario($request, $idC);
        
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.
                ' '.
                $datos->USR_Apellidos_Usuario.
                ' ha actualizado los datos de '.
                $request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
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

        return redirect()
            ->route(
                'clientes', ['id'=>$idE]
            )->with('mensaje', 'Cliente actualizado con exito');
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
}