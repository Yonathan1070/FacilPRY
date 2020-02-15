<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionProyecto;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use PDF;
use stdClass;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        can('listar-proyectos');
        $permisos = ['crear'=> can2('crear-proyectos'), 'listarR'=>can2('listar-requerimientos'), 'listarE'=>can2('listar-empresas')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        $proyectosNoFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin('TBL_Requerimientos as r', 'r.REQ_Proyecto_Id', '=', 'p.id')
            ->leftjoin('TBL_Actividades as a', 'a.ACT_Requerimiento_Id', '=', 'r.id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('u.*', 'p.*', 'p.id as Proyecto_Id', DB::raw('COUNT(a.id) as Actividades_Totales'), DB::raw('COUNT(af.id) as Actividades_Finalizadas'))
            ->where('p.PRY_Empresa_Id', '=', $id)
            ->where('p.PRY_Finalizado_Proyecto', '=', 0)
            ->groupBy('p.id')
            ->get();
        $proyectosFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin('TBL_Requerimientos as r', 'r.REQ_Proyecto_Id', '=', 'p.id')
            ->leftjoin('TBL_Actividades as a', 'a.ACT_Requerimiento_Id', '=', 'r.id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('u.*', 'p.*', 'p.id as Proyecto_Id', DB::raw('COUNT(a.id) as Actividades_Totales'), DB::raw('COUNT(af.id) as Actividades_Finalizadas'))
            ->where('p.PRY_Empresa_Id', '=', $id)
            ->where('p.PRY_Finalizado_Proyecto', '=', 1)
            ->groupBy('p.id')
            ->get();
        return view('proyectos.listar', compact('proyectosNoFinalizados', 'proyectosFinalizados', 'empresa', 'datos', 'notificaciones', 'cantidad', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($id)
    {
        can('crear-proyectos');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        $clientes = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'r.RLS_Nombre_Rol')
            ->where('ur.USR_RLS_Rol_Id', '=', '3')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->where('u.USR_Empresa_Id', '=', $id)
            ->orderBy('u.USR_Apellidos_Usuario', 'ASC')
            ->get();
        return view('proyectos.crear', compact('clientes', 'empresa', 'datos', 'notificaciones', 'cantidad'));
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::findOrFail($request->PRY_Cliente_Id);
        if($datos->USR_Supervisor_Id == 0)
            $datos->USR_Supervisor_Id = 1;
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha creado el proyecto '.$request->PRY_Nombre_Proyecto,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'proyectos',
            'id',
            $request->PRY_Empresa_Id,
            'library_add'
        );
        Notificaciones::crearNotificacion(
            'Hola! '.$datosU->USR_Nombres_Usuario.' '.$datosU->USR_Apellidos_Usuario.', su proyecto '.$request->PRY_Nombre_Proyecto.' ha sido creado',
            session()->get('Usuario_Id'),
            $datosU->id,
            'proyectos',
            'id',
            $request->PRY_Empresa_Id,
            'library_add'
        );
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
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'us.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('ro.id', '<>', 3)
            ->where('p.id', '=', $id)
            ->select('a.*', 'us.USR_Nombres_Usuario as NombreT', 'us.USR_Apellidos_Usuario as ApellidoT', 'p.*', 'r.*', 'u.*', 'es.*', 'e.*')
            ->get();
        if(count($actividades)<=0){
            return redirect()->back()->withErrors('No es posible generar el reporte de actividades debido a que no hay actividades registradas para el proyecto seleccionado!');
        }
        $empresa = Empresas::findOrFail(session()->get('Empresa_Id'));
        $pdf = PDF::loadView('includes.pdf.proyecto.actividades', compact('actividades', 'empresa'));
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
            ->where('e.EST_Nombre_Estado', '<>', 'En Proceso')
            ->where('e.EST_Nombre_Estado', '<>', 'Atrasado')
            ->where('e.EST_Nombre_Estado', '<>', 'Rechazado')
            ->where('p.id', '=', $id)
            ->get();
        
        if((double)count($actividadesTotales) == 0){
            $porcentaje = 0;
        }else {
            $porcentaje = (double)count($actividadesFinalizadas)/(double)count($actividadesTotales)*100;
        }
        $dato = new stdClass();
        $dato->porcentaje = (int)$porcentaje;
        return json_encode($dato);
    }

    public function gantt($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $proyecto = Proyectos::findOrFail($id);
        $fechas = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*', 'ha.*')
            ->where('p.id', '=', $id)
            ->orderBy('ha.HRS_ACT_Fecha_Actividad')
            ->groupBy('ha.HRS_ACT_Fecha_Actividad')
            ->get();
        $actividades = DB::table('TBL_Actividades as a')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*')
            ->where('p.id', '=', $id)
            ->orderby('a.ACT_Fecha_Inicio_Actividad')
            ->get();
        //$pdf = PDF::loadView('proyectos.gantt', compact('actividades', 'fechas', 'proyecto', 'notificaciones', 'cantidad', 'datos'))->setPaper('a4', 'landscape');
        //$fileName = 'Gantt'.$proyecto->PRY_Nombre_Proyecto;
        //return $pdf->download($fileName.'.pdf');
        return view('proyectos.gantt', compact('actividades', 'fechas', 'proyecto', 'notificaciones', 'cantidad', 'datos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function finalizar($id)
    {
        Proyectos::finalizarProyecto($id);
        return redirect()->back()->with('mensaje', 'Proyecto finalizado');
    }

    public function activar($id)
    {
        Proyectos::activarProyecto($id);
        return redirect()->back()->with('mensaje', 'Proyecto activado');
    }
}
