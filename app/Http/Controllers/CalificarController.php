<?php

namespace App\Http\Controllers;

use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\Calificaciones;
use App\Models\Tablas\Decisiones;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Usuarios;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class CalificarController extends Controller
{
    /**
     * Muestra el listado del historial de calificaciones de los usuarios
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        can('listar-calificaciones');

        $permisos = ['listar'=> can2('listar-decisiones'), 'calificar'=> can2('calificar-trabajadores')];
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        $calificaciones = Calificaciones::obtenerCalificaciones();

        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

        $decisiones = Decisiones::get();
        
        $diferencia = Decisiones::obtenerDiferenciaDecisiones(2);
        $total = 0;
        foreach ($decisiones as $decision) {
            if ($decision->DCS_Rango_Inicio_Decision == 0) {
                $total = -1;
                break;
            }

        }

        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia + 1;
        }
        return view(
            'calificaciones.listar',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'calificaciones',
                'asignadas',
                'perfilesOperacion',
                'decisiones',
                'total'
            )
        );
    }

    /**
     * Muestra la vista con la calificación realizada al usuario
     *
     * @param  $request
     * @return \Illuminate\View\View Vista con la calificación realizada
     */
    public function calificar(Request $request)
    {
        can('listar-calificaciones');

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        if(
            $request->Fecha_Inicio_Rango >= Carbon::now()->format('yy-m-d') ||
            $request->Fecha_Fin_Rango >= Carbon::now()->format('yy-m-d')
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'Las fechas seleccionadas no pueden ser iguales o superiores a la fecha actual.'
                );
        }

        $perfilOperacion = Usuarios::findOrFail($request->Id_Perfil);
        
        $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesRango(
            $request->Fecha_Inicio_Rango.' 00:00:00',
            $request->Fecha_Fin_Rango.' 23:59:59',
            $request->Id_Perfil
        );

        $actividadesTotales = Actividades::obtenerActividadesRango(
            $request->Fecha_Inicio_Rango.' 00:00:00',
            $request->Fecha_Fin_Rango.' 23:59:59',
            $request->Id_Perfil
        );

        try {
            $eficaciaPorcentaje = (
                (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
            );
        } catch(Exception $ex) {
            $eficaciaPorcentaje = 0;
        }

        $decision = Decisiones::obtenerDecisionPorRango((int)$eficaciaPorcentaje);
        
        if ($decision == null) {
            return redirect()
                ->back()
                ->withErrors(
                    'No hay una decisión que se pueda asociar a la calificación obtenida.'
                );
        }

        $calif = Calificaciones::guardarCalificacion(
            $eficaciaPorcentaje,
            $request->Id_Perfil,
            $decision->id
        );

        $calificaciones = Calificaciones::obtenerCalificacionesId(
            $calif->id
        );
        
        return view(
            'calificaciones.calificar',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'calificaciones',
                'asignadas'
            )
        );
    }

    /**
     * Obtiene los detalles de la calificación para visualizarlos en un modal
     *
     * @param  $id  Identificador de la calificación
     * @return response()->json()
     */
    public function obtener($id)
    {
        can('listar-calificaciones');
        
        $calificacion = Calificaciones::obtenerCalificacionId($id);

        return json_encode($calificacion);
    }
}