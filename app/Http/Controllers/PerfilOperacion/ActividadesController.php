<?php

namespace App\Http\Controllers\PerfilOperacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Carbon;
use App\Models\Tablas\HorasActividad;
use PDF;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\DocumentosSoporte;
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
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );
        
        foreach ($actividadesProceso as $actividad) {
            if (Carbon::now() > $actividad->ACT_Fecha_Fin_Actividad 
                && $actividad->ACT_Costo_Estimado_Actividad == 0 
                && $actividad->ACT_Costo_Real_Actividad == 0
                && $actividad->ACT_FIN_Titulo == null
            ) {
                Actividades::actualizarEstadoActividad($actividad->ID_Actividad, 2);
                HistorialEstados::crearHistorialEstado($actividad->ID_Actividad, 2);
                $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil($idUsuario);
            }
        }

        $actividadesAtrasadas = Actividades::obtenerActividadesAtrasadasPerfil(
            $idUsuario
        );

        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPerfilOchoDias(
            $idUsuario
        );
        
        return view(
            'perfiloperacion.actividades.listar',
            compact(
                'actividadesProceso',
                'actividadesAtrasadas',
                'actividadesFinalizadas',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
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
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $actividades = HorasActividad::obtenerActividadesHorasAsignacion(
            $id,
            $idUsuario
        );
        
        $horas = HorasActividad::obtenerHorasAsignadasActividad($id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

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
        $datos = Usuarios::findOrFail($idUsuario);
        
        return view(
            'perfiloperacion.actividades.asignacion',
            compact(
                'id',
                'actividades',
                'datos',
                'horas',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda la cantidad de horas asignada
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador de la hora asignada
     * @return response()->json()
     */
    public function guardarHoras(Request $request, $id)
    {
        $fecha = HorasActividad::findOrFail($id);
        $formatoFechaActividad = Carbon::createFromFormat('Y-m-d H:s:i', $fecha->HRS_ACT_Fecha_Actividad.' 23:59:59');
        $formatoFechaHoy = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());

        if (
            $formatoFechaActividad->lt($formatoFechaHoy)
        ) {
            return response()
                ->json(['msg' => 'errorF']);
        }
        
        if (
            ($formatoFechaActividad->format('d/m/Y') ==
                $formatoFechaHoy->format('d/m/Y') &&
                Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') <= 1)
            || ($formatoFechaActividad->format('d/m/Y') ==
                $formatoFechaHoy->format('d/m/Y') &&
                Carbon::now()->diffInHours($fecha->HRS_ACT_Fecha_Actividad.' 23:59:00') <
                $request->HRS_ACT_Cantidad_Horas_Asignadas)
        ) {
            return response()
                ->json(['msg' => 'errorH']);
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
        $idUsuario = session()->get('Usuario_Id');
        $horas = HorasActividad::obtenerHorasAsignadasActividad($id);
        $actividad = Actividades::findOrFail($id);
        
        if ($horas != 0) {
            $para = Usuarios::findOrFail($actividad->ACT_Encargado_Id);
            $de = Usuarios::findOrFail($idUsuario);

            Notificaciones::crearNotificacion(
                $de->USR_Nombres_Usuario.
                    ' '.
                    $de->USR_Apellidos_Usuario.
                    ' se ha asignado sus horas de trabajo',
                $de->id,
                $para->id,
                'aprobar_horas_actividad',
                'idH',
                $id,
                'alarm'
            );
            
            Mail::send('general.correo.informacion', [
                'nombre' => $de['USR_Nombre_Usuario'],
                'contenido' => 'Asignó sus horas de trabajo, para que las apruebes.'
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
        $idUsuario = session()->get('Usuario_Id');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($datos->USR_Empresa_Id);
        
        $actividadesProceso = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );
        
        $actividadesAtrasadas = Actividades::obtenerActividadesAtrasadasPerfil(
            $idUsuario
        );

        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPerfil(
            $idUsuario
        );

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
        $actividad = DocumentosSoporte::obtenerDocumentoSoporte($id);
        
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
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        $actividades = HorasActividad::obtenerActividad(
            $id,
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas($id);

        return view(
            'perfiloperacion.actividades.finalizar',
            compact(
                'id',
                'actividades',
                'respuestasAnteriores',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
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
        
        $actividad = Actividades::findOrFail($request['Actividad_Id']);
        
        $para = Usuarios::findOrFail($actividad->ACT_Encargado_Id);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));

        Notificaciones::crearNotificacion(
            $de->USR_Nombres_Usuario.
                ' '.
                $de->USR_Apellidos_Usuario.
                ' ha finalizado la tarea '.$actividad->ACT_Titulo_Actividad.'.',
            $de->id,
            $para->id,
            'inicio_validador',
            null,
            null,
            'done_all'
        );
        
        Mail::send('general.correo.informacion', [
            'nombre' => $de->USR_Nombre_Usuario,
            'contenido' => 'Realizó la entrega de la tarea '.$actividad->ACT_Nombre_Actividad.' y está esperando a ser aprobada.'
        ], function($message) use ($para, $actividad){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para->USR_Correo_Usuario, 'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Tarea '.$actividad->ACT_Nombre_Actividad.' finalizada y entregada');
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
    public function enviarSolicitud(Request $request, $id)
    {
        $actividad = Actividades::findOrFail($id);
        SolicitudTiempo::crearSolicitud($id, $request);

        HorasActividad::crearHorasActividadConHora(
            $id,
            Carbon::now(),
            $request->Hora_Solicitud
        );

        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $para = Usuarios::findOrFail($actividad->ACT_Encargado_Id);
        
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
            'nombre' => $de['USR_Nombre_Usuario'],
            'contenido' => 'Solicita tiempo adicional para entregar la tarea '.$actividad->ACT_Nombre_Actividad.'.'
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
