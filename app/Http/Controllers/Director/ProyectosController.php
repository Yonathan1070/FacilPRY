<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Facades\DB;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proyectos = DB::table('TBL_Usuarios')
            ->join('TBL_Proyectos', 'TBL_Usuarios.id', '=', 'TBL_Proyectos.PRY_Cliente_Id')
            ->select('TBL_Proyectos.*', 'TBL_Usuarios.USR_Nombre', 'TBL_Usuarios.USR_Apellido')
            ->orderBy('TBL_Proyectos.Id', 'ASC')
            ->get();
        return view('director.proyectos.listar', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $clientes = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Roles.RLS_Nombre')
            ->where('TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', '5')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.USR_Apellido', 'ASC')
            ->get();
        return view('director.proyectos.crear', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        Proyectos::create($request->all());
        return redirect()->route('crear_proyecto_director')->with('mensaje', 'Proyecto agregado con exito');
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
