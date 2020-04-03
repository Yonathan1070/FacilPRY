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
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\SolicitudTiempo;
use Illuminate\Support\Facades\Mail;

/**
 * Actividades Controller, donde se visualizaran y realizaran cambios
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
     * Muestra el listado de actividades en proceso, atrasadas y finalizadas
     * del usuario autenticado
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil();
        
        foreach ($actividadesProceso as $actividad) {
            if (Carbon::now() > $actividad->ACT_Fecha_Fin_Actividad) {
                Actividades::actualizarEstadoActividad($actividad->ID_Actividad, 2);
                HistorialEstados::crearHistorialEstado($actividad->ID_Actividad, 2);
                $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil();
            }
        }

        $actividadesAtrasadas = Actividades::obtenerActividadesAtrasadasPerfil();
        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPerfil();
        
        return view(
            'perfiloperacion.actividades.listar',
            compact(
                'actividadesProceso',
                'actividadesAtrasadas',
                'actividadesFinalizadas',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Muestra el formulario para asignar horas de trabajo
     *
     * @param  $id  Identificador de la actividad
     * @return \Illuminate\View\View Vista de asignacion de horas de trabajo
     */
    public function asignarHoras($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $actividades = HorasActividad::obtenerActividadesHorasAsignacion($id);
        $horas = HorasActividad::obtenerHorasAsignadasActividad($id);

        if ($actividades == null){
            return redirect()
                ->route('actividades_perfil_operacion')
                ->withErrors('La actividad no existe.');
        }
        if ($horas != 0){
            return redirect()
                ->route('actividades_perfil_operacion')
                ->withErrors('Ya se asignaron horas de trabajo a la Actividad.');
        }
        
        $hoy = Carbon::now();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        return view(
            'perfiloperacion.actividades.asignacion',
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
     * Guarda la cantidad de horas asignada&
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador de la hora asignada
     * @return response()->json()
     */
    public function guardarHoras(Request $request, $id)
    {
        $fecha = HorasActividad::findOrFail($id);
        $vigente = Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00');
        
        if (
            Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') <
                Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y')
        ) {
            return response()
                ->json(['msg' => 'errorF']);
        }
        if (
            (Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') ==
                Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y') &&
                Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') <= 1)
            || (Carbon::createFromFormat('Y-m-d', $fecha->HRS_ACT_Fecha_Actividad)->format('d/m/Y') ==
                Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now())->format('d/m/Y') &&
                Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') <
                $request->HRS_ACT_Cantidad_Horas_Asignadas)
        ) {
            return response()
                ->json(['msg' => 'errorF']);
        }

        $horas = HorasActividad::obtenerHorasAsignadasNoSeleccionada($id, $fecha);
        
        if (
            (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 8 &&
                ($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) <= 14)
        ) {
            HorasActividad::actualizarHorasAsignadas(
                $id, $request->HRS_ACT_Cantidad_Horas_Asignadas
            );
            
            return response()
                ->json(['msg' => 'alerta']);
        } else if (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 14) {
            return response()
                ->json(['msg' => 'error']);
        } else {
            HorasActividad::actualizarHorasAsignadas(
                $id, $request->HRS_ACT_Cantidad_Horas_Asignadas
            );
            
            return response()
                ->json(['msg' => 'exito'], 200);
        }
    }

    /**
     * Guarda la asignacion de horas de trabajo
     *
     * @param  $id  Identificador de la actividad
     * @return response()->json()
     */
    public function terminarAsignacion($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $horas = HorasActividad::obtenerHorasAsignadasActividad($id);

        if ($horas != 0) {
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' se ha asignado sus horas de trabajo',
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
                'titulo' => $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha asignado sus horas de trabajo',
                'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
                'contenido' => $para['USR_Nombres_Usuario'].
                    ', revisa la plataforma InkBrutalPry, '.
                    $de['USR_Nombres_Usuario'].
                    ' '.
                    $de['USR_Apellidos_Usuario'].
                    ' a asignado sus horas de trabajo, para que las apruebes.'
            ], function($message) use ($para){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to(
                    $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
                )->subject('Horas de trabajo asignadas');
            });
        }
        
        return response()
            ->json(['msg' => 'exito'], 200);
    }

    /**
     * Genera el PDF de las actividades asignadas en cada uno de sus estados
     *
     * @return PDF->download()
     */
    public function generarPdf()
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($datos->USR_Empresa_Id);
        $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil($hoy);
        $actividadesAtrasadas = Actividades::obtenerActividadesAtrasadasPerfil($hoy);
        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPerfil();

        $pdf = PDF::loadView(
            'includes.pdf.actividades',
            compact(
                'actividadesProceso',
                'actividadesAtrasadas',
                'actividadesFinalizadas',
                'empresa'
            )
        );

        $fileName = 'Actividades' . session()->get('Usuario_Nombre');
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Descarga el archivo cargado cuando se creó la actividad
     *
     * @param  $id  Identificador de la actividad
     * @return response()->download()
     */
    public function descargarDocumentoSoporte($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
            ->join('TBL_Documentos_Soporte as ds', 'ds.DOC_Actividad_Id', '=', 'a.id')
            ->select('ds.ACT_Documento_Soporte_Actividad')
            ->where('a.id', '=', $id)
            ->first();
        if(!$actividad){
            return redirect()
                ->back()
                ->withErrors('No hay documento disponible para descargar');
        }
        return response()
            ->download(
                public_path().
                    '/documentos_soporte/'.
                    $actividad->ACT_Documento_Soporte_Actividad
            );
    }

    /**
     * Vista de la entrega de la actividad
     *
     * @param  $id  Identificador de la actividad
     * @return \Illuminate\View\View Vista de la entrega de la actividad
     */
    public function finalizar($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = HorasActividad::obtenerActividad($id);

        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas($id);

        return view(
            'perfiloperacion.actividades.finalizar',
            compact(
                'id',
                'actividades',
                'respuestasAnteriores',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Guarda la actividad finalizada en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function guardarFinalizar(Request $request)
    {
        if (
            !$request->hasFile('ACT_Documento_Evidencia_Actividad') &&
                !$request['ACT_FIN_Link']
        ) {
            return redirect()
                ->route('actividades_finalizar_perfil_operacion', [$request['Actividad_Id']])
                ->withErrors(
                    'Debe cargar un documento o agregar un link que evidencie la actividad realizada.'
                )->withInput();
        }
        ActividadesFinalizadas::crearActividadFinalizadaTrabajador($request);

        $af = ActividadesFinalizadas::orderBy('created_at', 'desc')->first();
        if ($request->hasFile('ACT_Documento_Evidencia_Actividad')) {
            foreach ($request->file('ACT_Documento_Evidencia_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    DocumentosEvidencias::crearDocumentosEvicendia($af->id, $archivo);
                }
            }
        }
        Respuesta::crearRespuesta($af->id, 4);
        
        Actividades::actualizarEstadoActividad($request['Actividad_Id'], 3);
        
        HistorialEstados::crearHistorialEstado($request['Actividad_Id'], 4);
        
        $para = DB::table('TBL_Permiso as p')
            ->join('TBL_Permiso_Usuario as pu', 'pu.PRM_USR_Permiso_Id', '=', 'p.id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'pu.PRM_USR_Usuario_Id')
            ->where('p.PRM_Nombre_Permiso', '=', 'validador')
            ->select('u.*')
            ->first();
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Tarea finalizada y entregada',
            'nombre' => $para->USR_Nombres_Usuario.' '.$para->USR_Apellidos_Usuario,
            'contenido' => $para->USR_Nombres_Usuario.
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' a realizado la entrega de una tarea y está esperando a ser aprobada.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para->USR_Correo_Usuario, 'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Tarea finalizada y entregada');
        });
        
        return redirect()
            ->route('actividades_perfil_operacion')
            ->with('mensaje', 'Actividad finalizada');
    }

    /**
     * Obtiene los datos de la actividad a solicitar tiempo
     *
     * @param  $id  Identificador de la actividad
     * @return json_encode()
     */
    public function solicitarTiempo($id)
    {
        $actividad = Actividades::findOrFail($id);
        
        return json_encode($actividad);
    }

    /**
     * Guarda y envía la solicitud de tiempo para la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador de la actividad
     * @return redirect()->route()
     */
    public function enviarSolicitud(Request $request, $id){
        SolicitudTiempo::crearSolicitud($id, $request);

        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $para = Usuarios::findOrFail($de->USR_Supervisor_Id);
        
        Notificaciones::crearNotificacion(
            $de->USR_Nombres_Usuario.
                ' '.
                $de->USR_Apellidos_Usuario.
                ' solicita tiempo adicional para entregar una tarea.',
            $de->id,
            $para->id,
            'solicitud_tiempo_actividades',
            'idA',
            $id,
            'alarm'
        );
        Mail::send('general.correo.informacion', [
            'titulo' => $de->USR_Nombres_Usuario.
                ' '.
                $de->USR_Apellidos_Usuario.
                ' solicita tiempo adicional para entregar una tarea.',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' solicita tiempo adicional para entregar una tarea.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'],
                'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Solicitud de tiempo');
        });
        
        return redirect()
            ->route('actividades_perfil_operacion')
            ->with('mensaje', 'Solicitud enviada');
    }
}
