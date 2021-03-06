<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionRol;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use Illuminate\Database\QueryException;

/**
 * Roles Controller, donde se harán las distintas operaciones sobre la Tabla Roles
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class RolesController extends Controller
{
    /**
     * Muestra la vista con el listado de los roles del sistema
     *
     * @return \Illuminate\View\View Vista del listado de roles
     */
    public function index()
    {
        can('listar-roles');
        
        $permisos = [
            'crear'=> can2('crear-roles'),
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
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
        $roles = Roles::where('id', '<>', '4')->orderBy('id')->get();

        return view(
            'roles.listar',
            compact(
                'roles',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Guarda los roles
     *
     * @param  App\Http\Requests\ValidacionRol  $request
     * @return return redirect()->back()->with()
     */
    public function guardar(ValidacionRol $request)
    {
        can('crear-roles');

        $permisos = [
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
        ];

        $roles = Roles::where('RLS_Nombre_Rol', '=', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->first();
        
        if ($roles) {
            return response()->json(['mensaje' => 'dr']);
        }
        
        $rol = Roles::create([
            'RLS_Rol_Id' => 4,
            'RLS_Nombre_Rol' => $request->RLS_Nombre_Rol,
            'RLS_Descripcion_Rol' => $request->RLS_Descripcion_Rol,
            'RLS_Empresa_Id' => session()->get('Empresa_Id')
        ]);
        
        return response()->json(['rol' => $rol, 'permisos' => $permisos, 'mensaje' => 'ok']);
    }

    /**
     * Muestra el formulario para editar un rol
     *
     * @param  $id Identificador del rol
     * @return \Illuminate\View\View Vista del formulario de editar roles
     */
    public function editar($id)
    {
        can('editar-roles');
        
        $rol = Roles::findOrFail($id);
        
        if ($rol->RLS_Rol_Id != 4) {
            return response()->json(['mensaje' => 'rd']);
        }

        return response()->json(['rol' => $rol]);
    }

    /**
     * Actualiza los datos del rol en la Base de Datos
     *
     * @param  App\Http\Requests\ValidacionRol  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionRol $request, $id)
    {
        can('editar-roles');

        $permisos = [
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
        ];
        
        $roles = Roles::where('RLS_Nombre_Rol', '<>', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->get();
        foreach ($roles as $rol) {
            if ($rol->RLS_Nombre_Rol==$request->RLS_Nombre_Rol) {
                return response()->json(['mensaje' => 'dr']);
            }
        }
        $rol = Roles::findOrFail($id);
        $rol->update($request->all());
        
        return response()->json(['rol' => $rol, 'permisos' => $permisos, 'mensaje' => 'ok']);
    }

    /**
     * Elimina el rol seleccionado
     *
     * @param  $id Identificador del rol
     * @param  \Illuminate\Http\Request  $request
     * @return response()->json()
     */
    public function eliminar(Request $request, $id)
    {
        if (!can('eliminar-roles')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                $rol = Roles::findOrFail($id);
                if ($rol->RLS_Rol_Id != 4 || $rol->id == 4) {
                    return response()->json(['mensaje' => 'rd']);
                } else {
                    try {
                        Roles::destroy($id);
                        return response()->json(['mensaje' => 'ok']);
                    } catch (QueryException $e) {
                        return response()->json(['mensaje' => 'ng']);
                    }
                }
            }
        }
    }
}