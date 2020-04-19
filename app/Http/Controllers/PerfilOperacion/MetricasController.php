<?php

namespace App\Http\Controllers\PerfilOperacion;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Proyectos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricasController extends Controller
{
    /**
     * Obtiene los datos del indicador de eficacia del trabajador autenticado
     *
     * @return json_encode Datos del indicador de eficacia por proyectos
     * 
     */
    public function metricaEficaciaGeneral()
    {
        $idUsuario = session()->get('Usuario_Id');
        $eficacia = [];

        $proyectos = Proyectos::obtenerProyectosMetricas($idUsuario);
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesFinalizadasPerfil(
                $proyecto->id,
                $idUsuario
            );

            $actividadesTotales = Actividades::obtenerActividadesProyectoUsuario(
                $proyecto->id,
                $idUsuario
            );

            try{
                $eficaciaPorcentaje = ((count($actividadesFinalizadas) / count($actividadesTotales)) * 100);
            }catch(Exception $ex){
                $eficaciaPorcentaje = 0;
            }

            $eficacia[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$eficaciaPorcentaje
            ];
        }

        $pryEficaciaLlave = [];
        $pryEficaciaValor = [];
        $pryEficaciaColor = [];

        foreach ($eficacia as $indEficacia) {
            array_push($pryEficaciaLlave, $indEficacia[0]);
            array_push($pryEficaciaValor, $indEficacia[1]);
            array_push($pryEficaciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficaciaBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEficaciaColor,
            'data' => $pryEficaciaValor,
            'label' => 'Eficacia General',
            'type' => 'bar',
            'labels' => $pryEficaciaLlave
        ];
        
        return json_encode($chartEficaciaBar);
    }

    /**
     * Obtiene los datos del indicador de eficiencia del trabajador autenticado
     *
     * @return json_encode Datos del indicador de eficacia por proyectos
     * 
     */
    public function metricaEficienciaGeneral()
    {
        $idUsuario = session()->get('Usuario_Id');
        $eficiencia = [];

        $proyectos = Proyectos::obtenerProyectosMetricas($idUsuario);

        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesFinalizadasPerfil(
                $proyecto->id,
                $idUsuario
            );

            $actividadesTotales = Actividades::obtenerActividadesProyectoUsuario(
                $proyecto->id,
                $idUsuario
            );

            $actividades = HorasActividad::obtenerActividadesFinalizadas(
                $proyecto->id,
                $idUsuario
            );

            $costoEstimado = 0;
            $costoReal = 0;
            $horasEstimadas = 0;
            $horasReales = 0;

            foreach ($actividades as $actividad) {
                $costoReal = $costoReal + $actividad->ACT_Costo_Real_Actividad;
                $costoEstimado = $costoEstimado + $actividad->ACT_Costo_Estimado_Actividad;
                $horasEstimadas = $horasEstimadas + $actividad->HorasE;
                $horasReales = $horasReales + $actividad->HorasR;
            }

            try{
                $datosFinal = ((count($actividadesFinalizadas) / $costoReal) * $horasReales);
                $datosEstimado = ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas);
                $eficienciaPorcentaje = ($datosFinal / $datosEstimado) * 100;
            }catch(Exception $ex){
                $eficienciaPorcentaje = 0;
            }

