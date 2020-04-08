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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-calificaciones');

        $permisos = ['listar'=> can2('listar-decisiones'), 'calificar'=> can2('calificar-trabajadores')];
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $calificaciones = Calificaciones::obtenerCalificaciones();

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();

        return view('calificaciones.listar', compact('datos', 'notificaciones', 'cantidad', 'permisos', 'calificaciones', 'asignadas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function calificar()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $calificaciones = Calificaciones::obtenerCalificacionesFecha(Carbon::now()->format('yy-m-d'));

        
        if(count($calificaciones) != 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'Ya se ha realizado la respectiva calificacion, revisa el historico de calificaciones'
                );
        }
        $hoy1 = Carbon::now();
        $hoy2 = Carbon::now();
        if($hoy1->format('d') != '1' || $hoy1->format('d') != '16') {
            return redirect()
                ->back()
                ->withErrors(
                    'Las fechas disponibles para calificar son los 1 o 16 de cada mes.'
                );
        }
        $fechaFin = $hoy1->subDays(1);
        $fechaInicio = $hoy2->subDays(15);
        if($fechaFin->format('d') == '31') {
            $fechaInicio = $hoy2->subDays(1);
        }
        $perfilOperacion = Usuarios::obtenerPerfilOperacion();

        foreach ($perfilOperacion as $key => $po) {
            $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesRango($fechaInicio, $fechaFin, $po->id);
            $actividadesTotales = Actividades::obtenerActividadesRango($fechaInicio, $fechaFin, $po->id);
            try {
                $eficaciaPorcentaje = (
                    (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
                );
            } catch(Exception $ex) {
                $eficaciaPorcentaje = 0;
            }
            $decision = Decisiones::obtenerDecisionPorRango((int)$eficaciaPorcentaje);
            Calificaciones::guardarCalificacion($eficaciaPorcentaje, $po->id, $decision->id);
        }
        $calificaciones = Calificaciones::obtenerCalificacionesFecha(Carbon::now()->format('yy-m-d'));

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();
        
        return view('calificaciones.calificar', compact('datos', 'notificaciones', 'cantidad', 'calificaciones', 'asignadas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function obtener($id)
    {
        $calificacion = Calificaciones::obtenerCalificacionId($id);

        return json_encode($calificacion);
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
