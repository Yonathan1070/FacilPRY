<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Iconos;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\MenuUsuario;

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
    
    public function asignarMenu($id){
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $menus = Menu::orderBy('MN_Orden_Menu')->get();
        return view('administrador.permisos.asignar', compact('menus', 'datos', 'id', 'notificaciones', 'cantidad'));
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $iconos = Iconos::get();
        return view('administrador.permisos.crear', compact('iconos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        Menu::create($request->all());
        return redirect()->back()->with('mensaje', 'Menú creado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        //
    }
}
