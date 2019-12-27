<?php

namespace App\Http\Controllers\PerfilOperacion;

use App\Charts\Efectividad;
use App\Charts\Eficacia;
use App\Charts\Eficiencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Usuarios;
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

        $metricas = $this->metricasGenerales();
        
        $chartEficacia=$metricas['eficacia'];
        $chartEficiencia=$metricas['eficiencia'];
        $chartEfectividad=$metricas['efectividad'];

        return view('perfiloperacion.inicio', compact('datos', 'notificaciones', 'cantidad', 'chartEficacia', 'chartEficiencia', 'chartEfectividad'));
    }

    public function metricasGenerales(){
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $proyectos = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->where('u.id', '=', session()->get('Usuario_Id'))
            ->select('u.*', 'p.*')->get();
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
        //dd(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        $chartEficacia = new Eficacia;
        $apiEficacia = route('eficacia_general_perfil_operacion');
        $chartEficacia->labels($pryEficaciaLlave)->load($apiEficacia);
        
        $chartEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_general_perfil_operacion');
        $chartEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_general_perfil_operacion');
        $chartEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);
        $datos = ['eficacia'=> $chartEficacia, 'eficiencia'=>$chartEficiencia, 'efectividad'=>$chartEfectividad];
        return $datos;
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
        if($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null)
            $notif->ruta = route($notificacion->NTF_Route, [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]);
        else if($notificacion->NTF_Route != null)
            $notif->ruta = route($notificacion->NTF_Route);
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
