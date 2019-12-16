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
        $usuarios = DB::table('TBL_Usuarios as u')
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
        $menus = Menu::where('MN_Nombre_Menu', 'not like', '%inicio%')
            ->where('MN_Nombre_Menu', 'not like', '%director%')
            ->where('MN_Nombre_Menu', 'not like', '%operacion%')
            ->where('MN_Nombre_Menu', 'not like', '%permisos%')
            ->where('MN_Nombre_Menu', 'not like', '%menu%')->get();
        //Menu::orderBy('MN_Orden_Menu')->get();
        $permisos = Permiso::where('PRM_Nombre_Permiso', 'not like', '%perfil%')->orderBy('PRM_Nombre_Permiso')->get();
        return view('administrador.permisos.asignar', compact('menus', 'permisos', 'datos', 'id', 'notificaciones', 'cantidad'));
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
}
