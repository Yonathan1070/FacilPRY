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
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\SolicitudTiempo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

/**
 * Actividades Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de las Actividades
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
     * Muestra el listado de las Actividades por requerimiento
     *
     * @param  $idR Identificador del requerimiento
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index($idR)
    {
        can('listar-actividades');
        $permisos = [
            'crear' => can2('crear-actividades'),
            'crearC' => can2('crear-actividades-cliente'),
            'listarP' => can2('listar-proyectos')
        ];
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $requerimiento = Requerimientos::findOrFail($idR);
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $requerimiento['REQ_Proyecto_Id'])->get();
        
        if (count($requerimientos) <= 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'No se pueden asignar actividades si no hay requerimientos previamente'.
                        ' registrados.'
                );
        }
        
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);
        $actividades = Actividades::obtenerActividades($idR, $cliente);
        $actividadesCliente = Actividades::obtenerActividadesCliente($idR, $cliente);
        $proyecto = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        return view(
            'actividades.listar',
            compact(
                'actividades',
                'actividadesCliente',
                'proyecto',
                'requerimiento',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'requerimientos',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear las actividades a un Perfil de Operación
     *
     * @param  $idR Identificador del requerimiento
     * @return \Illuminate\View\View Vista para crear actividad al Perfil de Operación
     */
    public function crearTrabajador($idR)
    {
        can('crear-actividades');
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        return view(
            'actividades.crear',
            compact(
                'proyecto',
                'requerimiento',
                'perfilesOperacion',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear las actividades al cliente
     *
     * @param  $idR Identificador del requerimiento
     * @return \Illuminate\View\View Vista para crear actividad al Cliente
     */
    public function crearCliente($idR)
    {
        can('crear-actividades-cliente');
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();
        
        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));

        return view(
            'actividades.crear',
            compact(
                'proyecto',
                'requerimiento',
                'perfilesOperacion',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda las actividades en la Base de datos
     *
     * @param  App\Http\Requests\ValidacionActividad  $request
     * @param  $idR  Identificador del requerimiento
     * @return redirect()->route()
     */
    public function guardar(ValidacionActividad $request, $idR)
    {
        $hoy = Carbon::now();
        $diferencia = $hoy->diffInMinutes($request['ACT_Hora_Entrega']);
        
        if (
            $request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']
        ) {
            return redirect()
                ->route($request['ruta'], [$idR])
                ->withErrors(
                    'La fecha de inicio no puede ser superior a la fecha de finalización'
                )->withInput();
        } else if (
            ($request['ACT_Fecha_Inicio_Actividad'] == $request['ACT_Fecha_Fin_Actividad']) &&
            ($diferencia < 60 || $diferencia > 600)
        ) {
            return redirect()
                ->route($request['ruta'], [$idR])
                ->withErrors(
                    'La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas'
                )->withInput();
        }
        
        $actividades = Actividades::obtenerActividadesProyecto(
            $request->ACT_Proyecto_Id
        );

        foreach ($actividades as $actividad) {
            if ($actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad) {
                return redirect()
                    ->route($request['ruta'], [$idR])
                    ->withErrors('Ya hay registrada una actividad con el mismo nombre.')
                    ->withInput();
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
        
        Actividades::crearActividad($request, $idR, $idUsuario, session()->get('Usuario_Id'));

        $actividad = Actividades::orderByDesc('created_at')->take(1)->first();

        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            foreach ($request->file('ACT_Documento_Soporte_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    DocumentosSoporte::crearDocumentoSoporte($actividad->id, $archivo);
                } else {
                    $actividad->destroy();
                }
            }
        }

        $rangos = $this->obtenerFechasRango(
            $request['ACT_Fecha_Inicio_Actividad'], $request['ACT_Fecha_Fin_Actividad']
        );

        foreach ($rangos as $fecha) {
            HorasActividad::crearHorasActividad($actividad->id, $fecha);
        }

        HistorialEstados::crearHistorialEstado($actividad->id, 1);
        
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
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' le ha asignado la tarea '.
                $request['ACT_Nombre_Actividad']
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )
                ->subject('Tarea Asignada');
        });

        return redirect()
            ->route($ruta, [$idR])
            ->with('mensaje', 'Actividad agregada con exito');
    }

    #Función que retorna la lista de los días desde el inicio hasta la entrega de la actividad
    private function obtenerFechasRango($fechaInicio, $fechaFin)
    {
        $inicio = Carbon::createFromFormat('Y-m-d', substr($fechaInicio, 0, 10));
        $fin = Carbon::createFromFormat('Y-m-d', substr($fechaFin, 0, 10));
        $fechas = [];
        while ($inicio->lte($fin)) {
            $fechas[] = $inicio->copy()->format('Y-m-d');
            $inicio->addDay();
        }

        return $fechas;
    }

    /**
     * Guarda las actividades en la Base de datos
     *
     * @param  $id  Identificador de la actividad
     * @return redirect()->route()
     */
    public function detalleActividad($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $actividadesPendientes = Actividades::obtenerActividadPendiente($id);

        $documentosSoporte = DocumentosSoporte::obtenerDocumentosSoporte($id);
        $documentosEvidencia = DocumentosEvidencias::obtenerDocumentosEvidencia($id);

        $perfil = Usuarios::obtenerPerfilOperacionActividad($actividadesPendientes->Id_Act);
        $actividadFinalizada = ActividadesFinalizadas::findOrFail($id);
        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas($actividadFinalizada->ACT_FIN_Actividad_Id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        return view(
            'actividades.detalle',
            compact(
                'actividadesPendientes',
                'perfil',
                'datos',
                'documentosSoporte',
                'documentosEvidencia',
                'respuestasAnteriores',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para editar la actividad del perfil de operación
     *
     * @param  $idA  Identificador de la actividad
     * @return \Illuminate\View\View Vista para editar actividad al Perfil de operación
     */
    public function editarTrabajador($idA)
    {
        can('editar-actividades');
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));

        return view(
            'actividades.editar',
            compact(
                'actividad',
                'datos',
                'notificaciones',
                'cantidad',
                'perfilesOperacion',
                'proyecto',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para editar la actividad del cliente
     *
     * @param  $idA  Identificador de la actividad
     * @return \Illuminate\View\View Vista para editar actividad al Cliente
     */
    public function editarCliente($idA)
    {
        can('editar-actividades');
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        return view(
            'actividades.editar',
            compact(
                'actividad',
                'datos',
                'notificaciones',
                'cantidad',
                'proyecto',
                'asignadas'
            )
        );
    }

    /**
     * Actualza los datos de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idA  Identificador de la actividad
     * @return redirect()->route()
     */
    public function actualizar(Request $request, $idA)
    {
        $hoy = Carbon::now();
        $diferencia = $hoy->diffInMinutes($request['ACT_Hora_Entrega']);
        
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()
                ->route($request['ruta'], [$idA])
                ->withErrors(
                    'La fecha de inicio no puede ser superior a la fecha de finalización'
                )->withInput();
        } else if (
            ($request['ACT_Fecha_Inicio_Actividad'] == $request['ACT_Fecha_Fin_Actividad']) &&
            ($diferencia < 60 || $diferencia > 600)
        ) {
            return redirect()
                ->route($request['ruta'], [$idA])
                ->withErrors(
                    'La hora de entrega debe ser mínimo de 1 hora y máximo de 10 horas'
                )->withInput();
        }

        $actividades = Actividades::obtenerActividadesNoActual(
            $request->ACT_Proyecto_Id, $idA
        );

        foreach ($actividades as $actividad) {
            if (
                $actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad && 
                    $actividad->Actividad_Id == $idA
            ) {
                return redirect()
                ->route($request['ruta'], [$idA])
                ->withErrors('Ya hay registrada una actividad con el mismo nombre.')
                ->withInput();
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
        
        $actividad = Actividades::actualizarActividad($request, $idA, $idUsuario);

        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            foreach ($request->file('ACT_Documento_Soporte_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    $documentoBD = DocumentosSoporte::where('DOC_Actividad_Id', '=', $idA)->first();
                    if ($documentoBD == null) {
                        DocumentosSoporte::crearDocumentoSoporte($idA, $archivo);
                    }else{
                        DocumentosSoporte::actualizarDocumentoSoporte($documentoBD, $archivo);
                    }
                } else {
                    $actividad->destroy();
                }
            }
        }

        HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->delete();
        
        $rangos = $this->obtenerFechasRango(
            $request['ACT_Fecha_Inicio_Actividad'], $request['ACT_Fecha_Fin_Actividad']
        );

        foreach ($rangos as $fecha) {
            HorasActividad::crearHorasActividad($idA, $fecha);
        }

        HistorialEstados::crearHistorialEstado($idA, 1);
        
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
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' le ha asignado una Tarea'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Tarea Asignada');
        });
        
        $actividad = Actividades::findOrFail($idA);
        
        return redirect()
            ->route('actividades', [$actividad->ACT_Requerimiento_Id])
            ->with('mensaje', 'Actividad editada con exito');
    }

    /**
     * Actualza los datos de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idA  Identificador de la actividad
     * @return response()->json()
     */
    public function cambiarRequerimiento(Request $request, $idA){
        if ($request->ajax()) {
            try {
                Actividades::actualizarRequerimientoActividad($idA, $request);
                return response()->json(['mensaje' => 'ok']);
            } catch(QueryException $qe) {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Elimina la actividad de la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idA  Identificador de la actividad
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $idA)
    {
        if (!can('eliminar-actividades')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    DocumentosSoporte::where('DOC_Actividad_Id', '=', $idA)->delete();
                    HistorialEstados::where('HST_EST_Actividad', '=', $idA)->delete();
                    HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)->delete();
                    Actividades::destroy($idA);
                    
                    return response()->json(['mensaje' => 'ok']);
                } catch(QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }

    /**
     * Muestra la vista detallada para aprobar las horas de trabajo asignadas
     *
     * @param  $idH  Identificador de la actividad
     * @return \Illuminate\View\View Vista para aprobar las Horas de Trabajo
     */
    public function aprobarHoras($idH)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $horasAprobar = HorasActividad::obtenerHorasAprobar($idH);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        if (count($horasAprobar) == 0) {
            return redirect()
                ->route('inicio_director')
                ->with('mensaje', 'Las horas de trabajo ya han sido aprobadas.');
        }
        return view(
            'actividades.aprobar',
            compact(
                'horasAprobar',
                'notificaciones',
                'cantidad',
                'datos',
                'asignadas'
            )
        );
    }

    /**
     * Muestra la vista con los detalles de la solicitud de tiempo
     *
     * @param  $idA  Identificador de la actividad
     * @return \Illuminate\View\View Vista para aprobar la solicitud de tiempo
     */
    public function solicitudTiempo($idA){
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        $solicitud = SolicitudTiempo::obtenerSolicitudTiempoActividad($idA);
        if ($solicitud) {
            return view(
                'actividades.solicitud',
                compact(
                    'solicitud',
                    'notificaciones',
                    'cantidad',
                    'datos',
                    'asignadas'
                ));
        }
        return redirect()->back()->withErrors('La solicitud ya ha sido atendida.');
    }

    /**
     * Aprueba la solicitud de tiempo para la actividad
     *
     * @param  $idS  Identificador de la solicitud
     * @return redirect()->route()->with()
     */
    public function aprobarSolicitud($idS){
        $solicitud = SolicitudTiempo::obtenerSolicitudTiempo($idS);
        
        Actividades::actualizarFechaFin($solicitud);

        $rangos = $this->obtenerFechasRango(
            $solicitud->ACT_Fecha_Inicio_Actividad, $solicitud->SOL_TMP_Fecha_Solicitada
        );

        HorasActividad::where('HRS_ACT_Actividad_Id', '=', $solicitud->Id_Actividad)->delete();
        
        foreach ($rangos as $fecha) {
            HorasActividad::crearHorasActividad($solicitud->Id_Actividad, $fecha);
        }
        HistorialEstados::crearHistorialEstado($solicitud->Id_Actividad, 1);

        SolicitudTiempo::findOrFail($idS)->update(['SOL_TMP_Estado_Solicitud' => 1]);

        $para = Usuarios::findOrFail($solicitud->ACT_Trabajador_Id);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        Notificaciones::crearNotificacion(
            'Solicitud aprobada, ya puede reasignar sus horas de trabajo',
            $de->id,
            $para->id,
            'actividades_perfil_operacion',
            null,
            null,
            'add_to_photos'
        );
        
        Mail::send('general.correo.informacion', [
            'titulo' => 'Solicitud aprobada',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' ha aprobado su solicitud de tiempo, asigna tus horas de trabajo'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Solicitud de tiempo aprobada');
        });
        
        return redirect()
            ->route('actividades', ['idR' => $solicitud->ACT_Requerimiento_Id])
            ->with('mensaje', 'Solicitud aprobada.');
    }

    /**
     * Actualiza las horas de trabajo
     *
     * @param  $idH  Identificador de la fecha para actualizar la hora asignada
     * @return response()->json()
     */
    public function actualizarHoras(Request $request, $idH)
    {
        $fecha = HorasActividad::findOrFail($idH);
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id');
        $trabajador = $actividades->where('ha.id', '=', $idH)->first();
        $horas = HorasActividad::obtenerHorasAsignadas(
            $actividades,
            $fecha,
            $trabajador,
            $idH
        );
        
        $horaModif = HorasActividad::findOrFail($idH);
        
        if (
            ($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 8 &&
            ($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) <= 18
        ) {
            HorasActividad::actualizarHoraRealActividad($request, $idH);
            
            return response()->json(['msg' => 'alerta']);
        } else if (($horas + $request->HRS_ACT_Cantidad_Horas_Asignadas) > 18) {
            return response()->json(['msg' => 'error']);
        } else if (
            $horaModif->HRS_ACT_Cantidad_Horas_Asignadas != 0 &&
            $request->HRS_ACT_Cantidad_Horas_Asignadas == 0
        ){
            return response()->json(['msg' => 'cero']);
        }

        HorasActividad::actualizarHorasReales($idH, $request);
        
        return response()->json(['msg' => 'exito']);
    }

    /**
     * Finalizar la aprobación de las horas de trabajo
     *
     * @param  $idA  Identificador de la actividad
     * @return response()->json()
     */
    public function finalizarAprobacion($idA)
    {
        $horasActividades = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $idA)
            ->get();
        $trabajador = Actividades::findOrFail(
            $horasActividades->first()->HRS_ACT_Actividad_Id
        );
        
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
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' a aprobado tus horas de trabajo, ya tienes acceso  a la entrega de la tarea.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'],
                'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Horas de trabajo aprobadas');
        });
        return response()->json(['msg' => 'exito']);
    }

    /**
     * Obtiene los detalles de la actividad para visualizarlos en un modal
     *
     * @param  $id  Identificador de la actividad
     * @return response()->json()
     */
    public function detalleActividadModal($id)
    {
        $actividad = Actividades::obtenerDetalleActividad($id);

        return json_encode($actividad);
    }
}
