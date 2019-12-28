<?php

namespace App\Http\Controllers\PerfilOperacion;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricasController extends Controller
{
    public function metricaEficaciaGeneral(Request $request){
        $eficacia = [];

        $proyectos = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->where('uu.id', '=', session()->get('Usuario_Id'))
            ->get();
        
        foreach ($proyectos as $key => $proyecto) {
            $actividadesFinalizadas = $this->obtenerFinalizadas($proyecto->id);
            $actividadesTotales = $this->obtenerTotales($proyecto->id);
            try{
                $eficaciaPorcentaje = ((count($actividadesFinalizadas) / count($actividadesTotales)) * 100);
            }catch(Exception $ex){
                $eficaciaPorcentaje = 0;
            }
            $eficacia[++$key] = [$proyecto->PRY_Nombre_Proyecto, (int)$eficaciaPorcentaje];
        }
        $pryEficaciaLlave = []; $pryEficaciaValor = []; $pryEficaciaColor = [];

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
            'type' => 'pie',
            'labels' => $pryEficaciaLlave
        ];
        return json_encode($chartEficaciaBar);
    }

    public function metricaEficienciaGeneral(){
        $eficiencia = [];
        $proyectos = Proyectos::get();
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
            try{
                $eficienciaPorcentaje = ((count($actividadesFinalizadas) / $costoReal) * $horasReales) / ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas) * 100;
            }catch(Exception $ex){
                $eficienciaPorcentaje = 0;
            }
            $eficiencia[++$key] = [$proyecto->PRY_Nombre_Proyecto,(int)$eficienciaPorcentaje];
        }
        $pryEficienciaValor = []; $pryEficienciaValor = []; $pryEficienciaColor = [];

        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaLlave, $indEficiencia[0]);
            array_push($pryEficienciaValor, $indEficiencia[1]);
            array_push($pryEficienciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEficienciaBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEficienciaColor,
            'data' => $pryEficienciaValor,
            'label' => 'Eficacia General',
            'type' => 'bar',
            'labels' => $pryEficienciaLlave
        ];
        return json_encode($chartEficienciaBar);
    }

    public function metricaEfectividadGeneral(){
        $efectividad = [];

        $proyectos = Proyectos::get();
        foreach ($proyectos as $key => $proyecto) {
            //Obtenermos la Eficacia
            $actividadesFinalizadas = $this->obtenerFinalizadas($proyecto->id);
            $actividadesTotales = $this->obtenerTotales($proyecto->id);
            try{
                $eficaciaPorcentaje = ((count($actividadesFinalizadas) / count($actividadesTotales)) * 100);
            }catch(Exception $ex){
                $eficaciaPorcentaje = 0;
            }

            //Obtenemos la Eficiencia
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
            try{
                $eficienciaPorcentaje = ((count($actividadesFinalizadas) / $costoReal) * $horasReales) / ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas) * 100;
            }catch(Exception $ex){
                $eficienciaPorcentaje = 0;
            }

            //Obtenemos la Efectividad
            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            $efectividad[++$key] = [$proyecto->PRY_Nombre_Proyecto, (int)$efectividadPorcentaje];
        }

        $pryEfectividadLlave = []; $pryEfectividadValor = []; $pryEfectividadColor = [];

        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadLlave, $indEfectividad[0]);
            array_push($pryEfectividadValor, $indEfectividad[1]);
            array_push($pryEfectividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        
        $chartEfectividadBar = [
            'borderWidth' => 2,
            'backgroundColor' => $pryEfectividadColor,
            'data' => $pryEfectividadValor,
            'label' => 'Eficacia General',
            'type' => 'bar',
            'labels' => $pryEfectividadLlave
        ];
        return json_encode($chartEfectividadBar);
    }

    public function obtenerFinalizadas($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.id', '<>', 1)
            ->where('e.id', '<>', 2)
            ->where('uu.id', '=', session()->get('Usuario_Id'))
            ->get();
        return $actividadesFinalizadas;
    }

    public function obtenerTotales($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->where('uu.id', '=', session()->get('Usuario_Id'))
            ->get();
        return $actividadesTotales;
    }

    public function obtenerActividades($id){
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->select(DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'), DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'), 'ha.*', 'a.*')
            ->where('uu.id', '=', session()->get('Usuario_Id'))
            ->groupBy('HRS_ACT_Actividad_Id')->get();
        return $actividades;
    }
}