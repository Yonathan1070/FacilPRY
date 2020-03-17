<?php

namespace App\Http\Controllers\Administrador;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Usuarios;
use stdClass;

/**
 * Inicio Controller, donde se mostrarán las metricas del sistema para el administrador
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */

class InicioController extends Controller
{
    /**
     * Muestra las metricas de los proyectos y de los trabajadores
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        // Datos de las notificaciones y del usuario
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $proyectos = Proyectos::get();
        $trabajadores = Usuarios::obtenerTrabajadores();
        
        $metricasG = $this->metricasGenerales();
        $metricasT = $this->metricasTrabajadores();

        $chartBarEficacia=$metricasT['barrEficacia'];
        $chartBarEficiencia=$metricasT['barrEficiencia'];
        $chartBarEfectividad=$metricasT['barrEfectividad'];

        $chartEficacia=$metricasG['eficacia'];
        $chartEficiencia=$metricasG['eficiencia'];
        $chartEfectividad=$metricasG['efectividad'];
        
        return view(
            'administrador.inicio',
            compact(
                'datos',
                'notificaciones', 
                'trabajadores',
                'cantidad',
                'chartEficacia',
                'chartEficiencia',
                'chartEfectividad',
                'chartBarEficacia',
                'chartBarEficiencia',
                'chartBarEfectividad'
            )
        );
    }

    /**
     * Obtiene los datos de eficiencia, eficacia, efectividad y productividad de los proyectos
     *
     * @return $datos Datos de los indicadores
     */
    public function metricasGenerales()
    {
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $proyectos = Proyectos::where('PRY_Estado_Proyecto', '=', 1)->get();
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
        $apiEficacia = route('eficacia_general');
        $chartEficacia->labels($pryEficaciaLlave)->load($apiEficacia);

        $chartEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_general');
        $chartEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_general');
        $chartEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);

        $datos = [
            'eficacia'=> $chartEficacia,
            'eficiencia'=>$chartEficiencia,
            'efectividad'=>$chartEfectividad
        ];

        return $datos;
    }

    /**
     * Obtiene los datos de eficiencia, eficacia y efectividad de los trabajadores
     *
     * @return $datos Datos de los indicadores
     */
    public function metricasTrabajadores()
    {
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $trabajadores = Usuarios::obtenerTrabajadores();
        foreach ($trabajadores as $key => $trabajador) {
            $eficacia[++$key] = [$trabajador->USR_Nombres_Usuario];
            $eficiencia[++$key] = [$trabajador->USR_Nombres_Usuario];
            $efectividad[++$key] = [$trabajador->USR_Nombres_Usuario];
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
        
        $chartbarrEficacia = new Eficacia;
        $apiEficacia = route('eficacia_barras_trabajador');
        $chartbarrEficacia->labels($pryEficaciaLlave)->load($apiEficacia);

        $chartbarrEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_barras_trabajador');
        $chartbarrEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartbarrEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_barras_trabajador');
        $chartbarrEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);

        $datos = [
            'barrEficacia'=> $chartbarrEficacia,
            'barrEficiencia'=>$chartbarrEficiencia,
            'barrEfectividad'=>$chartbarrEfectividad
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