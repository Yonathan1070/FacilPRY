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
            ->where('r.RLS_Nombre_Rol', '<>', 'Cliente')
            ->where('e.id', '=', session()->get('Empresa_Id'))
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
    
    public function asignarMenu($id){
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

    public function agregar($idU, $idM)
    {
        $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $idU)
            ->where('MN_USR_Menu_Id', '=', $idM)
            ->first();
        if (!$asignado) {
            MenuUsuario::create([
                'MN_USR_Usuario_Id' => $idU,
                'MN_USR_Menu_Id' => $idM
            ]);
            return redirect()->back()->with('mensaje', 'Menú Asignado.');
        }else{
            return redirect()->back()->with('mensaje', 'El Menú se encuentra Asignado.');
        }
    }

    public function quitar($idU, $idM)
    {
        $asignado = MenuUsuario::where('MN_USR_Usuario_Id', '=', $idU)
            ->where('MN_USR_Menu_Id', '=', $idM)
            ->first();
        if (!$asignado) {
            return redirect()->back()->withErrors('El Menú no se encuentra asignado');
        }else{
            $asignado->delete();
            return redirect()->back()->with('mensaje', 'Menú des-asignado');
        }
    }

    public function agregarPermiso($idU, $idP)
    {
        $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $idU)
            ->where('PRM_USR_Permiso_Id', '=', $idP)
            ->first();
        if (!$asignado) {
            PermisoUsuario::create([
                'PRM_USR_Usuario_Id' => $idU,
                'PRM_USR_Permiso_Id' => $idP
            ]);
            return redirect()->back()->with('mensaje', 'Permiso Asignado.');
        }else{
            return redirect()->back()->with('mensaje', 'El Permiso se encuentra Asignado.');
        }
    }

    public function quitarPermiso($idU, $idP)
    {
        $asignado = PermisoUsuario::where('PRM_USR_Usuario_Id', '=', $idU)
            ->where('PRM_USR_Permiso_Id', '=', $idP)
            ->first();
        if (!$asignado) {
            return redirect()->back()->withErrors('El Permiso no se encuentra asignado');
        }else{
            $asignado->delete();
            return redirect()->back()->with('mensaje', 'Permiso des-asignado');
        }
    }
}
