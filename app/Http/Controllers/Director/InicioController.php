<?php

namespace App\Http\Controllers\Director;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Usuarios;
use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $proyectos = Proyectos::get();
        foreach ($proyectos as $key => $proyecto) {
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
                ->where('p.id', '=', $proyecto->id)
                ->get();
            $actividadesTotales = DB::table('TBL_Actividades as a')
                ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
                ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
                ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
                ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
                ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
                ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
                ->where('r.id', '<>', 5)
                ->where('p.id', '=', $proyecto->id)
                ->get();
            try{
                $eficaciaPorcentaje = ((count($actividadesFinalizadas) / count($actividadesTotales)) * 100);
            }catch(Exception $ex){
                $eficaciaPorcentaje = 0;
            }
            $eficacia[++$key] = [$proyecto->PRY_Nombre_Proyecto, $eficaciaPorcentaje];

            $actividades = DB::table('TBL_Horas_Actividad as ha')
                ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
                ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
                ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
                ->select(DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'), DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'), 'ha.*', 'a.*')
                ->where('p.id', '=', $proyecto->id)
                ->groupBy('HRS_ACT_Actividad_Id')->get();
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
            $eficiencia[++$key] = [$proyecto->PRY_Nombre_Proyecto, $eficienciaPorcentaje];

            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            $efectividad[++$key] = [$proyecto->PRY_Nombre_Proyecto, $efectividadPorcentaje];
        }
        $pryEficaciaLlave = []; $pryEficaciaValor = []; $pryEficaciaColor = [];
        $pryEficienciaLlave = []; $pryEficienciaValor = []; $pryEficienciaColor = [];
        $pryEfectividadLlave = []; $pryEfectividadValor = []; $pryEfectividadColor = [];

        foreach ($eficacia as $indEficacia) {
            array_push($pryEficaciaLlave, $indEficacia[0]);
            array_push($pryEficaciaValor, $indEficacia[1]);
            array_push($pryEficaciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        foreach ($eficiencia as $indEficiencia) {
            array_push($pryEficienciaLlave, $indEficiencia[0]);
            array_push($pryEficienciaValor, $indEficiencia[1]);
            array_push($pryEficienciaColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        foreach ($efectividad as $indEfectividad) {
            array_push($pryEfectividadLlave, $indEfectividad[0]);
            array_push($pryEfectividadValor, $indEfectividad[1]);
            array_push($pryEfectividadColor, sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        }
        //dd(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        $chartEficacia = new Eficacia;
        $chartEficacia->labels($pryEficaciaLlave);
        $dsetEficacia = $chartEficacia->dataset('Eficacia General', 'pie', $pryEficaciaValor);
        $dsetEficacia->backgroundColor(collect($pryEficaciaColor));

        $chartEficiencia = new Eficiencia;
        $chartEficiencia->labels($pryEficienciaLlave);
        $dsetEficiencia = $chartEficiencia->dataset('Eficacia General', 'pie', $pryEficienciaValor);
        $dsetEficiencia->backgroundColor(collect($pryEficienciaColor));

        $chartEfectividad = new Efectividad;
        $chartEfectividad->labels($pryEfectividadLlave);
        $dsetEfectividad = $chartEfectividad->dataset('Eficacia General', 'pie', $pryEfectividadValor);
        $dsetEfectividad->backgroundColor(collect($pryEfectividadColor));

        return view('director.inicio', compact('datos', 'notificaciones', 'cantidad', 'chartEficacia', 'chartEficiencia', 'chartEfectividad'));
    }

    public function metrica(){
        $eficacia[] = ['Proyecto', 'Porcentaje'];
        $eficiencia[] = ['Proyecto', 'Porcentaje'];
        $efectividad[] = ['Proyecto', 'Porcentaje'];

        $proyectos = Proyectos::get();
        foreach ($proyectos as $key => $proyecto) {
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
                ->where('p.id', '=', $proyecto->id)
                ->get();
            $actividadesTotales = DB::table('TBL_Actividades as a')
                ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
                ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
                ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
                ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
                ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
                ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
                ->where('r.id', '<>', 5)
                ->where('p.id', '=', $proyecto->id)
                ->get();
            $eficaciaPorcentaje = (count($actividadesFinalizadas) / count($actividadesTotales)) * 100;
            $eficacia[++$key] = [$proyecto->PRY_Nombre_Proyecto, $eficaciaPorcentaje];

            $actividades = DB::table('TBL_Horas_Actividad as ha')
                ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
                ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
                ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
                ->select(DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'), DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'), 'ha.*', 'a.*')
                ->where('p.id', '=', $proyecto->id)
                ->groupBy('HRS_ACT_Actividad_Id')->get();
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
            $eficienciaPorcentaje = ((count($actividadesFinalizadas) / $costoReal) * $horasReales) / ((count($actividadesTotales) / $costoEstimado) * $horasEstimadas) * 100;
            $eficiencia[++$key] = [$proyecto->PRY_Nombre_Proyecto, $eficienciaPorcentaje];

            $efectividadPorcentaje = (($eficienciaPorcentaje + $eficaciaPorcentaje) / 2);
            $efectividad[++$key] = [$proyecto->PRY_Nombre_Proyecto, $efectividadPorcentaje];
        }
        dd($efectividad, $eficacia, $eficiencia);
        //dd('Eficacia = ' . $eficacia . '% - Eficiencia = ' . (int) $eficiencia . '% - Efectividad = ' . (int) $efectividad . '%');
        return json_encode($eficacia);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstadoNotificacion($id)
    {
        $notificacion = Notificaciones::findOrFail($id);
        $notificacion->update([
            'NTF_Estado' => 1
        ]);
        $notif = new stdClass();
        if ($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null) {
            $notif->ruta = route($notificacion->NTF_Route, [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]);
        } else if ($notificacion->NTF_Route != null) {
            $notif->ruta = route($notificacion->NTF_Route);
        }
        return json_encode($notif);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