            $eficiencia[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$eficienciaPorcentaje
            ];
        }

        $pryEficienciaLlave = [];
        $pryEficienciaValor = [];
        $pryEficienciaColor = [];

        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaLlave, $indEficiencia[0]);
            array_push($pryEficienciaValor, $indEficiencia[1]);
            array_push($pryEficienciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficienciaBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEficienciaColor,
            'data' => $pryEficienciaValor,
            'label' => 'Eficiencia General',
            'type' => 'bar',
            'labels' => $pryEficienciaLlave
        ];

        return json_encode($chartEficienciaBar);
    }

    /**
     * Obtiene los datos del indicador de efectividad del trabajador autenticado
     *
     * @return json_encode Datos del indicador de eficacia por proyectos
     * 
     */
    public function metricaEfectividadGeneral()
    {
        $idUsuario = session()->get('Usuario_Id');
        $efectividad = [];

        $proyectos = Proyectos::obtenerProyectosMetricas($idUsuario);
	
        foreach ($proyectos as $key => $proyecto) {
            #Obtenemos la Eficacia
            $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesFinalizadasPerfil(
                $proyecto->id,
                $idUsuario
            );

            $actividadesTotales = Actividades::obtenerActividadesProyectoUsuario(
                $proyecto->id,
                $idUsuario
            );

            try{
                $eficaciaPorcentaje = ((count($actividadesFinalizadas) / count($actividadesTotales)) * 100);
            }catch(Exception $ex){
                $eficaciaPorcentaje = 0;
            }

            #Obtenemos la Eficiencia
            $actividades = HorasActividad::obtenerActividadesFinalizadas(
                $proyecto->id,
                $idUsuario
            );

            $costoEstimado = 0;
            $costoReal = 0;
            $horasEstimadas = 0;
            $horasReales = 0;

            foreach ($actividades as $actividad) {
                $costoReal = $costoReal + $actividad->ACT_Costo_Real_Actividad;
                $costoEstimado = $costoEstimado + $actividad->ACT_Costo_Estimado_Actividad;
                $horasEstimadas = $horasEstimadas + $actividad->HorasE;
                $horasReales = $horasReales + $actividad->HorasR;
            }
            try{
                $datosFinal = ((count($actividadesFinalizadas) / $costoReal) * $horasReales);
                $datosEstimado = ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas);
                $eficienciaPorcentaje = ($datosFinal / $datosEstimado) * 100;
            }catch(Exception $ex){
                $eficienciaPorcentaje = 0;
            }

            #Obtenemos la Efectividad
            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            $efectividad[++$key] = [$proyecto->PRY_Nombre_Proyecto, (int)$efectividadPorcentaje];
        }

        $pryEfectividadLlave = [];
        $pryEfectividadValor = [];
        $pryEfectividadColor = [];

        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadLlave, $indEfectividad[0]);
            array_push($pryEfectividadValor, $indEfectividad[1]);
            array_push($pryEfectividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEfectividadBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEfectividadColor,
            'data' => $pryEfectividadValor,
            'label' => 'Efectividad General',
            'type' => 'bar',
            'labels' => $pryEfectividadLlave
        ];

        return json_encode($chartEfectividadBar);
    }

    /**
     * Obtiene los datos del indicador de eficacia del trabajador
     *
     * @return json_encode Datos del indicador de eficacia del trabajador
     * 
     */
    public function metricaEficaciaCarga()
    {
        $idUsuario = session()->get('Usuario_Id');
        $eficacia = [];

        $proyectos = Proyectos::obtenerProyectosPerfil($idUsuario);
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadas(
                $proyecto->id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotales(
                $proyecto->id
            );

            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            $eficacia[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$eficaciaPorcentaje
            ];
        }

        $pryEficaciaLlave = [];
        $pryEficaciaValor = [];
        $pryEficaciaColor = [];

        foreach ($eficacia as $indEficacia) {
            array_push($pryEficaciaLlave, $indEficacia[0]);
            array_push($pryEficaciaValor, $indEficacia[1]);
            array_push($pryEficaciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficaciaPie = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEficaciaColor,
            'data' => $pryEficaciaValor,
            'label' => 'Eficacia General',
            'type' => 'pie',
            'labels' => $pryEficaciaLlave
        ];
        return json_encode($chartEficaciaPie);
    }

    /**
     * Obtiene los datos del indicador de eficiencia del trabajador
     *
     * @return json_encode Datos del indicador de eficiencia del trabajador
     * 
     */
    public function metricaEficienciaCarga()
    {
        $idUsuario = session()->get('Usuario_Id');
        $eficiencia = [];
        
        $proyectos = Proyectos::obtenerProyectosPerfil($idUsuario);

        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadas(
                $proyecto->id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotales(
                $proyecto->id
            );

            $actividades = Actividades::obtenerActividadesHoras(
                $proyecto->id
            );

            $costoEstimado = 0;
            $costoReal = 0;
            $horasEstimadas = 0;
            $horasReales = 0;

            foreach ($actividades as $actividad) {
                $costoReal = $costoReal + $actividad->ACT_Costo_Real_Actividad;
                $costoEstimado = $costoEstimado + $actividad->ACT_Costo_Estimado_Actividad;
                $horasEstimadas = $horasEstimadas + $actividad->HorasE;
                $horasReales = $horasReales + $actividad->HorasR;
            }

            try {
                $variableReal = (count($actividadesFinalizadas) / $costoReal) * $horasReales;
                $variableEstimada = (count($actividadesTotales) / $costoEstimado) * $horasEstimadas;
                $eficienciaPorcentaje = ($variableReal / $variableEstimada) * 100;
            } catch(Exception $ex) {
                $eficienciaPorcentaje = 0;
            }

            $eficiencia[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$eficienciaPorcentaje
            ];
        }
        $pryEficienciaLlave = [];
        $pryEficienciaValor = [];
        $pryEficienciaColor = [];

        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaLlave, $indEficiencia[0]);
            array_push($pryEficienciaValor, $indEficiencia[1]);
            array_push($pryEficienciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficienciaPie = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEficienciaColor,
            'data' => $pryEficienciaValor,
            'label' => 'Eficiencia General',
            'type' => 'pie',
            'labels' => $pryEficienciaLlave
        ];

        return json_encode($chartEficienciaPie);
    }

    /**
     * Obtiene los datos del indicador de efectividad del trabajador
     *
     * @return json_encode Datos del indicador de efectividad del trabajador
     * 
     */
    public function metricaEfectividadCarga()
    {
        $idUsuario = session()->get('Usuario_Id');
        $efectividad = [];

        $proyectos = Proyectos::obtenerProyectosPerfil($idUsuario);
        foreach ($proyectos as $key => $proyecto) {
            #Obtenermos la Eficacia
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadas(
                $proyecto->id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotales(
                $proyecto->id
            );

            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            #Obtenemos la Eficiencia
            $actividades = Actividades::obtenerActividadesHoras(
                $proyecto->id
            );

            $costoEstimado = 0;
            $costoReal = 0;
            $horasEstimadas = 0;
            $horasReales = 0;

            foreach ($actividades as $actividad) {
                $costoReal = $costoReal + $actividad->ACT_Costo_Real_Actividad;
                $costoEstimado = $costoEstimado + $actividad->ACT_Costo_Estimado_Actividad;
                $horasEstimadas = $horasEstimadas + $actividad->HorasE;
                $horasReales = $horasReales + $actividad->HorasR;
            }

            try {
                $variableReal = ((count($actividadesFinalizadas) / $costoReal) * $horasReales);
                $variableEstimada = ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas);
                $eficienciaPorcentaje = ($variableReal / $variableEstimada) * 100;
            } catch(Exception $ex) {
                $eficienciaPorcentaje = 0;
            }

            #Obtenemos la Efectividad
            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            $efectividad[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$efectividadPorcentaje
            ];
        }

        $pryEfectividadLlave = [];
        $pryEfectividadValor = [];
        $pryEfectividadColor = [];

        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadLlave, $indEfectividad[0]);
            array_push($pryEfectividadValor, $indEfectividad[1]);
            array_push($pryEfectividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficienciaPie = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEfectividadColor,
            'data' => $pryEfectividadValor,
            'label' => 'Efectividad General',
            'type' => 'pie',
            'labels' => $pryEfectividadLlave
        ];

        return json_encode($chartEficienciaPie);
    }
}