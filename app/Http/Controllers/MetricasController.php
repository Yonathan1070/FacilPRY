<?php

namespace App\Http\Controllers;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use App\Models\Tablas\Proyectos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricasController extends Controller
{
    public function metricaEficaciaGeneral(){
        $eficacia = [];

        $proyectos = Proyectos::get();
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
        $pryEficaciaValor = []; $pryEficaciaColor = [];

        foreach ($eficacia as $indEficacia) {
            array_push($pryEficaciaValor, $indEficacia[1]);
            array_push($pryEficaciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        $chartEficacia = new Eficacia;
        $dsetEficacia = $chartEficacia->dataset('Eficacia General', 'pie', $pryEficaciaValor);
        $dsetEficacia->backgroundColor(collect($pryEficaciaColor));
        
        return $chartEficacia->api();
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
        $pryEficienciaValor = []; $pryEficienciaColor = [];

        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaValor, $indEficiencia[1]);
            array_push($pryEficienciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        $chartEficiencia = new Eficiencia;
        $dsetEficiencia = $chartEficiencia->dataset('Eficiencia General', 'pie', $pryEficienciaValor);
        $dsetEficiencia->backgroundColor(collect($pryEficienciaColor));
        
        return $chartEficiencia->api();
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

        $pryEfectividadValor = []; $pryEfectividadColor = [];

        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadValor, $indEfectividad[1]);
            array_push($pryEfectividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        $chartEfectividad = new Efectividad;
        $dsetEfectividad = $chartEfectividad->dataset('Efectividad General', 'pie', $pryEfectividadValor);
        $dsetEfectividad->backgroundColor(collect($pryEfectividadColor));
        
        return $chartEfectividad->api();
    }

    public function obtenerFinalizadas($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.id', '<>', 1)
            ->where('e.id', '<>', 2)
            ->where('r.id', '<>', 5)
            ->where('p.id', '=', $id)
            ->get();
        return $actividadesFinalizadas;
    }

    public function obtenerTotales($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '<>', 5)
            ->where('p.id', '=', $id)
            ->get();
        return $actividadesTotales;
    }

    public function obtenerActividades($id){
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->select(DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'), DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'), 'ha.*', 'a.*')
            ->where('p.id', '=', $id)
            ->groupBy('HRS_ACT_Actividad_Id')->get();
        return $actividades;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
