<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Iconos;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $usuarios = Usuarios::orderBy('id')->get();
        return view('administrador.permisos.listar', compact('usuarios', 'datos'));
    }
    
    public function asignarRol($id){
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::where('RLS_Nombre', '<>', 'Perfil de Operación')->orderBy('id')->get();
        return view('administrador.permisos.asignar', compact('roles', 'datos', 'id'));
    }

    public function agregar($idU, $idR)
    {
        $asignado = UsuariosRoles::where('USR_RLS_Rol_Id', '=', $idR)
            ->where('USR_RLS_Usuario_Id', '=', $idU)
            ->first();
        if($asignado && $asignado->USR_RLS_Estado == true){
            return redirect()->back()->with('mensaje', 'El Rol ya está asignado');
        }
        if (!$asignado) {
            UsuariosRoles::create([
                'USR_RLS_Rol_Id' => $idR,
                'USR_RLS_Usuario_Id' => $idU,
                'USR_RLS_Estado' => 1
            ]);
            return redirect()->back()->with('mensaje', 'Rol Asignado.');
        }
        if ($asignado->USR_RLS_Estado == false) {
            $asignado->update([
                'USR_RLS_Estado' => 1
            ]);
            return redirect()->back()->with('mensaje', 'Rol Asignado.');
        }
    }

    public function quitar($idU, $idR)
    {
        $asignado = UsuariosRoles::where('USR_RLS_Rol_Id', '=', $idR)
            ->where('USR_RLS_Usuario_Id', '=', $idU)
            ->first();
        if($asignado && $asignado->USR_RLS_Estado == true){
            $asignado->update([
                'USR_RLS_Estado' => 0
            ]);
            return redirect()->back()->with('mensaje', 'Rol DesAsignado.');
        }
        if (!$asignado) {
            return redirect()->back()->withErrors('El Rol no se encuentra asignado');
        }
        if ($asignado->USR_RLS_Estado == false) {
            return redirect()->back()->withErrors('El Rol no se encuentra asignado');
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
