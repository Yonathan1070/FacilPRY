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
        $proyectos = Proyectos::get();
        $trabajadores = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('r.RLS_Nombre_Rol', '<>', 'Administrador')
            ->where('r.RLS_Nombre_Rol', '<>', 'Director de Proyectos')
            ->where('r.RLS_Nombre_Rol', '<>', 'Cliente')
            ->select('u.*')
            ->get();
        
        $metricasG = $this->metricasGenerales();
        $metricasT = $this->metricasTrabajadores();

        $chartBarEficacia=$metricasT['barrEficacia'];
        $chartBarEficiencia=$metricasT['barrEficiencia'];
        $chartBarEfectividad=$metricasT['barrEfectividad'];

        $chartEficacia=$metricasG['eficacia'];
        $chartEficiencia=$metricasG['eficiencia'];
        $chartEfectividad=$metricasG['efectividad'];

        return view('director.inicio', compact('datos', 'proyectos', 'trabajadores', 'notificaciones', 'cantidad', 'chartEficacia', 'chartEficiencia', 'chartEfectividad', 'chartBarEficacia', 'chartBarEficiencia', 'chartBarEfectividad'));
    }

    public function metricasGenerales(){
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $proyectos = Proyectos::get();
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
        $apiEficacia = route('eficacia_general');
        $chartEficacia->labels($pryEficaciaLlave)->load($apiEficacia);

        $chartEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_general');
        $chartEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_general');
        $chartEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);

        $datos = ['eficacia'=> $chartEficacia, 'eficiencia'=>$chartEficiencia, 'efectividad'=>$chartEfectividad];
        return $datos;
    }

    public function metricasTrabajadores(){
        $eficacia = [];
        $eficiencia = [];
        $efectividad = [];

        $trabajadores = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('r.RLS_Nombre_Rol', '<>', 'Administrador')
            ->where('r.RLS_Nombre_Rol', '<>', 'Director de Proyectos')
            ->where('r.RLS_Nombre_Rol', '<>', 'Cliente')
            ->select('u.*')->get();
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
        //dd(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        $chartbarrEficacia = new Eficacia;
        $apiEficacia = route('eficacia_barras_trabajador');
        $chartbarrEficacia->labels($pryEficaciaLlave)->load($apiEficacia);

        $chartbarrEficiencia = new Eficiencia;
        $apiEficiencia = route('eficiencia_barras_trabajador');
        $chartbarrEficiencia->labels($pryEficienciaLlave)->load($apiEficiencia);

        $chartbarrEfectividad = new Efectividad;
        $apiEfectividad = route('efectividad_barras_trabajador');
        $chartbarrEfectividad->labels($pryEfectividadLlave)->load($apiEfectividad);

        $datos = ['barrEficacia'=> $chartbarrEficacia, 'barrEficiencia'=>$chartbarrEficiencia, 'barrEfectividad'=>$chartbarrEfectividad];
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
        if ($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null) {
            $notif->ruta = route($notificacion->NTF_Route, [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]);
        } else if ($notificacion->NTF_Route != null) {
            $notif->ruta = route($notificacion->NTF_Route);
        }
        return json_encode($notif);
    }

    public function cambiarEstadoTodasNotificaciones($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', $id)->get();
        foreach ($notificaciones as $notificacion) {
            $notificacion->update([
                'NTF_Estado' => 1
            ]);
        }
        return response()->json(['mensaje' => 'ok']);
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
