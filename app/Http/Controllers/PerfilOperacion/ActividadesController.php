<?php

namespace App\Http\Controllers\PerfilOperacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Carbon;
use App\Models\Tablas\HorasActividad;
use PDF;
use Illuminate\Http\Response;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ActividadesController extends Controller
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
        $actividadesProceso = $this->actividadesProceso();
        foreach ($actividadesProceso as $actividad) {
            if (Carbon::now() > $actividad->ACT_Fecha_Fin_Actividad) {
                Actividades::findOrFail($actividad->ID_Actividad)->update(['ACT_Estado_Id' => 2]);
                HistorialEstados::create([
                    'HST_EST_Fecha' => Carbon::now(),
                    'HST_EST_Estado' => 2,
                    'HST_EST_Actividad' => $actividad->ID_Actividad
                ]);
                $actividadesProceso = $this->actividadesProceso();
            }
        }
        $actividadesAtrasadas = $this->actividadesAtrasadas();
        $actividadesFinalizadas = $this->actividadesFinalizadas();
        return view('perfiloperacion.actividades.listar', compact('actividadesProceso', 'actividadesAtrasadas', 'actividadesFinalizadas', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignarHoras($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $actividades = $this->obtenerActividades($id);
        $horas = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $id)
            ->sum('HRS_ACT_Cantidad_Horas_Asignadas');
        if (count($actividades) == 0)
            return redirect()->route('actividades_perfil_operacion')->withErrors('La actividad no existe.');
        if ($horas != 0)
            return redirect()->route('actividades_perfil_operacion')->withErrors('Ya se asignaron horas de trabajo a la Actividad.');
        $hoy = Carbon::now();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('perfiloperacion.actividades.asignacion', compact('id', 'actividades', 'datos', 'notificaciones', 'cantidad'));
    }

    public function guardarHoras(Request $request, $id)
    {
        $fecha = HorasActividad::findOrFail($id);
        $vigente = Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00');
        //dd(Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad)-24);
        if (Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') < Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y')) {
            return response()->json(['msg' => 'errorF']);
        }
        if ((Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') == Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y') && Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') <= 1)
            || (Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') == Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y') && Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') < $request->HRS_ACT_Cantidad_Horas_Asignadas)) {
            return response()->json(['msg' => 'errorF']);
        }
        $horas = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->where('ha.HRS_ACT_Fecha_Actividad', '=', $fecha->HRS_ACT_Fecha_Actividad)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('ha.id', '<>', $id)
            ->sum('ha.HRS_ACT_Cantidad_Horas_Asignadas');
        if ((($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 8 && ($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) <= 14)) {
            HorasActividad::findOrFail($id)->update([
                'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas
            ]);
            return response()->json(['msg' => 'alerta']);
        } else if (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 14) {
            return response()->json(['msg' => 'error']);
        } else {
            HorasActividad::findOrFail($id)->update([
                'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas
            ]);
            return response()->json(['msg' => 'exito'], 200);
        }
    }

    public function terminarAsignacion($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $horas = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $id)
            ->sum('HRS_ACT_Cantidad_Horas_Asignadas');
        if ($horas != 0) {
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' se ha asignado sus horas de trabajo',
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'aprobar_horas_actividad',
                'idH',
                $id,
                'alarm'
            );
            $para = Usuarios::findOrFail($datos->USR_Supervisor_Id);
            $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
            Mail::send('general.correo.informacion', [
                'titulo' => $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' ha asignado sus horas de trabajo',
                'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
                'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' a asignado sus horas de trabajo, para que las apruebes.'
            ], function($message) use ($para){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                    ->subject('Horas de trabajo asignadas');
            });
        }
        return response()->json(['msg' => 'exito'], 200);
    }

    public function generarPdf()
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($datos->USR_Empresa_Id);
        $actividadesProceso = $this->actividadesProceso($hoy);
        $actividadesAtrasadas = $this->actividadesAtrasadas($hoy);
        $actividadesFinalizadas = $this->actividadesFinalizadas();

        $pdf = PDF::loadView('includes.pdf.actividades', compact('actividadesProceso', 'actividadesAtrasadas', 'actividadesFinalizadas', 'empresa'));

        $fileName = 'Actividades' . session()->get('Usuario_Nombre');
        return $pdf->download($fileName);
    }

    public function descargarDocumentoSoporte($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
            ->join('TBL_Documentos_Soporte as ds', 'ds.DOC_Actividad_Id', '=', 'a.id')
            ->select('ds.ACT_Documento_Soporte_Actividad')
            ->where('a.id', '=', $id)
            ->first();
        if(!$actividad){
            return redirect()->back()->withErrors('No hay documento disponible para descargar');
        }
        return response()->download(public_path() . '/documentos_soporte/' . $actividad->ACT_Documento_Soporte_Actividad);
    }

    public function finalizar($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = $this->obtenerActividades($id);

        return view('perfiloperacion.actividades.finalizar', compact('id', 'datos', 'notificaciones', 'cantidad'));
    }

    public function guardarFinalizar(Request $request)
    {
        if (!$request->hasFile('ACT_Documento_Evidencia_Actividad') && !$request['ACT_FIN_Link']) {
            return redirect()->route('actividades_finalizar_perfil_operacion', [$request['Actividad_Id']])->withErrors('Debe cargar un documento o agregar un link que evidencie la actividad realizada.')->withInput();
        }
        ActividadesFinalizadas::create([
            'ACT_FIN_Titulo' => $request['ACT_FIN_Titulo'],
            'ACT_FIN_Descripcion' => $request['ACT_FIN_Descripcion'],
            'ACT_FIN_Actividad_Id' => $request['Actividad_Id'],
            'ACT_FIN_Fecha_Finalizacion' => Carbon::now(),
            'ACT_FIN_Link' => $request['ACT_FIN_Link']
        ]);
        $af = ActividadesFinalizadas::orderBy('created_at', 'desc')->first();
        if ($request->hasFile('ACT_Documento_Evidencia_Actividad')) {
            foreach ($request->file('ACT_Documento_Evidencia_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    DocumentosEvidencias::create([
                        'DOC_Actividad_Finalizada_Id' => $af->id,
                        'ACT_Documento_Evidencia_Actividad' => $archivo
                    ]);
                }
            }
        }
        Respuesta::create([
            'RTA_Actividad_Finalizada_Id' => $af->id,
            'RTA_Estado_Id' => 4
        ]);
        Actividades::findOrFail($request['Actividad_Id'])->update(['ACT_Estado_Id' => 3]);
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 4,
            'HST_EST_Actividad' => $request['Actividad_Id']
        ]);
        $para = DB::table('TBL_Permiso as p')
            ->join('TBL_Permiso_Usuario as pu', 'pu.PRM_USR_Permiso_Id', '=', 'p.id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'pu.PRM_USR_Usuario_Id')
            ->where('p.PRM_Nombre_Permiso', '=', 'validador')
            ->select('u.*')->first();
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Tarea finalizada y entregada',
            'nombre' => $para->USR_Nombres_Usuario.' '.$para->USR_Apellidos_Usuario,
            'contenido' => $para->USR_Nombres_Usuario.', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' a realizado la entrega de una tarea y está esperando a ser aprobada.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to($para->USR_Correo_Usuario, 'InkBrutalPRY, Software de Gestión de Proyectos')
                ->subject('Tarea finalizada y entregada');
        });
        
        return redirect()->route('actividades_perfil_operacion')->with('mensaje', 'Actividad finalizada');
    }

    public function solicitarTiempo($id)
    {
        $actividad = Actividades::findOrFail($id);
        return json_encode($actividad);
    }


    public function actividadesProceso()
    {
        $actividadesProceso = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->leftjoin('TBL_Horas_Actividad as ha', 'ha.HRS_ACT_Actividad_Id', '=', 'a.id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->leftJoin('TBL_Documentos_Soporte as ds', 'ds.DOC_Actividad_Id', '=', 'a.id')
            ->select('a.id AS ID_Actividad', 'a.*', 'ds.*', 'p.*', 'ha.*', 'e.*', DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Asignadas) as Horas'), DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Reales) as HorasR'))
            ->where('a.ACT_Estado_Id', '=', 1)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('p.PRY_Estado_Proyecto', '=', 1)
            ->orderBy('a.id', 'ASC')
            ->groupBy('a.id')
            ->get();
        return $actividadesProceso;
    }

    public function actividadesAtrasadas()
    {
        $actividadesAtrasadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id AS ID_Actividad', 'a.*', 'af.*', 'p.*', DB::raw('count(af.ACT_FIN_Actividad_Id) as fila'))
            ->where('a.ACT_Estado_Id', '=', 2)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('p.PRY_Estado_Proyecto', '=', 1)
            ->orderBy('a.id')
            ->groupBy('a.id')
            ->get();

        return $actividadesAtrasadas;
    }

    public function actividadesFinalizadas()
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id AS ID_Actividad', 'a.*', 'p.*', 'af.*', 'e.*')
            ->where('a.ACT_Estado_Id', '<>', 1)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('p.PRY_Estado_Proyecto', '=', 1)
            ->orderBy('a.id')
            ->groupBy('a.id')
            ->get();
        return $actividadesFinalizadas;
    }

    public function obtenerActividades($id)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->select('ha.id as Id_Horas', 'ha.*', 'a.*')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('ha.HRS_ACT_Actividad_Id', '=', $id)
            ->get();
        return $actividades;
    }
}
