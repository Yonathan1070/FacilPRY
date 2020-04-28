<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\Usuarios;
use Carbon\Carbon;
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
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail(
            $idUsuario
        );
        
        $actividadesPendientes = ActividadesFinalizadas::obtenerActividadesAprobar(
            $idUsuario
        );
        $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesFinalizadasCliente(
            $idUsuario
        );
        $actividadesEntregar = ActividadesFinalizadas::obtenerActividadesProcesoCliente(
            $idUsuario
        );
        
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
    public function finalizar($id)
    {
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail(
            $idUsuario
        );
        $actividades = Actividades::obtenerActividad(
            $id,
            $idUsuario
        );

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
        $idUsuario = session()->get('Usuario_Id');

        $horas = HorasActividad::obtenerHorasActividad($request['Actividad_Id']);
        
        $hR = count($horas) - Carbon::now()->diffInDays(
            $horas->first()->HRS_ACT_Fecha_Actividad
        );
        
        HorasActividad::actualizarHoraActividad(
            count($horas),
            $hR,
            $horas->first()->id
        );
        
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
                DocumentosEvidencias::crearDocumentosEvicendia(
                    $af->id,
                    $archivo
                );
            }
        }

        Actividades::actualizarEstadoActividad(
            $request['Actividad_Id'],
            3
        );
        
        HistorialEstados::crearHistorialEstado(
            $request['Actividad_Id'],
            3
        );
        
        $datos = Usuarios::findOrFail($idUsuario);
        
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' ha finalizado una Actividad.',
            $idUsuario,
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
     * Vista detallada de la actividad entregada
     *
     * @param  $id  Identificador de la actividad finalizada
     * @return \Illuminate\View\View Vista detallada de la entrega de la actividad>>
     */
    public function aprobarActividad($id)
    {
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );
        
        $datos = Usuarios::findOrFail($idUsuario);
        
        $actividadesPendientes = ActividadesFinalizadas::obtenerActividadFinalizada(
            $id
        );

        $actividadFinalizada = ActividadesFinalizadas::findOrFail($id);
        
        $documentosSoporte = DocumentosSoporte::obtenerDocumentosSoporte(
            $actividadFinalizada->ACT_FIN_Actividad_Id
        );
        
        $documentosEvidencia = DocumentosEvidencias::obtenerDocumentosEvidencia(
            $id
        );

        $perfil = Usuarios::obtenerPerfilOperacionActividad(
            $actividadesPendientes->Id_Act
        );
        
        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas(
            $actividadFinalizada->ACT_FIN_Actividad_Id
        );
        
        return view(
            'cliente.actividades.aprobacion',
            compact(
                'actividadesPendientes',
                'datos',
                'perfil',
                'documentosSoporte',
                'documentosEvidencia',
                'respuestasAnteriores',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Descarga el documento de soprte para la actividad
     *
     * @param  $ruta  Identificador del nombre del archivo
     * @return response()->download()
     */
    public function descargarArchivo($ruta)
    {
        $ruta = public_path().'/documentos_soporte/'.$ruta;
        
        return response()
            ->download($ruta);
    }

    /**
     * Guarda la respuesta rechazado de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function respuestaRechazado(Request $request)
    {
        $idUsuario = session()->get('Usuario_Id');
        
        $rtaTest = Respuesta::obtenerRespuestaValidador($request->id);
        
        if ($rtaTest!=null) {
            Respuesta::actualizarRespuestaCliente(
                $request,
                6,
                $idUsuario
            );

            ActividadesFinalizadas::actualizarRevisadoActividad(
                $rtaTest->RTA_Actividad_Finalizada_Id
            );

            $actividad = ActividadesFinalizadas::obtenerActividadFinalizadaSola(
                $request->id
            );
            
            HistorialEstados::crearHistorialEstado($actividad->id, 6);
            Actividades::actualizarEstadoActividad($actividad->id, 1);
            $datos = Usuarios::findOrFail($idUsuario);
            $trabajador = Usuarios::obtenerPerfilAsociado($actividad->id);
            
            Notificaciones::crearNotificacion(
                'El Cliente '.
                    $datos->USR_Nombres_Usuario.
                    ' ha rechazado la entrega de la Actividad.',
                $idUsuario,
                $trabajador->ACT_Trabajador_Id,
                'actividades_perfil_operacion',
                null,
                null,
                'clear'
            );
            
            $para = Usuarios::findOrFail($trabajador->ACT_Trabajador_Id);
            $de = Usuarios::findOrFail($idUsuario);
            
            Mail::send('general.correo.informacion', [
                'nombre' => $de['USR_Nombre_Usuario'],
                'contenido' => 'Rechazó la entrega de la tarea '.$actividad->ACT_Nombre_Actividad.'.'
            ], function($message) use ($para, $actividad){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to(
                    $para['USR_Correo_Usuario'],
                    'InkBrutalPRY, Software de Gestión de Proyectos'
                )->subject('Entrega de la tarea '.$actividad->ACT_Nombre_Actividad.', rechazada');
            });
        }
        
        return redirect()
            ->route('actividades_cliente')
            ->with('mensaje', 'Respuesta envíada');
    }

    /**
     * Guarda la respuesta aprobado de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function respuestaAprobado(Request $request)
    {
        $idUsuario = session()->get('Usuario_Id');

        $rtaTest = Respuesta::obtenerRespuestaValidador($request->id);
        
        if ($rtaTest!=null) {
            Respuesta::actualizarRespuestaCliente(
                $request,
                7,
                $idUsuario
            );
            
            ActividadesFinalizadas::actualizarRevisadoActividad(
                $rtaTest->RTA_Actividad_Finalizada_Id
            );
            
            $actividad = ActividadesFinalizadas::obtenerActividadFinalizadaSola(
                $request->id
            );
            
            HistorialEstados::crearHistorialEstado($actividad->id, 7);
            $datos = Usuarios::findOrFail($idUsuario);
            $trabajador = Usuarios::obtenerPerfilAsociado($actividad->id);
            
            Notificaciones::crearNotificacion(
                'El Cliente '.
                    $datos->USR_Nombres_Usuario.
                    ' ha aprobado la entrega de la Actividad.',
                $idUsuario,
                $trabajador->ACT_Trabajador_Id,
                'actividades_perfil_operacion',
                null,
                null,
                'done_all'
            );

            Notificaciones::crearNotificacion(
                'El Cliente '.$datos->USR_Nombres_Usuario.' ha aprobado la entrega una tarea',
                $idUsuario,
                $datos->USR_Supervisor_Id,
                'cobros',
                null,
                null,
                'done_all'
            );
            
            $para = Usuarios::findOrFail($datos->USR_Supervisor_Id);
            $de = Usuarios::findOrFail($idUsuario);
            
            Mail::send('general.correo.informacion', [
                'nombre' => $de['USR_Nombre_Usuario'],
                'contenido' => 'Aprobó la entrega de la tarea '.$actividad->ACT_Nombre_Actividad.'.'
            ], function($message) use ($para, $actividad){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to(
                    $para['USR_Correo_Usuario'],
                    'InkBrutalPRY, Software de Gestión de Proyectos'
                )->subject('Tarea '.$actividad->ACT_Nombre_Actividad.' finalizada');
            });
        }
        
        return redirect()
            ->route('actividades_cliente')
            ->with('mensaje', 'Respuesta envíada');
    }
}