<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionProyecto;
use App\Models\Tablas\Empresas;
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
        $permisos = ['crear'=> can2('crear-proyectos'), 'listarR'=>can2('listar-requerimientos'), 'listarA'=>can2('listar-actividades')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        $proyectos = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as e', 'e.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('u.*', 'p.*')
            ->where('p.PRY_Empresa_Id', '=', $id)
            ->get();
        return view('proyectos.listar', compact('proyectos', 'empresa', 'datos', 'notificaciones', 'cantidad', 'permisos'));
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
            ->where('ur.USR_RLS_Rol_Id', '=', '5')
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
        $datosP = Proyectos::orderByDesc('created_at')->first();
        $datosU = Usuarios::findOrFail($datosP->PRY_Cliente_Id);
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha creado el proyecto '.$request->PRY_Nombre_Proyecto,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'library_add'
        );
        Notificaciones::crearNotificacion(
            'Hola! '.$datosU->USR_Nombres_Usuario.' '.$datosU->USR_Apellidos_Usuario.', su proyecto ha sido creado',
            session()->get('Usuario_Id'),
            $datosU->id,
            'inicio_cliente',
            null,
            null,
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
            ->where('ro.id', '<>', 5)
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

    public function gantt()
    {
        return view('proyectos.gantt');
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
