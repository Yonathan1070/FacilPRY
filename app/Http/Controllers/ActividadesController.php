<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionActividad;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        can('listar-actividades');
        $permisos = ['crear'=>can2('crear-actividades'), 'crearC'=>can2('crear-actividades-cliente')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->get();
        if(count($requerimientos)<=0){
            return redirect()->back()->withErrors('No se pueden asignar actividades si no hay requerimientos previamente registrados.');
        }
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Proyectos::findOrFail($idP);
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.REQ_Proyecto_Id', '=', $idP)
            ->where('a.ACT_Trabajador_Id', '<>', $cliente->PRY_Cliente_Id)
            ->orderBy('a.Id', 'ASC')
            ->get();
        $actividadesCliente = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.REQ_Proyecto_Id', '=', $idP)
            ->where('a.ACT_Trabajador_Id', '=', $cliente->PRY_Cliente_Id)
            ->orderBy('a.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($idP);
        return view('actividades.listar', compact('actividades', 'actividadesCliente', 'proyecto', 'datos', 'notificaciones', 'cantidad', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearTrabajador($idP)
    {
        can('crear-actividades');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->orderBy('id', 'ASC')->get();
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '6')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('actividades.crear', compact('proyecto', 'perfilesOperacion', 'requerimientos', 'datos', 'notificaciones', 'cantidad'));
    }

    public function crearCliente($idP)
    {
        can('crear-actividades-cliente');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->orderBy('id', 'ASC')->get();
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '6')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('actividades.crear', compact('proyecto', 'perfilesOperacion', 'requerimientos', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionActividad $request)
    {
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()->route('crear_actividad', [$request['ACT_Proyecto_Id']])->withErrors('La fecha de inicio no puede ser superior a la fecha de finalización')->withInput();
        }
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('REQ_Proyecto_Id', '=', $request->ACT_Proyecto_Id)
            ->get();

        foreach ($actividades as $actividad) {
            if($actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad){
                return redirect()->route('crear_actividad', [$request['ACT_Proyecto_Id']])->withErrors('Ya hay registrada una actividad con el mismo nombre.')->withInput();
            }
        }
        $proyecto = Proyectos::findOrFail($request->ACT_Proyecto_Id);
        if($request->ACT_Usuario_Id == null){
            $idUsuario = $proyecto->PRY_Cliente_Id;
            $ruta = 'crear_actividad_cliente';
            $rutaNotificacion = 'actividades_cliente';
        }else{
            $idUsuario = $request['ACT_Usuario_Id'];
            $ruta = 'crear_actividad_trabajador';
            $rutaNotificacion = 'actividades_perfil_operacion';
        }
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].' '.$request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Requerimiento_Id' => $request['ACT_Requerimiento_Id'],
            'ACT_Trabajador_Id' => $idUsuario,
        ]);
        $actividad = Actividades::orderByDesc('created_at')->take(1)->first();

        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            foreach ($request->file('ACT_Documento_Soporte_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    DocumentosSoporte::create([
                        'DOC_Actividad_Id' => $actividad->id,
                        'ACT_Documento_Soporte_Actividad' => $archivo
                    ]);
                }else{
                    $actividad->destroy();
                }
            }
        }

        $rangos = $this->obtenerFechasRango($request['ACT_Fecha_Inicio_Actividad'],$request['ACT_Fecha_Fin_Actividad']);
        foreach ($rangos as $fecha) {
            HorasActividad::create([
                'HRS_ACT_Actividad_Id' => $actividad->id,
                'HRS_ACT_Fecha_Actividad' => $fecha." 23:59:00"
            ]);
        }
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 1,
            'HST_EST_Actividad' => $actividad->id
        ]);
        Notificaciones::crearNotificacion(
            'Nueva actividad asignada',
            session()->get('Usuario_Id'),
            $idUsuario,
            $rutaNotificacion,
            null,
            null,
            'add_to_photos'
        );
        return redirect()->route($ruta, [$request['ACT_Proyecto_Id']])->with('mensaje', 'Actividad agregada con exito');
    }

    private function obtenerFechasRango($fechaInicio, $fechaFin) { 
        // cut hours, because not getting last day when hours of time to is less than hours of time_from 
        // see while loop 
        $inicio = Carbon::createFromFormat('Y-m-d', substr($fechaInicio, 0, 10)); 
        $fin = Carbon::createFromFormat('Y-m-d', substr($fechaFin, 0, 10)); 
        $fechas = []; 
        while ($inicio->lte($fin)) { 
            $fechas[] = $inicio->copy()->format('Y-m-d'); 
            $inicio->addDay(); 
        } return $fechas; 
    } 

    public function detalleActividad($id){
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesPendientes = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->select('af.id as Id_Act_Fin', 'a.id as Id_Act', 'af.*', 'a.*', 'p.*', 're.*', 'u.*', 'ro.*')
            ->where('af.Id', '=', $id)
            ->orderByDesc('af.created_at')
            ->first();
        $documentosSoporte = DB::table('TBL_Documentos_Soporte as d')
            ->join('TBL_Actividades as a', 'a.id', '=', 'd.DOC_Actividad_Id')
            ->get();
        $documentosEvidencia = DB::table('TBL_Documentos_Evidencias as d')
            ->join('TBL_Actividades_Finalizadas as a', 'a.id', '=', 'd.DOC_Actividad_Finalizada_Id')
            ->where('a.id', '=', $id)
            ->get();
        $perfil = DB::table('TBL_Usuarios as u')
            ->join('TBL_Actividades as a', 'a.ACT_Trabajador_Id', '=', 'u.id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('a.id', '=', $actividadesPendientes->Id_Act)
            ->first();
        $actividadFinalizada = ActividadesFinalizadas::findOrFail($id);
        $respuestasAnteriores = DB::table('TBL_Respuesta as r')
            ->join('TBL_Actividades_Finalizadas as af', 'af.id', '=', 'r.RTA_Actividad_Finalizada_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'r.RTA_Usuario_Id')
            ->select('r.*', 'u.*', 'af.*')
            ->where('af.ACT_FIN_Actividad_Id', '=', $actividadFinalizada->ACT_FIN_Actividad_Id)
            ->where('r.RTA_Titulo', '<>', null)->get();
        return view('actividades.detalle', compact('actividadesPendientes', 'perfil', 'datos', 'documentosSoporte', 'documentosEvidencia', 'respuestasAnteriores', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($idP, $idR)
    {
        can('editar-actividades');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP)->first();
        $requerimiento = Requerimientos::findOrFail($idR)->first();
        return view('requerimientos.editar', compact('proyecto', 'requerimiento', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionActividad $request, $idR)
    {
        Requerimientos::findOrFail($idR)->update($request->all());
        return redirect()->route('requerimientos', [$request['REQ_Proyecto_Id']])->with('mensaje', 'Requerimiento actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($idP, $idR)
    {
        can('eliminar-actividades');
        try{
            Requerimientos::destroy($idR);
            return redirect()->route('requerimientos', [$idP])->with('mensaje', 'El Requerimiento fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('requerimientos', [$idP])->withErrors(['El Requerimiento está siendo usada por otro recurso.']);
        }
    }

    public function aprobarHoras($idH){
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $horasAprobar = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->select('ha.id as Id_Horas', 'ha.*', 'r.*', 'u.*', 'a.*')
            ->where('a.id', '=', $idH)
            ->where('ha.HRS_ACT_Cantidad_Horas_Reales', '=', null)
            ->get();
        if(count($horasAprobar)==0){
            return redirect()->route('inicio_director')->with('mensaje', 'Las horas de trabajo ya han sido aprobadas.');
        }
        return view('actividades.aprobar', compact('horasAprobar', 'notificaciones', 'cantidad', 'datos'));
        //dd($horasAprobar);
    }

    public function actualizarHoras(Request $request, $idH){
        $fecha = HorasActividad::findOrFail($idH);
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id');
        $trabajador = $actividades->where('ha.id', '=', $idH)->first();
        $horas = $actividades
            ->where('ha.HRS_ACT_Fecha_Actividad', '=', $fecha->HRS_ACT_Fecha_Actividad)
            ->where('a.ACT_Trabajador_Id', '=', $trabajador->ACT_Trabajador_Id)
            ->where('ha.id', '<>', $idH)
            ->sum('ha.HRS_ACT_Cantidad_Horas_Asignadas');
        if(($horas+$request->HRS_ACT_Cantidad_Horas_Asignadas)>8 && ($horas+$request->HRS_ACT_Cantidad_Horas_Asignadas)<=18){
            HorasActividad::findOrFail($idH)->update([
                'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas,
                'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
            ]);
            return response()->json(['msg' => 'alerta']);
        }
        else if(($horas+$request->HRS_ACT_Cantidad_Horas_Asignadas)>18)
            return response()->json(['msg' => 'error']);
        HorasActividad::findOrFail($idH)->update([
            'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas,
                'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
        ]);
        return response()->json(['msg' => 'exito']);
    }

    public function finalizarAprobacion($idA){
        $horasActividades = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->get();
        $trabajador = Actividades::findOrFail($horasActividades->first()->HRS_ACT_Actividad_Id);
        foreach ($horasActividades as $actividad) {
            if($actividad->HRS_ACT_Cantidad_Horas_Reales == null){
                $actividad->update([
                    'HRS_ACT_Cantidad_Horas_Reales' => $actividad->HRS_ACT_Cantidad_Horas_Asignadas
                ]);
            }
        }
        Notificaciones::crearNotificacion(
            'Horas de trabajo Aprobadas',
            session()->get('Usuario_Id'),
            $trabajador->ACT_Trabajador_Id,
            'actividades_perfil_operacion',
            null,
            null,
            'done_all'
        );
        return response()->json(['msg' => 'exito']);
    }
}
