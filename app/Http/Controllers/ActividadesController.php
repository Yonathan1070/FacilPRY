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
        $idUsuario = session()->get('Usuario_Id');
        $permisos = [
            'crear' => can2('crear-actividades'),
            'crearC' => can2('crear-actividades-cliente'),
            'listarP' => can2('listar-proyectos')
        ];

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil($idUsuario);

        $requerimiento = Requerimientos::findOrFail($idR);

        $requerimientos = Requerimientos::where(
            'REQ_Proyecto_Id', '=', $requerimiento['REQ_Proyecto_Id']
        )->get();
        
        if (count($requerimientos) <= 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'No se pueden asignar actividades si no hay requerimientos previamente'.
                        ' registrados.'
                );
        }
        
        $datos = Usuarios::findOrFail($idUsuario);
        $cliente = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);
        $actividades = Actividades::obtenerActividades($idR, $cliente);
        
        foreach ($actividades as $actividad) {
            if (Carbon::now() > $actividad->ACT_Fecha_Fin_Actividad && $actividad->ACT_Costo_Estimado_Actividad == 0 && $actividad->ACT_Costo_Real_Actividad == 0) {
                Actividades::actualizarEstadoActividad($actividad->ID_Actividad, 2);
                HistorialEstados::crearHistorialEstado($actividad->ID_Actividad, 2);
                $actividades = Actividades::obtenerActividades($idR, $cliente);
            }
        }

        $actividadesCliente = Actividades::obtenerActividadesCliente($idR, $cliente);
        $proyecto = Proyectos::findOrFail($requerimiento['REQ_Proyecto_Id']);
        
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
     * Muestra el listado de las todas las actividades por proyecto
     *
     * @param  $idP Identificador del proyecto
     * @return \Illuminate\View\View Vista del listado de actividades
     */
    public function todas($idP)
    {
        can('listar-actividades');

        $idUsuario = session()->get('Usuario_Id');
        $permisos = [
            'listarP' => can2('listar-proyectos')
        ];

        $datos = Usuarios::findOrFail($idUsuario);
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil($idUsuario);
        
        $proyecto = Proyectos::findOrFail($idP);

        $actividades = Actividades::obtenerTodasActividadesProyecto($idP);
        
        if (count($actividades) <= 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'No se encuentran tareas registradas'
                );
        }
        
        return view(
            'actividades.todas',
            compact(
                'actividades',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas',
                'proyecto'
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

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

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

        $idUsuario = session()->get('Usuario_Id');
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );
        
        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $requerimiento = Requerimientos::findOrFail($idR);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();
        
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
            $request['ACT_Fecha_Inicio_Actividad'] < $hoy->format('yy-m-d') || $request['ACT_Fecha_Fin_Actividad'] < $hoy->format('yy-m-d')
        ) {
            return redirect()
                ->route($request['ruta'], [$idR])
                ->withErrors(
                    'Las fechas no pueden ser dias ya pasados.'
                )->withInput();
        } else if (
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

        $actividades = Actividades::obtenerActividadesTotalesRequerimiento(
            $idR
        );

        foreach ($actividades as $actividad) {
            if (strtolower($actividad->ACT_Nombre_Actividad) == strtolower($request->ACT_Nombre_Actividad)) {
                return redirect()
                    ->route($request['ruta'], [$idR])
                    ->withErrors('La actividad ya cuenta con una tarea del mismo nombre.')
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
        
        Actividades::crearActividad(
            $request,
            $idR,
            $idUsuario,
            session()->get('Usuario_Id')
        );

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
            'nombre' => $de['USR_Nombre_Usuario'],
            'contenido' => 'Ha creado la actividad '.
                $request['ACT_Nombre_Actividad'].
                ' y se la ha asignado.'
        ], function($message) use ($para, $request){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )
                ->subject($request['ACT_Nombre_Actividad']);
        });

        return redirect()
            ->route($ruta, [$idR])
            ->with('mensaje', 'Tarea agregada con exito');
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
    public function detalleActividad(Request $request)
    {
        $idUsuario = session()->get('Usuario_Id');
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );
        
        $datos = Usuarios::findOrFail($idUsuario);
        
        $detalle = Actividades::obtenerActividadDetalle($request->idActividad);
        
        $documentosSoporte = DocumentosSoporte::obtenerDocumentosSoporte(
            $request->idActividad
        );

        $documentosEvidencia = DocumentosEvidencias::obtenerDocumentosEvidencia(
            $detalle->Id_Act_Fin
        );
        
        $perfil = Usuarios::obtenerPerfilOperacionActividad(
            $request->idActividad
        );

        $actividadFinalizada = ActividadesFinalizadas::findOrFail(
            $detalle->Id_Act_Fin
        );

        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas(
            $actividadFinalizada->ACT_FIN_Actividad_Id
        );
        
        return view(
            'actividades.detalle',
            compact(
                'detalle',
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
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

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

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $actividad = Actividades::findOrFail($idA);
        $requerimiento = Requerimientos::findOrFail($actividad->ACT_Requerimiento_Id);
        $proyecto = Proyectos::findOrFail($requerimiento->REQ_Proyecto_Id);
        
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
        
        if (
            $request['ACT_Fecha_Inicio_Actividad'] < $hoy->format('yy-m-d') || $request['ACT_Fecha_Fin_Actividad'] < $hoy->format('yy-m-d')
        ) {
            return redirect()
                ->route($request['ruta'], [$idA])
                ->withErrors(
                    'Las fechas no pueden ser dias ya pasados.'
                )->withInput();
        } else if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
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
                    $documentoBD = DocumentosSoporte::where('DOC_Actividad_Id', '=', $idA)
                        ->first();
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
            'nombre' => $de['USR_Nombre_Usuario'],
            'contenido' => 'Ha modificado la información de la actividad '.
                $request['ACT_Nombre_Actividad'].
                ' y se la ha asignado.'
        ], function($message) use ($para, $request){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject($request['ACT_Nombre_Actividad']);
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
    public function cambiarRequerimiento(Request $request, $idA)
    {
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
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        $horasAprobar = HorasActividad::obtenerHorasAprobar($idH);

        if (count($horasAprobar) == 0) {
            return redirect()
                ->back()
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
    public function solicitudTiempo($idA)
    {
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
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
    public function aprobarSolicitud($idS)
    {
        $solicitud = SolicitudTiempo::obtenerSolicitudTiempo($idS);
        
        Actividades::actualizarFechaFin($solicitud);

        $horas = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $solicitud->Id_Actividad)
            ->orderBy('id', 'desc')
            ->first();
        $horas->update([
            'HRS_ACT_Cantidad_Horas_Reales' => $horas->HRS_ACT_Cantidad_Horas_Asignadas
        ]);
        
        HistorialEstados::crearHistorialEstado($solicitud->Id_Actividad, 1);

        SolicitudTiempo::findOrFail($idS)->update(['SOL_TMP_Estado_Solicitud' => 1]);

        $para = Usuarios::findOrFail($solicitud->ACT_Trabajador_Id);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        Notificaciones::crearNotificacion(
            'Solicitud aprobada, ya puede realizar la entrega de la tarea.',
            $de->id,
            $para->id,
            'actividades_perfil_operacion',
            null,
            null,
            'add_to_photos'
        );
        
        Mail::send('general.correo.informacion', [
            'nombre' => $de['USR_Nombre_Usuario'],
            'contenido' => 'Aprobó su solicitud de tiempo, realiza la entrega de la tarea.'
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
            'nombre' => $de['USR_Nombre_Usuario'],
            'contenido' => 'Aprobó tus horas de trabajo, ya tienes acceso  a la entrega de la tarea.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'],
                'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Horas de trabajo aprobadas');
        });
        return response()->json(['msg' => 'exito', 'idPerfil' => $para->id]);
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