<?php

namespace App\Http\Controllers;

use App\Models\Tablas\Actividades;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Usuarios;
use Exception;

/**
 * Métricas Controller, donde se obtienen los datos de las métricas del sistema
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class MetricasController extends Controller
{
    /**
     * Obtiene los datos del indicador de eficacia por proyectos
     *
     * @return json_encode Datos del indicador de eficacia por proyectos
     * 
     */
    public function metricaEficaciaGeneral()
    {
        $eficacia = [];

        $proyectos = Proyectos::where('PRY_Estado_Proyecto', '=', 1)->get();
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = $this->obtenerFinalizadas($proyecto->id);
            $actividadesTotales = $this->obtenerTotales($proyecto->id);
            
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            $eficacia[++$key] = [$proyecto->PRY_Nombre_Proyecto, (int)$eficaciaPorcentaje];
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
     * Obtiene los datos del indicador de eficiencia por proyectos
     *
     * @return json_encode Datos del indicador de eficiencia por proyectos
     * 
     */
    public function metricaEficienciaGeneral()
    {
        $eficiencia = [];
        
        $proyectos = Proyectos::where('PRY_Estado_Proyecto', '=', 1)->get();
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = $this->obtenerFinalizadas($proyecto->id);
            $actividadesTotales = $this->obtenerTotales($proyecto->id);
            $actividades = $this->obtenerActividades($proyecto->id);
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

            $eficiencia[++$key] = [$proyecto->PRY_Nombre_Proyecto,(int)$eficienciaPorcentaje];
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
     * Obtiene los datos del indicador de efectividad por proyectos
     *
     * @return json_encode Datos del indicador de efectividad por proyectos
     * 
     */
    public function metricaEfectividadGeneral()
    {
        $efectividad = [];

        $proyectos = Proyectos::where('PRY_Estado_Proyecto', '=', 1)->get();
        
        foreach ($proyectos as $key => $proyecto) {
            #Obtenermos la Eficacia
            $actividadesFinalizadas = $this->obtenerFinalizadas($proyecto->id);
            $actividadesTotales = $this->obtenerTotales($proyecto->id);
            
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            #Obtenemos la Eficiencia
            $actividades = $this->obtenerActividades($proyecto->id);
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

    /**
     * Obtiene los datos del indicador de profuctividad por proyectos
     *
     * @return json_encode Datos del indicador de productividad por proyectos
     * 
     */
    public function metricaProductividad()
    {
        $proyectos = Proyectos::where('PRY_Estado_Proyecto', '=', 1)->get();
        
        foreach ($proyectos as $key => $proyecto) {
            
            $actividades = $this->obtenerActividades($proyecto->id);
            $costoEstimado = 0;
            $costoReal = 0;

            foreach ($actividades as $actividad) {
                $costoReal = $costoReal + $actividad->ACT_Costo_Real_Actividad;
                $costoEstimado = $costoEstimado + $actividad->ACT_Costo_Estimado_Actividad;
            }
            
            try {
                $productividadPorcentaje = ($costoReal  / $costoEstimado) * 100;
            } catch(Exception $ex) {
                $productividadPorcentaje = 0;
            }

            $productividad[++$key] = [
                $proyecto->PRY_Nombre_Proyecto,
                (int)$productividadPorcentaje
            ];
        }

        $pryProductividadLlave = [];
        $pryProductividadValor = [];
        $pryProductividadColor = [];

        foreach ($productividad as $indProductividad) {
            array_push($pryProductividadLlave, $indProductividad[0]);
            array_push($pryProductividadValor, $indProductividad[1]);
            array_push($pryProductividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartProductividadPie = [
            'borderWidth' => 2,
            'backgroundColor' => $pryProductividadColor,
            'data' => $pryProductividadValor,
            'label' => 'Productividad',
            'type' => 'pie',
            'labels' => $pryProductividadLlave
        ];

        return json_encode($chartProductividadPie);
    }

    /**
     * Obtiene los datos del indicador de eficacia por trabajadores
     *
     * @return json_encode Datos del indicador de eficacia por trabajadores
     * 
     */
    public function barrasEficaciaPorTrabajador()
    {
        $eficacia = [];

        $trabajadores = Usuarios::obtenerTrabajadores();
        
        foreach ($trabajadores as $key => $trabajador) {
            $actividadesFinalizadas = $this->obtenerFinalizadasTrabajador($trabajador->id);
            $actividadesTotales = $this->obtenerTotalesTrabajador($trabajador->id);
            
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }
            
            $eficacia[++$key] = [
                $trabajador->USR_Nombres_Usuario,
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
            'label' => 'Eficacia Trabajadores',
            'type' => 'bar',
            'labels' => $pryEficaciaLlave
        ];

        return json_encode($chartEficaciaBar);
    }

    /**
     * Obtiene los datos del indicador de eficiencia por trabajadores
     *
     * @return json_encode Datos del indicador de eficiencia por trabajadores
     * 
     */
    public function barrasEficienciaPorTrabajador()
    {
        $eficiencia = [];

        $trabajadores = Usuarios::obtenerTrabajadores();
        
        foreach ($trabajadores as $key => $trabajador) {
            $actividadesFinalizadas = $this->obtenerFinalizadasTrabajador($trabajador->id);
            $actividadesTotales = $this->obtenerTotalesTrabajador($trabajador->id);
            $actividades = $this->obtenerActividadesHorasTrabajador($trabajador->id);
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
                $variableEstimada = ((count($actividadesFinalizadas) / $costoReal) * $horasReales);
                $variableReal = ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas);
                $eficienciaPorcentaje = $variableEstimada / $variableReal * 100;
            } catch(Exception $ex) {
                $eficienciaPorcentaje = 0;
            }
            
            $eficiencia[++$key] = [
                $trabajador->USR_Nombres_Usuario,
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
            'label' => 'Eficiencia Trabajadores',
            'type' => 'bar',
            'labels' => $pryEficienciaLlave
        ];

        return json_encode($chartEficienciaBar);
    }

    /**
     * Obtiene los datos del indicador de efectividad por trabajadores
     *
     * @return json_encode Datos del indicador de efectividad por trabajadores
     * 
     */
    public function barrasEfectividadPorTrabajador()
    {
        $trabajadores = Usuarios::obtenerTrabajadores();
        
        foreach ($trabajadores as $key => $trabajador) {
            #Obtenemos la Eficacia
            $actividadesFinalizadas = $this->obtenerFinalizadasTrabajador($trabajador->id);
            $actividadesTotales = $this->obtenerTotalesTrabajador($trabajador->id);
            
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            #Obtenemos la Eficiencia
            $actividades = $this->obtenerActividadesHorasTrabajador($trabajador->id);
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
                $variableReal = ((count($actividadesFinalizadas) / $costoReal) * $horasReales);
                $variableEstimada = ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas);
                $eficienciaPorcentaje = $variableReal / $variableEstimada * 100;
            }catch(Exception $ex){
                $eficienciaPorcentaje = 0;
            }

            #Obtenemos la Efectividad
            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            
            $efectividad[++$key] = [
                $trabajador->USR_Nombres_Usuario,
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
        
        $chartEfectividadBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEfectividadColor,
            'data' => $pryEfectividadValor,
            'label' => 'Efectividad Trabajadores',
            'type' => 'bar',
            'labels' => $pryEfectividadLlave
        ];
        
        return json_encode($chartEfectividadBar);
    }

    /**
     * Obtiene los datos de las actividades en estado finalizado
     *
     * @return $actividadesFinalizadas array de las actividades finalizadas
     * 
     */
    public function obtenerFinalizadas($id)
    {
        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadas($id);
        
        return $actividadesFinalizadas;
    }

    /**
     * Obtiene los datos de todas las actividades
     *
     * @return $actividadesTotales array de todas las actividades
     * 
     */
    public function obtenerTotales($id)
    {
        $actividadesTotales = Actividades::obtenerActividadesTotales($id);
        
        return $actividadesTotales;
    }

    /**
     * Obtiene los datos de las actividades en estado finalizado por cada trabajador
     *
     * @return $actividadesFinalizadas array de las actividades finalizadas por trabajador
     * 
     */
    public function obtenerFinalizadasTrabajador($id)
    {
        $actividadesFinalizadas = 
            Actividades::obtenerActividadesFinalizadasTrabajador($id);
        
        return $actividadesFinalizadas;
    }

    /**
     * Obtiene los datos de todas las actividades de cada trabajador
     *
     * @return $actividadesTotales array de todas las actividades de los trabajadores
     * 
     */
    public function obtenerTotalesTrabajador($id)
    {
        $actividadesTotales = Actividades::obtenerActividadesTotalesTrabajador($id);
        
        return $actividadesTotales;
    }

    /**
     * Obtiene los datos de todas las actividades
     *
     * @return $actividades array de con todas las actividades
     * 
     */
    public function obtenerActividades($id){
        $actividades = Actividades::obtenerActividadesHoras($id);
        
        return $actividades;
    }

    /**
     * Obtiene los datos de las actividades con sus respectivas horas asignadas
     *
     * @return $actividades array con todas las actividades y horas de traajo asignadas
     * 
     */
    public function obtenerActividadesHorasTrabajador($id){
        $actividades = Actividades::obtenerHorasActividadesTrabajador($id);
        
        return $actividades;
    }

    /**
     * Obtiene los datos del indicador de eficacia del trabajador
     *
     * @return json_encode Datos del indicador de eficacia del trabajador
     * 
     */
    public function metricaEficaciaCarga($id)
    {
        $eficacia = [];

        $proyectos = Proyectos::obtenerProyectosPerfil($id);
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPRY_TRB(
                $proyecto->id,
                $id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotalesPRY_TRB(
                $proyecto->id,
                $id
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
    public function metricaEficienciaCarga($id)
    {
        $eficiencia = [];
        
        $proyectos = Proyectos::obtenerProyectosPerfil($id);
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPRY_TRB(
                $proyecto->id,
                $id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotalesPRY_TRB(
                $proyecto->id,
                $id
            );
            $actividades = Actividades::obtenerActividadesHorasPRY_TRB(
                $proyecto->id,
                $id
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
    public function metricaEfectividadCarga($id)
    {
        $efectividad = [];

        $proyectos = Proyectos::obtenerProyectosPerfil($id);
        
        foreach ($proyectos as $key => $proyecto) {
            #Obtenermos la Eficacia
            $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasPRY_TRB(
                $proyecto->id,
                $id
            );

            $actividadesTotales = Actividades::obtenerActividadesTotalesPRY_TRB(
                $proyecto->id,
                $id
            );
            
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }

            #Obtenemos la Eficiencia
            $actividades = Actividades::obtenerActividadesHorasPRY_TRB(
                $proyecto->id,
                $id
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