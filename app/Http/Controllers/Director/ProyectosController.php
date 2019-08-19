<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionProyecto;
use PDF;
use stdClass;

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
        $proyectos = DB::table('TBL_Usuarios as u')
            ->join('TBL_Proyectos as p', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'u.USR_Nombres_Usuario', 'u.USR_Apellidos_Usuario')
            ->orderBy('p.Id')
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
        $clientes = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'r.RLS_Nombre_Rol')
            ->where('ur.USR_RLS_Rol_Id', '=', '5')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario', 'ASC')
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
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as es', 'es.id', '=', 'a.ACT_Estado_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->where('p.id', '=', $id)
            ->select('a.*', 'us.USR_Nombres_Usuario as NombreT', 'us.USR_Apellidos_Usuario as ApellidoT', 'p.*', 'r.*', 'u.*', 'es.*', 'e.*')
            ->get();
        if(count($actividades)<=0){
            return redirect()->back()->withErrors('No es posible generar el reporte de actividades debido a que no hay actividades registradas para el proyecto seleccionado!');
        }
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
    public function obtenerPorcentaje($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->get();
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.EST_Nombre_Estado', '=', 'Finalizado')
            ->where('p.id', '=', $id)
            ->get();
        
        if((double)count($actividadesTotales) == 0){
            $porcentaje = 0;
        }else {
            $porcentaje = (double)count($actividadesFinalizadas)/(double)count($actividadesTotales)*100;
        }
        $dato = new stdClass();
        $dato->porcentaje = $porcentaje;
        return json_encode($dato);
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
