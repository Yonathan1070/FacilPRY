<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionProyecto;
use PDF;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyectos = DB::table('TBL_Usuarios')
            ->join('TBL_Proyectos', 'TBL_Usuarios.id', '=', 'TBL_Proyectos.PRY_Cliente_Id')
            ->select('TBL_Proyectos.*', 'TBL_Usuarios.USR_Nombre', 'TBL_Usuarios.USR_Apellido')
            ->orderBy('TBL_Proyectos.Id', 'ASC')
            ->get();
        return view('director.proyectos.listar', compact('proyectos', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $clientes = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Roles.RLS_Nombre')
            ->where('TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', '5')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.USR_Apellido', 'ASC')
            ->get();
        return view('director.proyectos.crear', compact('clientes', 'datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionProyecto $request)
    {
        Proyectos::create($request->all());
        return redirect()->back()->with('mensaje', 'Proyecto agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarPdf($id)
    {
        $proyecto = Proyectos::findOrFail($id);
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Usuarios as us', 'us.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->where('p.id', '=', $id)
            ->select('a.*', 'us.USR_Nombre as NombreT', 'us.USR_Apellido as ApellidoT', 'p.*', 'p.*', 'u.*', 'e.*')
            ->get();
        $pdf = PDF::loadView('includes.pdf.proyecto.actividades', compact('actividades'));
        $fileName = 'Actividades'.$proyecto->PRY_Nombre_Proyecto;
        return $pdf->download($fileName);
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
