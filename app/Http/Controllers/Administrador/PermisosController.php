<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionPermiso;
use App\Models\Tablas\Iconos;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Permiso;
use App\Models\Tablas\PermisoUsuario;
use App\Models\Tablas\UsuariosRoles;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::orderBy('id')->where('id', '!=', 4)->pluck('RLS_Nombre_Rol', 'id')->toArray();
        $usuarios = Usuarios::with('roles')->get();
        $users = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->select('r.*', 'u.*')
            ->get();
        return view('administrador.permisos.listar', compact('usuarios', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('administrador.permisos.crear', compact('datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionPermiso $request)
    {
        Permiso::create($request->all());
        return redirect()->back()->with('mensaje', 'Permiso creado con exito');
    }

    public function asignarMenu($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $menuAsignado = DB::table('TBL_Menu as m')
            ->join('TBL_Menu_Usuario as mu', 'mu.MN_USR_Menu_Id', '=', 'm.id')
            ->where('mu.MN_USR_Usuario_Id', '=', $id)
            ->select('m.*')
            ->get();
        $menuNoAsignado = $this->menuNoAsignado($menuAsignado);
        
        $permisoAsignado = DB::table('TBL_Permiso as p')
            ->join('TBL_Permiso_Usuario as pu', 'pu.PRM_USR_Permiso_Id', '=', 'p.id')
            ->where('pu.PRM_USR_Usuario_Id', '=', $id)
            ->select('p.*')
            ->get();
        
        $permisoNoAsignado = $this->permisoNoAsignado($permisoAsignado);

        $rolesAsignados = DB::table('TBL_Roles as r')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Rol_Id', '=', 'r.id')
            ->where('ur.USR_RLS_Usuario_Id', '=', $id)
            ->select('r.*')
            ->get();
        $rolesNoAsignados = $this->rolesNoAsignados($rolesAsignados);
        
        return view('administrador.permisos.asignar', compact('id', 'menuAsignado', 'menuNoAsignado', 'permisoAsignado', 'permisoNoAsignado', 'rolesAsignados', 'rolesNoAsignados', 'datos', 'id', 'notificaciones', 'cantidad'));
    }

    public function menuNoAsignado($menuAsignado){
        $menus = Menu::get();
        $disponibles = [];
        foreach ($menus as $menu) {
            if(!$menuAsignado->contains('id', $menu->id)){
                array_push($disponibles, $menu);
            }
        }
        return $disponibles;
    }

    public function permisoNoAsignado($permisoAsignado){
        $permisos = Permiso::get();
        $disponibles = [];
        foreach ($permisos as $permiso) {
            if(!$permisoAsignado->contains('id', $permiso->id)){
                array_push($disponibles, $permiso);
            }
        }
        return $disponibles;
    }

    public function rolesNoAsignados($rolesAsignados){
        $roles = Roles::where('id', '!=', 4)->get();
        $disponibles = [];
        foreach ($roles as $rol) {
            if(!$rolesAsignados->contains('id', $rol->id)){
                array_push($disponibles, $rol);
            }
        }
        return $disponibles;
    }

    public function agregar(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $id)
                ->where('MN_USR_Menu_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                MenuUsuario::create([
                    'MN_USR_Usuario_Id' => $request->id,
                    'MN_USR_Menu_Id' => $request->menuId
                ]);
                return response()->json(['mensaje' => 'okMA']);
            } else {
                return response()->json(['mensaje' => 'ngMA']);
            }
        }
    }

    public function quitar(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $id)
                ->where('MN_USR_Menu_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                return response()->json(['mensaje' => 'ngMD']);
            } else {
                $asignado->delete();
                return response()->json(['mensaje' => 'okMD']);
            }
        }
    }

    public function agregarPermiso(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $id)
                ->where('PRM_USR_Permiso_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                PermisoUsuario::create([
                    'PRM_USR_Usuario_Id' => $id,
                    'PRM_USR_Permiso_Id' => $menuId
                ]);
                return response()->json(['mensaje' => 'okPA']);
            } else {
                return response()->json(['mensaje' => 'ngPA']);
            }
        }
    }

    public function quitarPermiso(Request $request, $id, $menuId)
    {
        if ($request->ajax()) {
            $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $id)
                ->where('PRM_USR_Permiso_Id', '=', $menuId)
                ->first();
            if (!$asignado) {
                return response()->json(['mensaje' => 'ngPD']);
            } else {
                $asignado->delete();
                return response()->json(['mensaje' => 'okPD']);
            }
        }
    }

    public function agregarRol(Request $request, $id, $rolId)
    {
        if ($request->ajax()) {
            $asignado = UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
                ->where('USR_RLS_Rol_Id', '=', $rolId)
                ->first();
            if (!$asignado) {
                UsuariosRoles::create([
                    'USR_RLS_Usuario_Id' => $id,
                    'USR_RLS_Rol_Id' => $rolId,
                    'USR_RLS_Estado' => 1
                ]);
                return response()->json(['mensaje' => 'okRA']);
            } else {
                return response()->json(['mensaje' => 'ngRA']);
            }
        }
    }

    public function quitarRol(Request $request, $id, $rolId)
    {
        if ($request->ajax()) {
            $asignado = UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
                ->where('USR_RLS_Rol_Id', '=', $rolId)
                ->first();
            if (!$asignado) {
                return response()->json(['mensaje' => 'ngRD']);
            } else {
                $asignado->delete();
                return response()->json(['mensaje' => 'okRD']);
            }
        }
    }
}
