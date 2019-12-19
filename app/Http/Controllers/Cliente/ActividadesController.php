<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\Usuarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $actividadesPendientes = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->join('TBL_Respuesta as re', 're.RTA_Actividad_Finalizada_Id', '=', 'af.id')
            ->select('af.id as Id_Act_Fin', 'af.*', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 3)
            ->where('re.RTA_Usuario_Id', '=', 0)
            ->where('re.RTA_Estado_Id', '=', 12)
            ->where('af.ACT_FIN_Revisado', '=', 1)
            ->where('p.PRY_Cliente_Id', '=', session()->get('Usuario_Id'))
            ->get();
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id as Id_Actividad', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 3)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->get();
        $actividadesEntregar = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id as Id_Actividad', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 1)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->get();
        return view('cliente.actividades.inicio', compact('actividadesPendientes', 'actividadesFinalizadas', 'actividadesEntregar', 'datos', 'notificaciones', 'cantidad'));
    }

    public function finalizar($id){
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = DB::table('TBL_Actividades as a')
            ->select('a.*')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('a.id', '=', $id)
            ->get();

        return view('cliente.actividades.finalizar', compact('id', 'datos', 'notificaciones', 'cantidad'));
    }

    public function guardarFinalizar(Request $request){
        $horas = DB::table('TBL_Horas_Actividad')->where('HRS_ACT_Actividad_Id', '=', $request['Actividad_Id'])->get();
        $hR = count($horas) - Carbon::now()->diffInDays($horas->first()->HRS_ACT_Fecha_Actividad);
        HorasActividad::findOrFail($horas->first()->id)->update([
            'HRS_ACT_Cantidad_Horas_Asignadas' => count($horas),
            'HRS_ACT_Cantidad_Horas_Reales' => $hR
        ]);
        if (!$request->hasFile('ACT_Documento_Evidencia_Actividad')) {
            return redirect()->route('actividades_finalizar_cliente', [$request['Actividad_Id']])->withErrors('Debe cargar un documento que evidencie la actividad realizada.')->withInput();
        }
        ActividadesFinalizadas::create([
            'ACT_FIN_Titulo' => $request['ACT_FIN_Titulo'],
            'ACT_FIN_Descripcion' => $request['ACT_FIN_Descripcion'],
            'ACT_FIN_Actividad_Id' => $request['Actividad_Id'],
            'ACT_FIN_Fecha_Finalizacion' => Carbon::now(),
            'ACT_FIN_Revisado' => 1
        ]);
        $af = ActividadesFinalizadas::orderBy('created_at', 'desc')->first();
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
        Actividades::findOrFail($request['Actividad_Id'])->update(['ACT_Estado_Id' => 3]);
        
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 3,
            'HST_EST_Actividad' => $request['Actividad_Id']
        ]);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' ha finalizado una Actividad.',
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'find_in_page'
        );
        return redirect()->route('actividades_cliente')->with('mensaje', 'Actividad finalizada');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aprobarActividad($id)
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
            ->first();
        $documentosSoporte = DB::table('TBL_Documentos_Soporte as d')
            ->join('TBL_Actividades as a', 'a.id', '=', 'd.DOC_Actividad_Id')
            ->where('a.id', '=', $actividadesPendientes->Id_Act)
            ->get();
        $documentosEvidencia = DB::table('TBL_Documentos_Evidencias as d')
            ->join('TBL_Actividades_Finalizadas as a', 'a.id', '=', 'd.DOC_Actividad_Finalizada_Id')
            ->where('a.id', '=', $id)
            ->get();
        $actividadFinalizada = ActividadesFinalizadas::findOrFail($id);
        $respuestasAnteriores = DB::table('TBL_Respuesta as r')
            ->join('TBL_Actividades_Finalizadas as af', 'af.id', '=', 'r.RTA_Actividad_Finalizada_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'r.RTA_Usuario_Id')
            ->select('u.*', 'af.*', 'r.*')
            ->where('af.ACT_FIN_Actividad_Id', '=', $actividadFinalizada->ACT_FIN_Actividad_Id)
            ->where('r.RTA_Titulo', '<>', null)->get();
        $perfil = DB::table('TBL_Usuarios as u')
            ->join('TBL_Actividades as a', 'a.ACT_Trabajador_Id', '=', 'u.id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('a.id', '=', $actividadesPendientes->Id_Act)
            ->first();
        return view('cliente.actividades.aprobacion', compact('actividadesPendientes', 'datos', 'perfil', 'documentosSoporte', 'documentosEvidencia', 'respuestasAnteriores', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function descargarArchivo($ruta)
    {
        $ruta = public_path().'/documentos_soporte/'.$ruta;
        return response()->download($ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaRechazado(Request $request)
    {
        $rtaTest = Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Usuario_Id', '<>', 0)
            ->first();
        if($rtaTest!=null){
            Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
                ->where('RTA_Usuario_Id', '=', 0)
                ->first()
                ->update([
                'RTA_Titulo' => $request->RTA_Titulo,
                'RTA_Respuesta' => $request->RTA_Respuesta,
                'RTA_Estado_Id' => 6,
                'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                'RTA_Fecha_Respuesta' => Carbon::now()
            ]);
            ActividadesFinalizadas::findOrFail($rtaTest->RTA_Actividad_Finalizada_Id)->update([
                'ACT_FIN_Revisado' => 1
            ]);
            $actividad = $this->actividad($request->id);
            HistorialEstados::create([
                'HST_EST_Fecha' => Carbon::now(),
                'HST_EST_Estado' => 6,
                'HST_EST_Actividad' => $actividad->id
            ]);
            Actividades::findOrFail($actividad->id)->update(['ACT_Estado_Id'=>1]);
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $trabajador = DB::table('TBL_Actividades as a')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
                ->where('a.id', '=', $actividad->id)
                ->first();
            Notificaciones::crearNotificacion(
                'El Cliente '.$datos->USR_Nombres_Usuario.' ha rechazado la entrega de la Actividad.',
                session()->get('Usuario_Id'),
                $trabajador->ACT_Trabajador_Id,
                'actividades_perfil_operacion',
                null,
                null,
                'clear'
            );
        }
        return redirect()->route('actividades_cliente')->with('mensaje', 'Respuesta envíada');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaAprobado(Request $request)
    {
        $rtaTest = Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Usuario_Id', '<>', 0)
            ->first();
        if($rtaTest!=null){
            Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
                ->where('RTA_Usuario_Id', '=', 0)
                ->first()
                ->update([
                    'RTA_Titulo' => $request->RTA_Titulo,
                    'RTA_Respuesta' => $request->RTA_Respuesta,
                    'RTA_Estado_Id' => 7,
                    'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                    'RTA_Fecha_Respuesta' => Carbon::now()
                ]);
            ActividadesFinalizadas::findOrFail($rtaTest->RTA_Actividad_Finalizada_Id)->update([
                'ACT_FIN_Revisado' => 1
            ]);
            $actividad = $this->actividad($request->id);
            HistorialEstados::create([
                'HST_EST_Fecha' => Carbon::now(),
                'HST_EST_Estado' => 7,
                'HST_EST_Actividad' => $actividad->id
            ]);
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $trabajador = DB::table('TBL_Actividades as a')
                ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
                ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
                ->where('a.id', '=', $actividad->id)
                ->first();
            Notificaciones::crearNotificacion(
                'El Cliente '.$datos->USR_Nombres_Usuario.' ha aprobado la entrega de la Actividad.',
                session()->get('Usuario_Id'),
                $trabajador->ACT_Trabajador_Id,
                'actividades_perfil_operacion',
                null,
                null,
                'done_all'
            );
            Notificaciones::crearNotificacion(
                'El Cliente '.$datos->USR_Nombres_Usuario.' ha aprobado la entrega una Actividad',
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'cobros_director',
                null,
                null,
                'done_all'
            );
        }
        return redirect()->route('actividades_cliente')->with('mensaje', 'Respuesta envíada');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actividad($id)
    {
        $actividad = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->select('a.id')
            ->where('af.id', '=', $id)
            ->first();

        return $actividad;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
