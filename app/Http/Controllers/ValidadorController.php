<?php

namespace App\Http\Controllers;

use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\Usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('validador');
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
            ->where('re.RTA_Titulo', '=', null)
            ->where('a.ACT_Estado_Id', '=', 3)
            ->where('re.RTA_Estado_Id', '=', 4)
            ->get();
        return view('tester.inicio', compact('actividadesPendientes', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aprobacionActividad($id)
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
        return view('tester.aprobacion', compact('actividadesPendientes', 'perfil', 'datos', 'documentosSoporte', 'documentosEvidencia', 'respuestasAnteriores', 'notificaciones', 'cantidad'));
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
        Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Titulo', '=', null)
            ->first()
            ->update([
                'RTA_Titulo'=>$request->RTA_Titulo,
                'RTA_Respuesta' => $request->RTA_Respuesta,
                'RTA_Estado_Id' => 6,
                'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                'RTA_Fecha_Respuesta' => Carbon::now()
            ]);
        ActividadesFinalizadas::findOrFail($request->id)->update([
            'ACT_FIN_Revisado' => 1
        ]);
        $actividad = $this->actividad($request->id);
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 6,
            'HST_EST_Actividad' => $actividad->id
        ]);
        Actividades::findOrFail($actividad->id)->update(['ACT_Estado_Id'=>1]);
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 1,
            'HST_EST_Actividad' => $actividad->id
        ]);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $trabajador = DB::table('TBL_Actividades as a')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->where('a.id', '=', $actividad->id)
            ->first();
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' ha rechazado la entrega de la Actividad.',
            session()->get('Usuario_Id'),
            $trabajador->ACT_Trabajador_Id,
            'actividades_perfil_operacion',
            null,
            null,
            'clear'
        );
        return redirect()->route('inicio_validador')->with('mensaje', 'Respuesta envíada');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaAprobado(Request $request)
    {
        Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Titulo', '=', null)
            ->first()
            ->update([
                'RTA_Titulo'=>$request->RTA_Titulo,
                'RTA_Respuesta' => $request->RTA_Respuesta,
                'RTA_Estado_Id' => 5,
                'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                'RTA_Fecha_Respuesta' => Carbon::now()
            ]);
        ActividadesFinalizadas::findOrFail($request->id)->update([
            'ACT_FIN_Revisado' => 1
        ]);
        Respuesta::create([
            'RTA_Actividad_Finalizada_Id' => $request->id,
            'RTA_Estado_Id' => 4
        ]);
        $idActFin = ActividadesFinalizadas::orderBy('created_at')->first()->id;
        $actividad = $this->actividad($request->id);
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 5,
            'HST_EST_Actividad' => $actividad->id
        ]);
        Actividades::findOrFail($actividad->id)->update(['ACT_Estado_Id'=>3]);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $trabajador = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->where('a.id', '=', $actividad->id)
            ->first();
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' ha aprobado la entrega de la Actividad.',
            session()->get('Usuario_Id'),
            $trabajador->ACT_Trabajador_Id,
            'actividades_perfil_operacion',
            null,
            null,
            'done_all'
        );
        Notificaciones::crearNotificacion(
            'Se ha finalizado una actividad del proyecto '.$trabajador->PRY_Nombre_Proyecto,
            session()->get('Usuario_Id'),
            $trabajador->PRY_Cliente_Id,
            'aprobar_actividad_cliente',
            'id',
            $idActFin,
            'info'
        );
        return redirect()->route('inicio_validador')->with('mensaje', 'Respuesta envíada');
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
