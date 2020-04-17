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
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $calificaciones = Calificaciones::obtenerCalificaciones();

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));

        $perfilesOperacion = Usuarios::obtenerPerfilOperacion();

        return view('calificaciones.listar', compact('datos', 'notificaciones', 'cantidad', 'permisos', 'calificaciones', 'asignadas', 'perfilesOperacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function calificar(Request $request)
    {
        $asignadas = Actividades::obtenerActividadesProcesoPerfil(session()->get('Usuario_Id'));
        
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        if(
            $request->Fecha_Inicio_Rango >= Carbon::now()->format('yy-m-d') ||
            $request->Fecha_Fin_Rango >= Carbon::now()->format('yy-m-d')
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'Las fechas seleccionadas no pueden ser iguales o superiores a la fecha actual.'
                );
        }
        $perfilOperacion = Usuarios::findOrFail($request->ACT_Usuario_Id);
        
        $actividadesFinalizadas = ActividadesFinalizadas::obtenerActividadesRango($request->Fecha_Inicio_Rango, $request->Fecha_Fin_Rango, $request->ACT_Usuario_Id);
        $actividadesTotales = Actividades::obtenerActividadesRango($request->Fecha_Inicio_Rango, $request->Fecha_Fin_Rango, $request->ACT_Usuario_Id);
        try {
            $eficaciaPorcentaje = (
                (count($actividadesFinalizadas) / count($actividadesTotales)) * 100
            );
        } catch(Exception $ex) {
            $eficaciaPorcentaje = 0;
        }
        $decision = Decisiones::obtenerDecisionPorRango((int)$eficaciaPorcentaje);
        Calificaciones::guardarCalificacion($eficaciaPorcentaje, $request->ACT_Usuario_Id, $decision->id);
        $calificaciones = Calificaciones::obtenerCalificacionesFecha(Carbon::now()->format('yy-m-d'));
        
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
