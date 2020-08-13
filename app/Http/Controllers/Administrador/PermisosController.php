<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionPermiso;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Permiso;
use App\Models\Tablas\PermisoUsuario;
use App\Models\Tablas\UsuariosRoles;

/**
 * Permisos Controller, donde se habilitarán o dehabilitarán los permisos a los usuarios
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class PermisosController extends Controller
{
    /**
     * Muestra el listado de los usuarios actuales del sistema
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

        $datos = Usuarios::findOrFail(
            session()->get('Usuario_Id')
        );

        $roles = Roles::orderBy('id')
            ->where('id', '!=', 4)
            ->pluck('RLS_Nombre_Rol', 'id')
            ->toArray();
        $usuarios = Usuarios::with('roles')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'TBL_Usuarios.id')
            ->where('ur.USR_RLS_Estado', '=', 1)
            ->groupBy('TBL_Usuarios.id')
            ->select('TBL_Usuarios.*')
            ->with('roles')
            ->get();
        
        return view(
            'administrador.permisos.listar',
            compact(
                'usuarios',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Muestra el formulario para crear permisos
     *
     * @return \Illuminate\View\View Vista de crear permiso
     */
    public function crear()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Usuarios::findOrFail(
            session()->get('Usuario_Id')
        );
        
        return view(
            'administrador.permisos.crear',
            compact(
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Guarda los datos del permiso en la Base de Datos
     *
     * @param  App\Http\Requests\ValidacionPermiso  $request
     * @return redirect()->back()->with()
     */
    public function guardar(ValidacionPermiso $request)
    {
        Permiso::crear($request);
        
        return redirect()
            ->route('crear_permiso_administrador')
            ->with('mensaje', 'Permiso creado con éxito');
    }

    /**
     * Muestra la vista de asignación de permisos
     *
     * @param  $id Identificador del usuario a asignar el permiso
     * @return \Illuminate\View\View Vista para asignar permisos
     */
    public function asignarMenu($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Usuarios::findOrFail(
            session()->get('Usuario_Id')
        );

        $usuario = Usuarios::findOrFail($id);

        $menuAsignado = Menu::obtenerItemsAsignados($id);
        $menuNoAsignado = $this->menuNoAsignado($menuAsignado);
        
        $permisoAsignado = Permiso::obtenerPermisosAsignados($id);
        $permisoNoAsignado = $this->permisoNoAsignado($permisoAsignado);

        $rolesAsignados = Roles::obtenerRolesAsignados($id);
        $rolesNoAsignados = $this->rolesNoAsignados($rolesAsignados);
        
        return view(
            'administrador.permisos.asignar',
            compact(
                'menuAsignado',
                'menuNoAsignado',
                'permisoAsignado',
                'permisoNoAsignado',
                'rolesAsignados',
                'rolesNoAsignados',
                'usuario',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Función que obtiene los items no asignados
     *
     * @param  $menuAsignado  Lista con los items asignados para hacer el filtro
     * @return $disponibles  Items no asignados
     */
    public function menuNoAsignado($menuAsignado)
    {
        $menus = Menu::get();
        $disponibles = [];
        foreach ($menus as $menu) {
            if(!$menuAsignado->contains('id', $menu->id)) {
                array_push($disponibles, $menu);
            }
        }
        return $disponibles;
    }

    /**
     * Función que obtiene los permisos no asignados
     *
     * @param  $permisoAsignado  Lista con los permisos asignados para hacer el filtro
     * @return $disponibles  Permisos no asignados
     */
    public function permisoNoAsignado($permisoAsignado)
    {
        $permisos = Permiso::get();
        $disponibles = [];
        foreach ($permisos as $permiso) {
            if(!$permisoAsignado->contains('id', $permiso->id)) {
                array_push($disponibles, $permiso);
            }
        }

        return $disponibles;
    }

    /**
     * Función que obtiene los roles no asignados
     *
     * @param  $rolesAsignado  Lista con los roles asignados para hacer el filtro
     * @return $disponibles  Roles no asignados
     */
    public function rolesNoAsignados($rolesAsignados)
    {
        $roles = Roles::where('id', '!=', 4)
            ->where('RLS_Nombre_Rol', '<>', 'Cliente')
            ->get();
        
        $disponibles = [];

        foreach ($roles as $rol) {
            if(!$rolesAsignados->contains('id', $rol->id)) {
                array_push($disponibles, $rol);
            }
        }

        return $disponibles;
    }

    /**
     * Asigna el item del menú
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $menuId Identificador del item
     * 
     * @return response()->json()
     */
    public function agregar(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $id)
                ->where('MN_USR_Menu_Id', '=', $menuId)
                ->first();
            
            if (!$asignado) {
                MenuUsuario::asignar($request);

                return response()
                    ->json(['mensaje' => 'okMA']);
            } else {
                return response()
                    ->json(['mensaje' => 'ngMA']);
            }
        }
    }

    /**
     * Des-asigna el item del menú
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $menuId Identificador del item
     * 
     * @return response()->json()
     */
    public function quitar(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $id)
                ->where('MN_USR_Menu_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                return response()
                    ->json(['mensaje' => 'ngMD']);
            } else {
                MenuUsuario::desasignar($asignado);

                return response()
                    ->json(['mensaje' => 'okMD']);
            }
        }
    }

    /**
     * Asigna el permiso
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $menuId Identificador del permiso
     * 
     * @return response()->json()
     */
    public function agregarPermiso(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $id)
                ->where('PRM_USR_Permiso_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                PermisoUsuario::asignar($id, $menuId);

                return response()
                    ->json(['mensaje' => 'okPA']);
            } else {
                return response()
                    ->json(['mensaje' => 'ngPA']);
            }
        }
    }

    /**
     * Des-asigna el permiso
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $menuId Identificador del permiso
     * 
     * @return response()->json()
     */
    public function quitarPermiso(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $id)
                ->where('PRM_USR_Permiso_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                return response()
                    ->json(['mensaje' => 'ngPD']);
            } else {
                PermisoUsuario::desasignar($asignado);

                return response()
                    ->json(['mensaje' => 'okPD']);
            }
        }
    }

    /**
     * Asigna el rol
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $rolId Identificador del rol
     * 
     * @return response()->json()
     */
    public function agregarRol(Request $request, $id, $rolId)
    {
        if ($request->ajax()) {
            $asignado = UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
                ->where('USR_RLS_Rol_Id', '=', $rolId)
                ->first();
            
            if (!$asignado) {
                UsuariosRoles::asignarRol($rolId, $id);

                return response()
                    ->json(['mensaje' => 'okRA']);
            } else {
                return response()
                    ->json(['mensaje' => 'ngRA']);
            }
        }
    }

    /**
     * Des-asigna el rol
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Identificador del usuario
     * @param  $rolId Identificador del rol
     * 
     * @return response()->json()
     */
    public function quitarRol(Request $request, $id, $rolId)
    {
        if ($request->ajax()) {
            $asignado = UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
                ->where('USR_RLS_Rol_Id', '=', $rolId)
                ->first();
            if (!$asignado) {
                return response()
                    ->json(['mensaje' => 'ngRD']);
            } else {
                UsuariosRoles::desasignar($asignado);

                return response()
                    ->json(['mensaje' => 'okRD']);
            }
        }
    }
}
