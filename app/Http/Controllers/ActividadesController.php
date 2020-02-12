<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idR)
    {
        can('listar-actividades');
        $permisos = ['crear' => can2('crear-actividades'), 'crearC' => can2('crear-actividades-cliente'), 'listarP' => can2('listar-proyectos')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $requerimiento = Requerimientos::findOrFail($idR);
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $requerimiento['REQ_Proyecto_Id'])->get();
        if (count($requerimientos) <= 0) {
            return redirect()->back()->withErrors('No se pueden asignar actividades si no hay requerimientos previamente registrados.');
        }
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '=', $idR)
            ->where('a.ACT_Trabajador_Id', '<>', $cliente->PRY_Cliente_Id)
            ->select('a.id as ID_Actividad', 'r.id as ID_Requerimiento', 'a.*', 'u.*', 'e.*', 'r.*')
            ->orderBy('a.Id', 'ASC')
            ->get();
        $actividadesCliente = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '=', $idR)
            ->where('a.ACT_Trabajador_Id', '=', $cliente->PRY_Cliente_Id)
            ->select('a.id as ID_Actividad', 'r.id as ID_Requerimiento', 'a.*', 'u.*', 'e.*', 'r.*')
            ->orderBy('a.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);
        return view('actividades.listar', compact('actividades', 'actividadesCliente', 'proyecto', 'requerimiento', 'datos', 'notificaciones', 'cantidad', 'permisos', 'requerimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crearTrabajador($idR)
    {
        can('crear-actividades');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '4')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('actividades.crear', compact('proyecto', 'requerimiento', 'perfilesOperacion', 'datos', 'notificaciones', 'cantidad'));
    }

    public function crearCliente($idR)
    {
        can('crear-actividades-cliente');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '4')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('actividades.crear', compact('proyecto', 'requerimiento', 'perfilesOperacion', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionActividad $request, $idR)
    {
        $hoy = Carbon::now();
        $diferencia = $hoy->diffInMinutes($request['ACT_Hora_Entrega']);
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()->route($request['ruta'], [$idR])->withErrors('La fecha de inicio no puede ser superior a la fecha de finalización')->withInput();
        }else if (($request['ACT_Fecha_Inicio_Actividad'] == $request['ACT_Fecha_Fin_Actividad']) &&
                    ($diferencia < 60 || $diferencia > 600)) {
            return redirect()->route($request['ruta'], [$idR])->withErrors('La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas')->withInput();
        }
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('REQ_Proyecto_Id', '=', $request->ACT_Proyecto_Id)
            ->get();

        foreach ($actividades as $actividad) {
            if ($actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad) {
                return redirect()->route($request['ruta'], [$idR])->withErrors('Ya hay registrada una actividad con el mismo nombre.')->withInput();
            }
        }
        $proyecto = Proyectos::findOrFail($request->ACT_Proyecto_Id);
        if ($request->ACT_Usuario_Id == null) {
            $idUsuario = $proyecto->PRY_Cliente_Id;
            $ruta = 'crear_actividad_cliente';
            $rutaNotificacion = 'actividades_cliente';
        } else {
            $idUsuario = $request['ACT_Usuario_Id'];
            $ruta = 'crear_actividad_trabajador';
            $rutaNotificacion = 'actividades_perfil_operacion';
        }
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'] . ' ' . $request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Requerimiento_Id' => $idR,
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
                } else {
                    $actividad->destroy();
                }
            }
        }

        $rangos = $this->obtenerFechasRango($request['ACT_Fecha_Inicio_Actividad'], $request['ACT_Fecha_Fin_Actividad']);
        foreach ($rangos as $fecha) {
            HorasActividad::create([
                'HRS_ACT_Actividad_Id' => $actividad->id,
                'HRS_ACT_Fecha_Actividad' => $fecha . " 23:59:00"
            ]);
        }
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 1,
            'HST_EST_Actividad' => $actividad->id
        ]);
        Notificaciones::crearNotificacion(
            'Nueva tarea asignada',
            session()->get('Usuario_Id'),
            $idUsuario,
            $rutaNotificacion,
            null,
            null,
            'add_to_photos'
        );
        $para = Usuarios::findOrFail($idUsuario);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Nueva Tarea Asignada',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' le ha asignado una Tarea'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                ->subject('Tarea Asignada');
        });
        return redirect()->route($ruta, [$idR])->with('mensaje', 'Actividad agregada con exito');
    }

    private function obtenerFechasRango($fechaInicio, $fechaFin)
    {
        // cut hours, because not getting last day when hours of time to is less than hours of time_from 
        // see while loop 
        $inicio = Carbon::createFromFormat('Y-m-d', substr($fechaInicio, 0, 10));
        $fin = Carbon::createFromFormat('Y-m-d', substr($fechaFin, 0, 10));
        $fechas = [];
        while ($inicio->lte($fin)) {
            $fechas[] = $inicio->copy()->format('Y-m-d');
            $inicio->addDay();
        }
        return $fechas;
    }

    public function detalleActividad($id)
    {
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
    public function editarTrabajador($idA)
    {
        can('editar-actividades');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '4')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('actividades.editar', compact('actividad', 'datos', 'notificaciones', 'cantidad', 'perfilesOperacion', 'proyecto'));
    }

    public function editarCliente($idA)
    {
        can('editar-actividades');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        return view('actividades.editar', compact('actividad', 'datos', 'notificaciones', 'cantidad', 'proyecto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $idA)
    {
        $hoy = Carbon::now();
        $diferencia = $hoy->diffInMinutes($request['ACT_Hora_Entrega']);
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()->route($request['ruta'], [$idA])->withErrors('La fecha de inicio no puede ser superior a la fecha de finalización')->withInput();
        }else if (($request['ACT_Fecha_Inicio_Actividad'] == $request['ACT_Fecha_Fin_Actividad']) &&
                    ($diferencia < 60 || $diferencia > 600)) {
            return redirect()->route($request['ruta'], [$idA])->withErrors('La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas')->withInput();
        }
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('REQ_Proyecto_Id', '=', $request->ACT_Proyecto_Id)
            ->where('a.id', '<>', $idA)
            ->select('a.id as Actividad_Id', 'a.*', 'r.*', 'p.*')
            ->get();
        foreach ($actividades as $actividad) {
            if ($actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad && $actividad->Actividad_Id == $idA) {
                return redirect()->route($request['ruta'], [$idA])->withErrors('Ya hay registrada una actividad con el mismo nombre.')->withInput();
            }
        }
        $proyecto = Proyectos::findOrFail($request->ACT_Proyecto_Id);
        if ($request->ACT_Usuario_Id == null) {
            $idUsuario = $proyecto->PRY_Cliente_Id;
            $rutaNotificacion = 'actividades_cliente';
        } else {
            $idUsuario = $request['ACT_Usuario_Id'];
            $rutaNotificacion = 'actividades_perfil_operacion';
        }
        $actividad = Actividades::findOrFail($idA)->update([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'] . ' ' . $request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Trabajador_Id' => $idUsuario,
        ]);

        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            foreach ($request->file('ACT_Documento_Soporte_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    $documentoBD = DocumentosSoporte::where('DOC_Actividad_Id', '=', $idA)->first();
                    if($documentoBD == null){
                        DocumentosSoporte::create([
                            'DOC_Actividad_Id' => $idA,
                            'ACT_Documento_Soporte_Actividad' => $archivo
                        ]);
                    }else{
                        $documentoBD->update([
                            'ACT_Documento_Soporte_Actividad' => $archivo
                        ]);
                    }
                } else {
                    $actividad->destroy();
                }
            }
        }

        HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->delete();
        $rangos = $this->obtenerFechasRango($request['ACT_Fecha_Inicio_Actividad'], $request['ACT_Fecha_Fin_Actividad']);
        foreach ($rangos as $fecha) {
            HorasActividad::create([
                'HRS_ACT_Actividad_Id' => $idA,
                'HRS_ACT_Fecha_Actividad' => $fecha . " 23:59:00"
            ]);
        }
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 1,
            'HST_EST_Actividad' => $idA
        ]);
        Notificaciones::crearNotificacion(
            'Actividad Editada',
            session()->get('Usuario_Id'),
            $idUsuario,
            $rutaNotificacion,
            null,
            null,
            'add_to_photos'
        );
        $para = Usuarios::findOrFail($idUsuario);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Tarea Editada',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' le ha asignado una Tarea'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                ->subject('Tarea Asignada');
        });
        $actividad = Actividades::findOrFail($idA);
        return redirect()->route('actividades', [$actividad->ACT_Requerimiento_Id])->with('mensaje', 'Actividad editada con exito');
    }

    public function cambiarRequerimiento(Request $request, $idA){
        if($request->ajax()){
            try{
                Actividades::findOrFail($idA)->update(['ACT_Requerimiento_Id' => $request['ACT_Requerimiento']]);
                return response()->json(['mensaje' => 'ok']);
            }catch(QueryException $qe){
                return response()->json(['mensaje' => 'ng']);
            }
        }else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $idA)
    {
        if(!can('eliminar-actividades')){
            return response()->json(['mensaje' => 'np']);
        }else{
            if($request->ajax()){
                try{
                    DocumentosSoporte::where('DOC_Actividad_Id', '=', $idA)->delete();
                    HistorialEstados::where('HST_EST_Actividad', '=', $idA)->delete();
                    HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->delete();
                    Actividades::destroy($idA);
                    return response()->json(['mensaje' => 'ok']);
                }catch(QueryException $e){
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }

    public function aprobarHoras($idH)
    {
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
        if (count($horasAprobar) == 0) {
            return redirect()->route('inicio_director')->with('mensaje', 'Las horas de trabajo ya han sido aprobadas.');
        }
        return view('actividades.aprobar', compact('horasAprobar', 'notificaciones', 'cantidad', 'datos'));
        //dd($horasAprobar);
    }

    public function actualizarHoras(Request $request, $idH)
    {
        $fecha = HorasActividad::findOrFail($idH);
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id');
        $trabajador = $actividades->where('ha.id', '=', $idH)->first();
        $horas = $actividades
            ->where('ha.HRS_ACT_Fecha_Actividad', '=', $fecha->HRS_ACT_Fecha_Actividad)
            ->where('a.ACT_Trabajador_Id', '=', $trabajador->ACT_Trabajador_Id)
            ->where('ha.id', '<>', $idH)
            ->sum('ha.HRS_ACT_Cantidad_Horas_Asignadas');
        $horaModif = HorasActividad::findOrFail($idH);
        if (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 8 && ($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) <= 18) {
            HorasActividad::findOrFail($idH)->update([
                'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas,
                'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
            ]);
            return response()->json(['msg' => 'alerta']);
        } else if (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 18) {
            return response()->json(['msg' => 'error']);
        } else if($horaModif->HRS_ACT_Cantidad_Horas_Asignadas != 0 && $request->HRS_ACT_Cantidad_Horas_Asignadas == 0){
            return response()->json(['msg' => 'cero']);
        }
        HorasActividad::findOrFail($idH)->update([
            'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
        ]);
        return response()->json(['msg' => 'exito']);
    }

    public function finalizarAprobacion($idA)
    {
        $horasActividades = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->get();
        $trabajador = Actividades::findOrFail($horasActividades->first()->HRS_ACT_Actividad_Id);
        foreach ($horasActividades as $actividad) {
            if ($actividad->HRS_ACT_Cantidad_Horas_Reales == null) {
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
        $para = Usuarios::findOrFail($trabajador->ACT_Trabajador_Id);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Horas de trabajo Aprobadas',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' a aprobado tus horas de trabajo, ya tienes acceso  a la entrega de la tarea.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                ->subject('Horas de trabajo aprobadas');
        });
        return response()->json(['msg' => 'exito']);
    }

    public function detalleActividadModal($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('a.id', '=', $id)->first();
        return json_encode($actividad);
    }
}
