<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Iconos;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'))->first();
        $rols = Roles::orderBy('id')->pluck('RLS_Nombre', 'id')->toArray();
        $menus = Menu::getMenu();
        $menusRols = Menu::with('roles')->get()->pluck('roles', 'id')->toArray();
        return view('administrador.permisos.listar', compact('rols', 'menus', 'menusRols', 'datos'));
        
        /*$menus = Menu::getMenu();
        return view('administrador.permisos.listar', compact('menus'));*/
    }
    public function asignarPermiso(Request $request)
    {
        if($request->ajax()){
            $menus = new Menu();
            if ($request->input('estado') == 1) {
                $menus->find($request->input('MN_RL_Menu_Id'))->roles()->attach($request->input('MN_RL_Rol_Id'));
                return response()->json(['respuesta' => 'El menú se asignó correctamente']);
            }else{
                $menus->find($request->input('MN_RL_Menu_Id'))->roles()->detach($request->input('MN_RL_Rol_Id'));
                return response()->json(['respuesta' => 'El menú se desasignó correctamente']);
            }
        }else{
            abort(404);
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
        return redirect()->route('crear_menu_administrador')->with('mensaje', 'Menú creado con exito');
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
