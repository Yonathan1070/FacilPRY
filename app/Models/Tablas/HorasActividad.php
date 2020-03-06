<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Horas Actividad, donde se establecen los atributos de la tabla en la 
 * Base de Datos y se realizan las distintas operaciones sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class HorasActividad extends Model
{
    protected $table = "TBL_Horas_Actividad";
    protected $fillable = ['HRS_ACT_Actividad_Id',
        'HRS_ACT_Fecha_Actividad',
        'HRS_ACT_Cantidad_Horas_Asignadas',
        'HRS_ACT_Cantidad_Horas_Reales'];
    protected $guarded = ['id'];

    //Función que obtiene las fechas para el diagrama de Gantt
    public static function obtenerFechas($id)
    {
        $fechas = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*', 'ha.*')
            ->where('p.id', '=', $id)
            ->orderBy('ha.HRS_ACT_Fecha_Actividad')
            ->groupBy('ha.HRS_ACT_Fecha_Actividad')
            ->get();
        
        return $fechas;
    }

    //Función para obtener las horas para aprobar
    public static function obtenerHorasAprobar($idA)
    {
        $horasAprobar = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->select('ha.id as Id_Horas', 'ha.*', 'r.*', 'u.*', 'a.*')
            ->where('a.id', '=', $idA)
            ->where('ha.HRS_ACT_Cantidad_Horas_Reales', '=', null)
            ->get();
        
        return $horasAprobar;
    }

    //Función que obtiene las actividades para el diagrama de Gantt
    public static function obtenerActividadesGantt($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->leftjoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*')
            ->where('p.id', '=', $id)
            ->orderby('a.ACT_Fecha_Inicio_Actividad')
            ->get();
        
        return $actividades;
    }

    //Funcion para obtener las horas asignadas
    public static function obtenerHorasAsignadas($actividades, $fecha, $trabajador, $idH)
    {
        $horas = $actividades
            ->where('ha.HRS_ACT_Fecha_Actividad', '=', $fecha->HRS_ACT_Fecha_Actividad)
            ->where('a.ACT_Trabajador_Id', '=', $trabajador->ACT_Trabajador_Id)
            ->where('ha.id', '<>', $idH)
            ->sum('ha.HRS_ACT_Cantidad_Horas_Asignadas');
        
        return $horas;
    }

    //Función para obtener las horas de la actividad seleccionada
    public static function obtenerHorasActividad($id)
    {
        $horas = DB::table('TBL_Horas_Actividad')
            ->where('HRS_ACT_Actividad_Id', '=', $id)
            ->get();
        
        return $horas;
    }

    //Funcion para obtener cantidad de horas asignadas
    public static function obtenerHorasAsignadasActividad($id)
    {
        $horas = HorasActividad::where('HRS_ACT_Actividad_Id', '=', $id)
            ->sum('HRS_ACT_Cantidad_Horas_Asignadas');
        
        return $horas;
    }

    //Funcion para obtener cantidad de horas asignadas sin la hora seleccionada
    public static function obtenerHorasAsignadasNoSeleccionada($id, $fecha)
    {
        $horas = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->where('ha.HRS_ACT_Fecha_Actividad', '=', $fecha->HRS_ACT_Fecha_Actividad)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('ha.id', '<>', $id)
            ->sum('ha.HRS_ACT_Cantidad_Horas_Asignadas');
        
        return $horas;
    }

    //Función para crear las Horas asignadas para las actividades
    public static function crearHorasActividad($idA, $fecha)
    {
        HorasActividad::create([
            'HRS_ACT_Actividad_Id' => $idA,
            'HRS_ACT_Fecha_Actividad' => $fecha . " 23:59:00"
        ]);
    }

    //Función para actualizar las horas de la actividad
    public static function actualizarHoraActividad($horasAsignadas, $horasReales, $idH)
    {
        HorasActividad::findOrFail($idH)->update([
            'HRS_ACT_Cantidad_Horas_Asignadas' => $horasAsignadas,
            'HRS_ACT_Cantidad_Horas_Reales' => $horasReales
        ]);
    }

    //Función para actualizar las horas de la actividad
    public static function actualizarHoraRealActividad($request, $idH)
    {
        HorasActividad::findOrFail($idH)->update([
            'HRS_ACT_Cantidad_Horas_Asignadas' => $request->HRS_ACT_Cantidad_Horas_Asignadas,
            'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
        ]);
    }

    //Funcion para ajustar las horas asignadas de la actividad
    public static function actualizarHorasAsignadas($id, $cantidadHoras)
    {
        HorasActividad::findOrFail($id)->update([
            'HRS_ACT_Cantidad_Horas_Asignadas' => $cantidadHoras
        ]);
    }

    //Funcion para ajustar las horas reales de la actividad
    public static function actualizarHorasReales($idH, $request)
    {
        HorasActividad::findOrFail($idH)->update([
            'HRS_ACT_Cantidad_Horas_Reales' => $request->HRS_ACT_Cantidad_Horas_Asignadas
        ]);
    }
}
