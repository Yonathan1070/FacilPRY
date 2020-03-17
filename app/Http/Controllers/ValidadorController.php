<?php

namespace App\Http\Controllers;

use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;
use App\Models\Tablas\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Validador Controller, donde se veran detalles de la entrega
 * de las actividades y se dará respuesta de aprobado o rechazado
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class ValidadorController extends Controller
{
    /**
     * Muestra el listado de las actividades finalizadas
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        can('validador');
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesPendientes = ActividadesFinalizadas::obtenerActividadesAprobarValidador();
        
        return view(
            'tester.inicio',
            compact(
                'actividadesPendientes',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Vista detallada de la actividad entregada
     *
     * @param  $id  Identificador de la actividad finalizada
     * @return \Illuminate\View\View Vista detallada de la entrega de la actividad>>
     */
    public function aprobacionActividad($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $actividadesPendientes = ActividadesFinalizadas::obtenerActividadFinalizada($id);
        $documentosSoporte = DocumentosSoporte::obtenerDocumentoSoporteFinalizada($id);
        $documentosEvidencia = DocumentosEvidencias::obtenerDocumentosEvidencia($id);

        $perfil = Usuarios::obtenerPerfilOperacionActividad($actividadesPendientes->Id_Act);
        $actividadFinalizada = ActividadesFinalizadas::findOrFail($id);
        
        $respuestasAnteriores = Respuesta::obtenerHistoricoRespuestas(
            $actividadFinalizada->ACT_FIN_Actividad_Id
        );
        
        return view(
            'tester.aprobacion',
            compact(
                'actividadesPendientes',
                'perfil',
                'datos',
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
        
        return response()->download($ruta);
    }

    /**
     * Guarda la respuesta rechazado de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function respuestaRechazado(Request $request)
    {
        Respuesta::actualizarRespuesta($request, 6);
        ActividadesFinalizadas::actualizarRevisadoActividad($request->id);
        $actividad = $this->actividad($request->id);
        HistorialEstados::crearHistorialEstado($actividad->id, 6);
        Actividades::actualizarEstadoActividad($actividad->id, 1);
        HistorialEstados::crearHistorialEstado($actividad->id, 1);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $trabajador = Usuarios::obtenerPerfilAsociado($actividad->id);
        
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' ha rechazado la entrega de la Tarea.',
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
            'titulo' => $datos->USR_Nombres_Usuario.' ha rechazado la entrega de la Tarea.',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' a rechazado la entrega de la tarea.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'],
                'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Entrega de la tarea, rechazada');
        });
        
        return redirect()
            ->route('inicio_validador')
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
        Respuesta::actualizarRespuesta($request, 5);
        ActividadesFinalizadas::actualizarRevisadoActividad($request->id);
        Respuesta::crearRespuesta($request->id, 12);
        $idActFin = ActividadesFinalizadas::orderByDesc('created_at')->first()->id;
        $actividad = $this->actividad($request->id);
        HistorialEstados::crearHistorialEstado($actividad->id, 5);
        Actividades::actualizarEstadoActividad($actividad->id, 3);
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
        
        $para = Usuarios::findOrFail($trabajador->PRY_Cliente_Id);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Mail::send('general.correo.informacion', [
            'titulo' => 'Se ha finalizado una actividad del proyecto '.
                $trabajador->PRY_Nombre_Proyecto,
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' está en espera de su aprobado en tarea entregada.'
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'],
                'InkBrutalPRY, Software de Gestión de Proyectos'
            )->subject('Tarea finalizada, pendiente de aprobación');
        });
        
        return redirect()
            ->route('inicio_validador')
            ->with('mensaje', 'Respuesta envíada');
    }

    public function actividad($id)
    {
        $actividad = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->select('a.id')
            ->where('af.id', '=', $id)
            ->first();

        return $actividad;
    }
}
