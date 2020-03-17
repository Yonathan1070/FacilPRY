<?php

namespace App\Http\Controllers\PerfilOperacion;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Usuarios;
use stdClass;

class InicioController extends Controller
{
    /**
     * Muestra las metricas de eficiencia, eficacia y efectividad para
     * el usuario autenticado
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $metricas = $this->metricasGenerales();
        
        $chartEficacia=$metricas['eficacia'];
        $chartEficiencia=$metricas['eficiencia'];
        $chartEfectividad=$metricas['efectividad'];

        return view(
            'perfiloperacion.inicio',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'chartEficacia',
                'chartEficiencia',
                'chartEfectividad'
            )
        );
    }

    public function metricasGenerales(){
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $proyectos = Proyectos::obtenerProyectosAsociados();
        
        foreach ($proyectos as $key => $proyecto) {
            $eficacia[++$key] = [$proyecto->PRY_Nombre_Proyecto];
            $eficiencia[++$key] = [$proyecto->PRY_Nombre_Proyecto];
            $efectividad[++$key] = [$proyecto->PRY_Nombre_Proyecto];
        }

        $pryEficaciaLlave = [];
        $pryEficienciaLlave = [];
        $pryEfectividadLlave = [];

        foreach ($eficacia as $indEficacia) {
            array_push($pryEficaciaLlave, $indEficacia[0]);
        }
        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaLlave, $indEficiencia[0]);
        }
        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadLlave, $indEfectividad[0]);
        }
        
        $chartEficacia = new Eficacia;
        $apiEficacia = route('eficacia_general_perfil_operacion');
        $chartEficacia->labels($pryEficaciaLlave)->load($apiEficacia);
        
        $chartEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_general_perfil_operacion');
        $chartEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_general_perfil_operacion');
        $chartEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);
        $datos = [
            'eficacia'=> $chartEficacia,
            'eficiencia'=>$chartEficiencia,
            'efectividad'=>$chartEfectividad
        ];
        
        return $datos;
    }

    /**
     * Cambia el estado de la notificación y retorna la ruta a la que debe redireccionar
     *
     * @param: $id Identificador de la notificación
     * @return json_encode Datos de la ruta
     * 
     */
    public function cambiarEstadoNotificacion($id)
    {
        $notificacion = Notificaciones::cambiarEstadoNotificacion($id);
        $notif = new stdClass();
        if($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null) {
            $notif->ruta = route(
                $notificacion->NTF_Route,
                [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]
            );
        } else if($notificacion->NTF_Route != null) {
            $notif->ruta = route($notificacion->NTF_Route);
        }
        return json_encode($notif);
    }

    /**
     * Cambia el estado de todas las notificaciónest retorna mesaje de éxito
     *
     * @param: $id Identificador del usuario autenticado
     * @return response()->json() Mensaje de exito
     * 
     */
    public function cambiarEstadoTodasNotificaciones($id)
    {
        Notificaciones::cambiarEstadoTodas($id);
        
        return response()
            ->json(['mensaje' => 'ok']);
    }
}
