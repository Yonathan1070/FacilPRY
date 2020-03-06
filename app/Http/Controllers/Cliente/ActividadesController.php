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
use Illuminate\Support\Facades\Mail;

/**
 * Requerimientos Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los requerimientos de cada proyecto
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class ActividadesController extends Controller
{
    /**
     * Muestra el listado de las actividades que el cliente tenga para entregar,
     * pendientes para aprobación o que ya haya entregado.
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $actividadesPendientes = ActividadesFinalizadas::obtenerActividadesAprobar();
        $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesFinalizadasCliente();
        $actividadesEntregar = ActividadesFinalizadas::obtenerActividadesProcesoCliente();
        
        return view(
            'cliente.actividades.inicio',
            compact(
                'actividadesPendientes',
                'actividadesFinalizadas',
                'actividadesEntregar',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Muestra el formulario para realizar la entrega de la actividad
     *
     * @return \Illuminate\View\View Vista del formulario de entrega
     */
    public function finalizar($id){
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = Actividades::obtenerActividad($id);

        return view(
            'cliente.actividades.finalizar',
            compact(
                'id',
                'actividades',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Guarda la entrega de la actividad la entrega de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function guardarFinalizar(Request $request)
    {
        $horas = HorasActividad::obtenerHorasActividad($request['Actividad_Id']);
        $hR = count($horas) - Carbon::now()->diffInDays(
            $horas->first()->HRS_ACT_Fecha_Actividad
        );
        HorasActividad::actualizarHoraActividad(count($horas), $hR, $horas->first()->id);
        
        if (!$request->hasFile('ACT_Documento_Evidencia_Actividad')) {
            return redirect()
                ->route('actividades_finalizar_cliente', [$request['Actividad_Id']])
                ->withErrors(
                    'Debe cargar un documento que evidencie la actividad realizada.'
                )->withInput();
        }

        ActividadesFinalizadas::crearActividadFinalizada($request);

        $af = ActividadesFinalizadas::orderBy('created_at', 'desc')->first();
        
        foreach ($request->file('ACT_Documento_Evidencia_Actividad') as $documento) {
            $archivo = null;
            if ($documento->isValid()) {
                $archivo = time() . '.' . $documento->getClientOriginalName();
                $documento->move(public_path('documentos_soporte'), $archivo);
                DocumentosEvidencias::crearDocumentosEvicendia($af->id, $archivo);
            }
        }
        Actividades::actualizarEstadoActividad($request['Actividad_Id'], 3);
        
        HistorialEstados::crearHistorialEstado($request['Actividad_Id'], 3);
        
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
        
        return redirect()
            ->route('actividades_cliente')
            ->with('mensaje', 'Actividad finalizada');
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
            $para = Usuarios::findOrFail($trabajador->ACT_Trabajador_Id);
            $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
            Mail::send('general.correo.informacion', [
                'titulo' => 'El Cliente '.$datos->USR_Nombres_Usuario.' ha rechazado la entrega de la Actividad.',
                'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
                'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' a rechazado la entrega de la tarea.'
            ], function($message) use ($para){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                    ->subject('Entrega de la tarea, rechazada');
            });
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
                'El Cliente '.$datos->USR_Nombres_Usuario.' ha aprobado la entrega una tarea',
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'cobros',
                null,
                null,
                'done_all'
            );
            $para = Usuarios::findOrFail($datos->USR_Supervisor_Id);
            $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
            Mail::send('general.correo.informacion', [
                'titulo' => 'El Cliente '.$datos->USR_Nombres_Usuario.' ha aprobado la tarea entregada',
                'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
                'contenido' => $para['USR_Nombres_Usuario'].', revisa la plataforma InkBrutalPry, '.$de['USR_Nombres_Usuario'].' '.$de['USR_Apellidos_Usuario'].' ha aprobado la tarea entregada.'
            ], function($message) use ($para){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to($para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos')
                    ->subject('Tarea finalizada');
            });
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
